<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\FlutterwaveService;
use App\Support\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function index(Request $request): RedirectResponse|View
    {
        $summary = Cart::summary('pickup');

        if ($summary['item_count'] === 0) {
            return redirect()->route('menu')->with('status', 'Add at least one item before checking out.');
        }

        return view('checkout.index', [
            'pageTitle' => 'Checkout',
            'pickupSummary' => $summary,
            'deliverySummary' => Cart::summary('delivery'),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fulfillment_type' => ['required', Rule::in(['pickup', 'delivery'])],
            'payment_method' => ['required', Rule::in(['cash-on-delivery', 'flutterwave'])],
            'phone' => ['required', 'string', 'max:30'],
            'location' => [
                Rule::requiredIf(fn () => $request->input('fulfillment_type') === 'delivery'),
                'nullable',
                'string',
                'max:255',
            ],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $summary = Cart::summary($validated['fulfillment_type']);

        if ($summary['item_count'] === 0) {
            return redirect()->route('menu')->with('status', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($request, $summary, $validated): Order {
            $order = $request->user()->orders()->create([
                'order_number' => 'CB-'.now()->format('ymd').'-'.Str::upper(Str::random(4)),
                'status' => 'confirmed',
                'fulfillment_type' => $validated['fulfillment_type'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cash-on-delivery' ? 'cash-on-delivery' : 'pending',
                'phone' => $validated['phone'],
                'location' => $validated['location'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $summary['subtotal'],
                'delivery_fee' => $summary['delivery_fee'],
                'total' => $summary['total'],
                'placed_at' => now(),
                'payment_reference' => null,
                'payment_provider_reference' => null,
                'payment_channel' => null,
                'paid_at' => null,
            ]);

            foreach ($summary['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        if ($validated['payment_method'] === 'flutterwave') {
            $this->recordPaymentActivity(
                $order,
                'checkout',
                'payment_initialized',
                'pending',
                'Order created and ready for Flutterwave checkout.',
                ['payment_method' => 'flutterwave']
            );

            return $this->redirectToFlutterwave($order, true);
        }

        $this->recordPaymentActivity(
            $order,
            'checkout',
            'cash_on_delivery_selected',
            'cash-on-delivery',
            'Customer selected Cash on Delivery.',
            ['payment_method' => 'cash-on-delivery']
        );

        Cart::clear();

        return redirect()->route('client.orders.show', $order)->with(
            'status',
            'Order '.$order->order_number.' placed successfully. A canteen runner will prepare it shortly.'
        );
    }

    public function restartFlutterwavePayment(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->payment_method === 'flutterwave', 404);

        if ($order->payment_status === 'paid') {
            return redirect()->route('client.orders.show', $order)->with('status', 'This order has already been paid successfully.');
        }

        $this->recordPaymentActivity(
            $order,
            'client-panel',
            'payment_retry_requested',
            'pending',
            'Customer requested another Flutterwave payment attempt.',
            []
        );

        return $this->redirectToFlutterwave($order, false);
    }

    public function flutterwaveCallback(Request $request, FlutterwaveService $flutterwave): RedirectResponse
    {
        $orderNumber = $request->string('tx_ref')->toString();
        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            return redirect()->route('home')->withErrors([
                'payment_method' => 'We could not match that Flutterwave payment to an order.',
            ]);
        }

        if ($request->string('status')->toString() !== 'successful' || ! $request->filled('transaction_id')) {
            $this->recordPaymentActivity(
                $order,
                'flutterwave-callback',
                'payment_cancelled_or_failed',
                'failed',
                'Flutterwave redirected back without a successful payment status.',
                $request->all()
            );

            return $this->redirectToOrderPage($request, $order)->withErrors([
                'payment_method' => 'Flutterwave payment was not completed. You can try again or use Cash on Delivery.',
            ]);
        }

        try {
            $transaction = $flutterwave->verifyTransaction((string) $request->input('transaction_id'));
        } catch (Throwable $exception) {
            report($exception);

            $this->recordPaymentActivity(
                $order,
                'flutterwave-callback',
                'verification_failed',
                'error',
                'Flutterwave callback arrived but verification failed.',
                ['error' => $exception->getMessage()]
            );

            return $this->redirectToOrderPage($request, $order)->withErrors([
                'payment_method' => 'We could not verify the Flutterwave payment. Please try again shortly.',
            ]);
        }

        if (! $flutterwave->transactionMatchesOrder($transaction, $order)) {
            $this->recordPaymentActivity(
                $order,
                'flutterwave-callback',
                'verification_mismatch',
                'mismatch',
                'Flutterwave callback details did not match the stored order.',
                $transaction
            );

            return $this->redirectToOrderPage($request, $order)->withErrors([
                'payment_method' => 'The Flutterwave payment details did not match this order.',
            ]);
        }

        if ($order->payment_status !== 'paid') {
            $order->update([
                ...$flutterwave->extractPaymentDetails($transaction),
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            $this->recordPaymentActivity(
                $order,
                'flutterwave-callback',
                'payment_confirmed',
                'paid',
                'Flutterwave payment verified successfully through the customer callback.',
                $transaction
            );
        }

        return $this->redirectToOrderPage($request, $order)->with(
            'status',
            'Flutterwave payment confirmed successfully for order '.$order->order_number.'.'
        );
    }

    private function redirectToFlutterwave(Order $order, bool $clearCartOnSuccess): RedirectResponse
    {
        $order->loadMissing('user');

        try {
            $paymentLink = app(FlutterwaveService::class)->initializeCheckout($order);
        } catch (Throwable $exception) {
            report($exception);

            $this->recordPaymentActivity(
                $order,
                'flutterwave-initialization',
                'payment_link_failed',
                'error',
                'Flutterwave checkout link could not be created.',
                ['error' => $exception->getMessage()]
            );

            if ($clearCartOnSuccess) {
                $order->items()->delete();
                $order->delete();
            }

            return back()->withErrors([
                'payment_method' => 'Unable to start Flutterwave checkout right now. Please try again or use Cash on Delivery.',
            ]);
        }

        $this->recordPaymentActivity(
            $order,
            'flutterwave-initialization',
            'payment_link_created',
            'pending',
            'Flutterwave checkout link generated successfully.',
            ['redirect' => $paymentLink]
        );

        if ($clearCartOnSuccess) {
            Cart::clear();
        }

        return redirect()->away($paymentLink);
    }

    private function recordPaymentActivity(
        Order $order,
        string $source,
        string $type,
        string $status,
        string $message,
        array $payload
    ): void {
        $order->paymentActivities()->create([
            'source' => $source,
            'type' => $type,
            'status' => $status,
            'message' => $message,
            'payload' => $payload,
            'happened_at' => now(),
        ]);
    }

    private function redirectToOrderPage(Request $request, Order $order): RedirectResponse
    {
        if ($request->user()?->id === $order->user_id) {
            return redirect()->route('client.orders.show', $order);
        }

        $request->session()->put('url.intended', route('client.orders.show', $order));

        return redirect()->route('login');
    }
}
