<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $orders = Order::query()->with(['user', 'items'])->latest('placed_at')->take(5)->get();
        $recentProducts = Product::query()->with('category')->latest()->take(4)->get();
        $statusCounts = [
            'confirmed' => Order::query()->where('status', 'confirmed')->count(),
            'preparing' => Order::query()->where('status', 'preparing')->count(),
            'ready' => Order::query()->where('status', 'ready')->count(),
            'completed' => Order::query()->where('status', 'completed')->count(),
        ];
        $paymentCounts = [
            'paid' => Order::query()->where('payment_status', 'paid')->count(),
            'pending' => Order::query()->where('payment_status', 'pending')->count(),
            'cash_on_delivery' => Order::query()->where('payment_status', 'cash-on-delivery')->count(),
        ];

        return view('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'metrics' => [
                'products' => Product::query()->count(),
                'orders' => Order::query()->count(),
                'customers' => User::query()->where('role', 'client')->count(),
                'revenue' => (float) Order::query()->sum('total'),
            ],
            'recentOrders' => $orders,
            'recentProducts' => $recentProducts,
            'statusCounts' => $statusCounts,
            'paymentCounts' => $paymentCounts,
        ]);
    }
}
