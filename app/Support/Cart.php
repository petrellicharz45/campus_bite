<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    private const SESSION_KEY = 'campus_bites.cart';

    public static function items(): Collection
    {
        $cart = collect(session(self::SESSION_KEY, []));

        if ($cart->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->whereIn('id', $cart->keys())
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        return $cart
            ->map(function (array $line, int|string $productId) use ($products): ?array {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                $quantity = max(1, (int) ($line['quantity'] ?? 1));
                $unitPrice = (float) $product->price;

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public static function add(Product $product, int $quantity = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $productId = $product->getKey();
        $existingQuantity = (int) data_get($cart, $productId.'.quantity', 0);

        $cart[$productId] = [
            'quantity' => min($existingQuantity + max(1, $quantity), 20),
        ];

        session([self::SESSION_KEY => $cart]);
    }

    public static function update(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            self::remove($product);
            return;
        }

        $cart = session(self::SESSION_KEY, []);
        $cart[$product->getKey()] = ['quantity' => min($quantity, 20)];
        session([self::SESSION_KEY => $cart]);
    }

    public static function remove(Product $product): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$product->getKey()]);
        session([self::SESSION_KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public static function summary(string $fulfillmentType = 'pickup'): array
    {
        $items = self::items();
        $subtotal = (float) $items->sum('line_total');
        $deliveryFee = $fulfillmentType === 'delivery' && $subtotal > 0 ? 2.50 : 0.0;

        return [
            'items' => $items,
            'item_count' => (int) $items->sum('quantity'),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total' => $subtotal + $deliveryFee,
        ];
    }
}
