@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Manage orders</h1>
                    <p class="text-secondary mb-0">Update order preparation progress and payment status.</p>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Total orders</div>
                        <div class="display-6 fw-bold">{{ $summary['total'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Active orders</div>
                        <div class="display-6 fw-bold">{{ $summary['active'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Pending payments</div>
                        <div class="display-6 fw-bold">{{ $summary['pendingPayment'] }}</div>
                    </div>
                </div>
            </div>

            <div class="section-card p-4">
                @foreach ($orders as $order)
                    <div class="border rounded-4 p-4 mb-3">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-4">
                                <div class="fw-bold">{{ $order->order_number }}</div>
                                <div class="small text-secondary">{{ $order->user->name }} • {{ $order->user->email }}</div>
                                <div class="small text-secondary">{{ ucfirst($order->fulfillment_type) }} • {{ $order->placed_at?->format('M d, Y h:i A') }}</div>
                                <div class="small text-secondary mt-2">Total: {{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</div>
                                <div class="small text-secondary">Location: {{ $order->location ?: 'Pickup desk' }}</div>
                                <div class="d-flex gap-2 flex-wrap mt-2">
                                    <span class="status-pill status-{{ $order->status }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                                    <span class="status-pill status-{{ $order->payment_status }}">{{ ucwords(str_replace('-', ' ', $order->payment_status)) }}</span>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="small text-secondary mb-2">Items</div>
                                <ul class="mb-0 small">
                                    @foreach ($order->items as $item)
                                        <li>{{ $item->quantity }} x {{ $item->product_name }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="col-lg-3">
                                <div class="d-grid gap-2 mb-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-campus-outline">View details</a>
                                </div>
                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="row g-2">
                                    @csrf
                                    @method('PATCH')

                                    <div class="col-12">
                                        <select name="status" class="form-select form-select-sm">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <select name="payment_status" class="form-select form-select-sm">
                                            @foreach ($paymentStatuses as $paymentStatus)
                                                <option value="{{ $paymentStatus }}" @selected($order->payment_status === $paymentStatus)>{{ ucwords(str_replace('-', ' ', $paymentStatus)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 d-grid">
                                        <button class="btn btn-sm btn-campus" type="submit">Update order</button>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                @endforeach

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
