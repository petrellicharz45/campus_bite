<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ClientPanelController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest('placed_at')
            ->paginate(6);

        return view('client.dashboard', [
            'pageTitle' => 'Client Panel',
            'orders' => $orders,
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        $order->load(['items', 'user']);

        abort_unless($order->user_id === $request->user()->id, Response::HTTP_FORBIDDEN);

        return view('client.order-show', [
            'pageTitle' => 'Order Details',
            'order' => $order,
        ]);
    }

    public function receipt(Request $request, Order $order): View
    {
        $order->load(['items', 'user']);

        abort_unless($order->user_id === $request->user()->id, Response::HTTP_FORBIDDEN);

        return view('client.receipt', [
            'pageTitle' => 'Order Receipt',
            'order' => $order,
        ]);
    }
}
