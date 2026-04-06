@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Admin dashboard</h1>
                    <p class="text-secondary mb-0">Monitor products, customers, orders, and revenue from one panel.</p>
                </div>
            </div>

            <div class="dashboard-banner p-4 p-lg-5 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-8">
                        <div class="small text-uppercase fw-bold text-campus mb-2">Operations overview</div>
                        <h2 class="h3 mb-2">Keep the canteen menu fresh and order flow responsive.</h2>
                        <p class="text-secondary mb-0">
                            Use this dashboard to manage visible products, update active orders, and keep payment status clear for daily canteen operations.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-campus me-2">Add product</a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-campus-outline">View orders</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="small text-secondary">Products</div>
                        <div class="display-6 fw-bold">{{ $metrics['products'] }}</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="small text-secondary">Orders</div>
                        <div class="display-6 fw-bold">{{ $metrics['orders'] }}</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="small text-secondary">Customers</div>
                        <div class="display-6 fw-bold">{{ $metrics['customers'] }}</div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="metric-card">
                        <div class="small text-secondary">Revenue</div>
                        <div class="h3 fw-bold mb-0">{{ $currencyCode }} {{ number_format((float) $metrics['revenue'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="section-card p-4 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="small text-secondary">Confirmed</div>
                            <div class="h3 mb-0 fw-bold">{{ $statusCounts['confirmed'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="small text-secondary">Preparing</div>
                            <div class="h3 mb-0 fw-bold">{{ $statusCounts['preparing'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="small text-secondary">Ready</div>
                            <div class="h3 mb-0 fw-bold">{{ $statusCounts['ready'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="metric-card">
                            <div class="small text-secondary">Completed</div>
                            <div class="h3 mb-0 fw-bold">{{ $statusCounts['completed'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">Payment summary</h2>
                    <span class="small text-secondary">At-a-glance payment status</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Paid</div>
                            <div class="h3 mb-0 fw-bold">{{ $paymentCounts['paid'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Pending</div>
                            <div class="h3 mb-0 fw-bold">{{ $paymentCounts['pending'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Cash on Delivery</div>
                            <div class="h3 mb-0 fw-bold">{{ $paymentCounts['cash_on_delivery'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="section-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h4 mb-0">Order pipeline</h2>
                            <span class="small text-secondary">Live fulfillment view</span>
                        </div>

                        @php($orderTotal = max(1, array_sum($statusCounts)))

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Confirmed</span>
                                <span>{{ $statusCounts['confirmed'] }}</span>
                            </div>
                            <div class="progress progress-campus">
                                <div class="progress-bar bg-campus" style="width: {{ ($statusCounts['confirmed'] / $orderTotal) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Preparing</span>
                                <span>{{ $statusCounts['preparing'] }}</span>
                            </div>
                            <div class="progress progress-campus">
                                <div class="progress-bar bg-warning" style="width: {{ ($statusCounts['preparing'] / $orderTotal) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Ready</span>
                                <span>{{ $statusCounts['ready'] }}</span>
                            </div>
                            <div class="progress progress-campus">
                                <div class="progress-bar bg-success" style="width: {{ ($statusCounts['ready'] / $orderTotal) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Completed</span>
                                <span>{{ $statusCounts['completed'] }}</span>
                            </div>
                            <div class="progress progress-campus">
                                <div class="progress-bar bg-secondary" style="width: {{ ($statusCounts['completed'] / $orderTotal) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="section-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h4 mb-0">Recent menu items</h2>
                            <a href="{{ route('admin.products.index') }}" class="small text-campus fw-semibold">See all</a>
                        </div>

                        @foreach ($recentProducts as $product)
                            <div class="d-flex align-items-center gap-3 {{ $loop->last ? '' : 'border-bottom pb-3 mb-3' }}">
                                <img
                                    src="{{ $product->image_url }}"
                                    alt="{{ $product->name }}"
                                    width="64"
                                    height="64"
                                    class="rounded-4 object-fit-cover"
                                >
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="small text-secondary">{{ $product->category->name }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">{{ $currencyCode }} {{ number_format((float) $product->price, 2) }}</div>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="small text-campus">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="section-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">Recent orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-campus-outline">Manage all orders</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $order->order_number }}</div>
                                        <div class="small text-secondary">{{ $order->placed_at?->format('M d, Y h:i A') }}</div>
                                    </td>
                                    <td>{{ $order->user->name }}</td>
                                    <td><span class="status-pill status-{{ $order->status }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span></td>
                                    <td><span class="status-pill status-{{ $order->payment_status }}">{{ ucwords(str_replace('-', ' ', $order->payment_status)) }}</span></td>
                                    <td class="fw-semibold">{{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
