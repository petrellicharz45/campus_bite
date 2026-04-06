@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Order details</h1>
                    <p class="text-secondary mb-0">{{ $order->order_number }} • {{ $order->user->name }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-campus-outline">Back to orders</a>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-5">
                    <div class="section-card p-4 h-100">
                        <h2 class="h5 mb-3">Order status</h2>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <span class="status-pill status-{{ $order->status }}">{{ ucwords(str_replace('-', ' ', $order->status)) }}</span>
                            <span class="status-pill status-{{ $order->payment_status }}">{{ ucwords(str_replace('-', ' ', $order->payment_status)) }}</span>
                        </div>

                        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="row g-3">
                            @csrf
                            @method('PATCH')

                            <div class="col-12">
                                <label class="form-label">Fulfillment status</label>
                                <select name="status" class="form-select">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Payment status</label>
                                <select name="payment_status" class="form-select">
                                    @foreach ($paymentStatuses as $paymentStatus)
                                        <option value="{{ $paymentStatus }}" @selected($order->payment_status === $paymentStatus)>{{ ucwords(str_replace('-', ' ', $paymentStatus)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 d-grid">
                                <button class="btn btn-campus" type="submit">Save status changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="section-card p-4 h-100">
                        <h2 class="h5 mb-3">Payment activity</h2>

                        <div class="mb-3 small text-secondary">
                            Method: {{ ucwords(str_replace('-', ' ', $order->payment_method)) }}<br>
                            Reference: {{ $order->payment_reference ?: 'Not assigned yet' }}<br>
                            Provider ref: {{ $order->payment_provider_reference ?: 'Not available' }}<br>
                            Channel: {{ $order->payment_channel ?: 'Not available' }}<br>
                            Paid at: {{ $order->paid_at?->format('M d, Y h:i A') ?: 'Awaiting confirmation' }}
                        </div>

                        @forelse ($order->paymentActivities as $activity)
                            <div class="border-start border-3 ps-3 mb-3">
                                <div class="d-flex justify-content-between gap-3">
                                    <div class="fw-semibold">{{ ucwords(str_replace(['-', '_'], ' ', $activity->type)) }}</div>
                                    <div class="small text-secondary">{{ $activity->happened_at?->format('M d, Y h:i A') }}</div>
                                </div>
                                <div class="small text-secondary mb-1">{{ ucwords(str_replace('-', ' ', $activity->source)) }} • {{ ucwords(str_replace('-', ' ', (string) $activity->status)) }}</div>
                                @if ($activity->message)
                                    <div>{{ $activity->message }}</div>
                                @endif
                                @if (! empty($activity->payload))
                                    <details class="mt-2">
                                        <summary class="small text-campus">View payload</summary>
                                        <pre class="small bg-light rounded-4 p-3 mt-2 mb-0 overflow-auto">{{ json_encode($activity->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </details>
                                @endif
                            </div>
                        @empty
                            <div class="text-secondary">No payment activity has been logged for this order yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @include('partials.order-receipt-preview', ['order' => $order])
        </div>
    </div>
@endsection
