<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FlutterwaveService
{
    public function isConfigured(): bool
    {
        return filled(config('services.flutterwave.secret_key'));
    }

    public function hasWebhookSecret(): bool
    {
        return filled(config('services.flutterwave.secret_hash'));
    }

    public function initializeCheckout(Order $order): string
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Flutterwave credentials are not configured yet.');
        }

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->acceptJson()
            ->post(rtrim(config('services.flutterwave.base_url'), '/').'/payments', [
                'tx_ref' => $order->order_number,
                'amount' => number_format((float) $order->total, 2, '.', ''),
                'currency' => config('services.flutterwave.currency', 'UGX'),
                'redirect_url' => route('payments.flutterwave.callback'),
                'customer' => [
                    'email' => $order->user->email,
                    'name' => $order->user->name,
                    'phonenumber' => $order->phone,
                ],
                'customizations' => [
                    'title' => 'Campus Bites and Canteen',
                    'description' => 'Payment for order '.$order->order_number,
                ],
            ])
            ->throw()
            ->json();

        $paymentLink = data_get($response, 'data.link');

        if (($response['status'] ?? null) !== 'success' || blank($paymentLink)) {
            throw new RuntimeException($response['message'] ?? 'Flutterwave checkout could not be started.');
        }

        return $paymentLink;
    }

    /**
     * @return array<string, mixed>
     */
    public function verifyTransaction(int|string $transactionId): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Flutterwave credentials are not configured yet.');
        }

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->acceptJson()
            ->get(rtrim(config('services.flutterwave.base_url'), '/').'/transactions/'.$transactionId.'/verify')
            ->throw()
            ->json();

        $data = data_get($response, 'data');

        if (($response['status'] ?? null) !== 'success' || ! is_array($data)) {
            throw new RuntimeException($response['message'] ?? 'Unable to verify Flutterwave payment.');
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function transactionMatchesOrder(array $transaction, Order $order): bool
    {
        return data_get($transaction, 'status') === 'successful'
            && data_get($transaction, 'tx_ref') === $order->order_number
            && data_get($transaction, 'currency') === config('services.flutterwave.currency', 'UGX')
            && (float) data_get($transaction, 'amount', 0) >= (float) $order->total;
    }

    /**
     * @return array<string, mixed>
     */
    public function extractPaymentDetails(array $transaction): array
    {
        return [
            'payment_reference' => (string) data_get($transaction, 'tx_ref', ''),
            'payment_provider_reference' => (string) data_get($transaction, 'flw_ref', data_get($transaction, 'id', '')),
            'payment_channel' => (string) data_get($transaction, 'payment_type', ''),
            'payment_meta' => $transaction,
        ];
    }
}
