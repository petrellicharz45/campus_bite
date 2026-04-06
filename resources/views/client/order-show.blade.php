@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="section-card p-4 p-lg-5 mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                    <div>
                        <div class="small text-secondary">{{ $order->placed_at?->format('M d, Y h:i A') }}</div>
                        <h1 class="h2 mb-1">{{ $order->order_number }}</h1>
                        <div class="text-secondary">
                            {{ ucfirst($order->fulfillment_type) }} order • {{ ucwords(str_replace('-', ' ', $order->payment_method)) }}
                        </div>
                    </div>
                    <div class="text-md-end">
                        <span class="status-pill status-{{ $order->status }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                        <div class="mt-2 fw-bold h4 mb-0">{{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</div>
                    </div>
                </div>

                <div class="timeline-steps mb-4">
                    <div class="timeline-step {{ in_array($order->status, ['confirmed', 'preparing', 'out-for-delivery', 'ready', 'completed'], true) ? 'active' : '' }}">
                        Confirmed
                    </div>
                    <div class="timeline-step {{ in_array($order->status, ['preparing', 'out-for-delivery', 'ready', 'completed'], true) ? 'active' : '' }}">
                        Preparing
                    </div>
                    <div class="timeline-step {{ in_array($order->status, ['out-for-delivery', 'ready', 'completed'], true) ? 'active' : '' }}">
                        In progress
                    </div>
                    <div class="timeline-step {{ in_array($order->status, ['ready', 'completed'], true) ? 'active' : '' }}">
                        Ready
                    </div>
                    <div class="timeline-step {{ $order->status === 'completed' ? 'active' : '' }}">
                        Completed
                    </div>
                </div>

                <h2 class="h4 mb-3">Items ordered</h2>
                @foreach ($order->items as $item)
                    <div class="d-flex justify-content-between align-items-center border rounded-4 p-3 mb-3">
                        <div>
                            <div class="fw-semibold">{{ $item->product_name }}</div>
                            <div class="small text-secondary">{{ $item->quantity }} x {{ $currencyCode }} {{ number_format((float) $item->unit_price, 2) }}</div>
                        </div>
                        <div class="fw-semibold">{{ $currencyCode }} {{ number_format((float) $item->line_total, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="section-card p-4 mb-4">
                <h2 class="h5 mb-3">Delivery and payment</h2>
                <div class="mb-3">
                    <div class="small text-secondary">Phone</div>
                    <div>{{ $order->phone }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-secondary">Location</div>
                    <div>{{ $order->location ?: 'Pickup desk at campus canteen' }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-secondary">Payment status</div>
                    <span class="status-pill status-{{ $order->payment_status }}">{{ ucwords(str_replace('-', ' ', $order->payment_status)) }}</span>
                </div>
                @if ($order->payment_method === 'flutterwave' && $order->payment_status !== 'paid')
                    <form method="POST" action="{{ route('client.orders.flutterwave', $order) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-campus w-100">Continue Flutterwave payment</button>
                    </form>
                @endif
                @if ($order->notes)
                    <div>
                        <div class="small text-secondary">Notes</div>
                        <div>{{ $order->notes }}</div>
                    </div>
                @endif
            </div>

            <div class="section-card p-4">
                <h2 class="h5 mb-3">Receipt summary</h2>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $order->subtotal, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery fee</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $order->delivery_fee, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</strong>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('client.orders.receipt', $order) }}" class="btn btn-campus">Open receipt view</a>
                    <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">Back to panel</a>
                </div>
            </div>
        </div>
    </div>
@endsection
