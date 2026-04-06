<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class FlutterwaveWebhookController extends Controller
{
    public function __invoke(Request $request, FlutterwaveService $flutterwave): Response
    {
        $signature = $request->header('verif-hash');
        $secretHash = config('services.flutterwave.secret_hash');

        if (! $signature || ! $secretHash || $signature !== $secretHash) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->all();
        Log::info('flutterwave_webhook_received', [
            'event' => (string) data_get($payload, 'event', 'webhook.received'),
            'tx_ref' => (string) data_get($payload, 'data.tx_ref', ''),
            'transaction_id' => data_get($payload, 'data.id'),
            'status' => (string) data_get($payload, 'data.status', 'received'),
        ]);

        $orderNumber = (string) data_get($payload, 'data.tx_ref', '');
        $transactionId = data_get($payload, 'data.id');
        $event = (string) data_get($payload, 'event', 'webhook.received');

        if ($orderNumber === '') {
            return new Response('', Response::HTTP_OK);
        }

        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            return new Response('', Response::HTTP_OK);
        }

        $order->paymentActivities()->create([
            'source' => 'flutterwave-webhook',
            'type' => $event,
            'status' => (string) data_get($payload, 'data.status', 'received'),
            'message' => 'Flutterwave webhook received for this order.',
            'payload' => $payload,
            'happened_at' => now(),
        ]);

        if (! $transactionId) {
            return new Response('', Response::HTTP_OK);
        }

        try {
            $transaction = $flutterwave->verifyTransaction((string) $transactionId);
        } catch (Throwable $exception) {
            report($exception);

            $order->paymentActivities()->create([
                'source' => 'flutterwave-webhook',
                'type' => 'verification_failed',
                'status' => 'error',
                'message' => 'Webhook arrived but transaction verification failed.',
                'payload' => ['transaction_id' => $transactionId, 'error' => $exception->getMessage()],
                'happened_at' => now(),
            ]);

            return new Response('', Response::HTTP_OK);
        }

        if (! $flutterwave->transactionMatchesOrder($transaction, $order)) {
            $order->paymentActivities()->create([
                'source' => 'flutterwave-webhook',
                'type' => 'verification_mismatch',
                'status' => 'mismatch',
                'message' => 'Webhook verification did not match the expected order details.',
                'payload' => $transaction,
                'happened_at' => now(),
            ]);

            return new Response('', Response::HTTP_OK);
        }

        $order->update([
            ...$flutterwave->extractPaymentDetails($transaction),
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        $order->paymentActivities()->create([
            'source' => 'flutterwave-webhook',
            'type' => 'charge.completed',
            'status' => 'successful',
            'message' => 'Payment confirmed by Flutterwave webhook verification.',
            'payload' => $transaction,
            'happened_at' => now(),
        ]);

        return new Response('', Response::HTTP_OK);
    }
}
