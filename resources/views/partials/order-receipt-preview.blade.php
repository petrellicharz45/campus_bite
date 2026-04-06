<div class="section-card p-4 receipt-card">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-start mb-4">
        <div>
            <div class="small text-uppercase fw-bold text-campus mb-2">Order receipt</div>
            <h2 class="h4 mb-1">{{ $companySettings->company_name }}</h2>
            <div class="text-secondary">Order {{ $order->order_number }}</div>
        </div>
        <div class="text-md-end">
            <div class="small text-secondary">Issued</div>
            <div class="fw-semibold">{{ $order->placed_at?->format('M d, Y h:i A') }}</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="small text-secondary">Customer</div>
            <div class="fw-semibold">{{ $order->user->name }}</div>
            <div>{{ $order->user->email }}</div>
            <div>{{ $order->phone }}</div>
        </div>
        <div class="col-md-6">
            <div class="small text-secondary">Fulfillment</div>
            <div class="fw-semibold">{{ ucfirst($order->fulfillment_type) }}</div>
            <div>{{ $order->location ?: 'Pickup desk at campus canteen' }}</div>
            <div>{{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ $currencyCode }} {{ number_format((float) $item->unit_price, 2) }}</td>
                        <td class="text-end">{{ $currencyCode }} {{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Subtotal</th>
                    <th class="text-end">{{ $currencyCode }} {{ number_format((float) $order->subtotal, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Delivery fee</th>
                    <th class="text-end">{{ $currencyCode }} {{ number_format((float) $order->delivery_fee, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Grand total</th>
                    <th class="text-end">{{ $currencyCode }} {{ number_format((float) $order->total, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
