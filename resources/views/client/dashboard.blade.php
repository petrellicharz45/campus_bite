@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="section-card p-4">
                <h1 class="h3 mb-3">Client panel</h1>
                <div class="mb-3">
                    <div class="small text-secondary">Student</div>
                    <div class="fw-bold">{{ $user->name }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-secondary">Email</div>
                    <div>{{ $user->email }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-secondary">Phone</div>
                    <div>{{ $user->phone }}</div>
                </div>
                <div>
                    <div class="small text-secondary">Location</div>
                    <div>{{ $user->location ?: 'Not provided' }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Total orders</div>
                        <div class="display-6 fw-bold">{{ $orders->total() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Latest status</div>
                        <div class="h5 mb-0 fw-bold">
                            {{ $orders->count() ? ucwords(str_replace('-', ' ', $orders->first()->status)) : 'No orders yet' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Preferred location</div>
                        <div class="h5 mb-0 fw-bold">{{ $user->location ?: 'Not set' }}</div>
                    </div>
                </div>
            </div>

            <div class="section-card p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 mb-1">Order history</h2>
                        <p class="text-secondary mb-0">Track your past and current canteen orders.</p>
                    </div>
                    <a href="{{ route('menu') }}" class="btn btn-campus">Order again</a>
                </div>

                @forelse ($orders as $order)
                    <div class="border rounded-4 p-4 mb-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                            <div>
                                <div class="small text-secondary">{{ $order->placed_at?->format('M d, Y h:i A') }}</div>
                                <h3 class="h5 mb-1">{{ $order->order_number }}</h3>
                                <div class="text-secondary">{{ ucfirst($order->fulfillment_type) }} • {{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</div>
                            </div>
                            <div class="text-md-end">
                                <span class="status-pill status-{{ $order->status }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                                <div class="fw-bold mt-2">{{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</div>
                            </div>
                        </div>

                        <ul class="mb-0 text-secondary">
                            @foreach ($order->items as $item)
                                <li>{{ $item->quantity }} x {{ $item->product_name }} ({{ $currencyCode }} {{ number_format((float) $item->line_total, 2) }})</li>
                            @endforeach
                        </ul>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('client.orders.show', $order) }}" class="btn btn-sm btn-campus-outline">View details</a>
                            <a href="{{ route('client.orders.receipt', $order) }}" class="btn btn-sm btn-outline-secondary">Receipt</a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info mb-0">No orders yet. Visit the menu and place your first order.</div>
                @endforelse

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
