<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\FlutterwaveService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckoutPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_flutterwave_callback_redirects_to_login_and_preserves_intended_order_page(): void
    {
        $user = User::query()->where('email', 'student@campusbites.test')->firstOrFail();

        $order = $user->orders()->create([
            'order_number' => 'CB-TEST-2001',
            'status' => 'confirmed',
            'fulfillment_type' => 'delivery',
            'payment_method' => 'flutterwave',
            'payment_status' => 'pending',
            'phone' => $user->phone,
            'location' => $user->location,
            'notes' => 'Test payment callback.',
            'subtotal' => 24000,
            'delivery_fee' => 2500,
            'total' => 26500,
            'placed_at' => now(),
        ]);

        $transaction = [
            'id' => 778899,
            'status' => 'successful',
            'tx_ref' => $order->order_number,
            'flw_ref' => 'FLW-TEST-2001',
            'payment_type' => 'card',
            'currency' => config('services.flutterwave.currency'),
            'amount' => 26500,
        ];

        $this->mock(FlutterwaveService::class, function (MockInterface $mock) use ($transaction): void {
            $mock->shouldReceive('verifyTransaction')->once()->andReturn($transaction);
            $mock->shouldReceive('transactionMatchesOrder')->once()->andReturnTrue();
            $mock->shouldReceive('extractPaymentDetails')->once()->andReturn([
                'payment_reference' => $transaction['tx_ref'],
                'payment_provider_reference' => $transaction['flw_ref'],
                'payment_channel' => $transaction['payment_type'],
                'payment_meta' => $transaction,
            ]);
        });

        $response = $this->get(route('payments.flutterwave.callback', [
            'status' => 'successful',
            'transaction_id' => $transaction['id'],
            'tx_ref' => $order->order_number,
        ]));

        $response->assertRedirect(route('login'));
        $this->assertSame(route('client.orders.show', $order), session('url.intended'));

        $order->refresh();

        $this->assertSame('paid', $order->payment_status);
        $this->assertNotNull($order->paid_at);
    }
}
