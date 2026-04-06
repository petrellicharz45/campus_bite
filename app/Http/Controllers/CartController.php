<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'pageTitle' => 'Your Cart',
            'items' => Cart::items(),
            'pickupSummary' => Cart::summary('pickup'),
            'deliverySummary' => Cart::summary('delivery'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()
            ->whereKey($validated['product_id'])
            ->where('is_available', true)
            ->firstOrFail();

        Cart::add($product, (int) ($validated['quantity'] ?? 1));

        return back()->with('status', $product->name.' added to your cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        Cart::update($product, (int) $validated['quantity']);

        return redirect()->route('cart.index')->with('status', 'Your cart has been updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Cart::remove($product);

        return redirect()->route('cart.index')->with('status', $product->name.' removed from your cart.');
    }
}
