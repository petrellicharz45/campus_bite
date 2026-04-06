<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'pageTitle' => 'Manage Orders',
            'orders' => Order::query()->with(['user', 'items'])->latest('placed_at')->paginate(10),
            'statuses' => ['confirmed', 'preparing', 'out-for-delivery', 'ready', 'completed'],
            'paymentStatuses' => ['pending', 'cash-on-delivery', 'paid'],
            'summary' => [
                'total' => Order::query()->count(),
                'pendingPayment' => Order::query()->where('payment_status', 'pending')->count(),
                'active' => Order::query()->whereIn('status', ['confirmed', 'preparing', 'out-for-delivery', 'ready'])->count(),
            ],
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items', 'paymentActivities']);

        return view('admin.orders.show', [
            'pageTitle' => 'Order Details',
            'order' => $order,
            'statuses' => ['confirmed', 'preparing', 'out-for-delivery', 'ready', 'completed'],
            'paymentStatuses' => ['pending', 'cash-on-delivery', 'paid'],
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'preparing', 'out-for-delivery', 'ready', 'completed'])],
            'payment_status' => ['required', Rule::in(['pending', 'cash-on-delivery', 'paid'])],
        ]);

        $order->update($validated);
        $order->paymentActivities()->create([
            'source' => 'admin-panel',
            'type' => 'manual_update',
            'status' => $validated['payment_status'],
            'message' => 'An administrator updated the order or payment status manually.',
            'payload' => $validated,
            'happened_at' => now(),
        ]);

        return redirect()->route('admin.orders.index')->with('status', 'Order status updated successfully.');
    }
}
