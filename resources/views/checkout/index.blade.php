@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="section-card p-4 p-lg-5">
                <h1 class="h2 mb-3">Checkout</h1>
                <p class="text-secondary mb-4">Complete your order with your preferred fulfillment and payment method.</p>

                <form method="POST" action="{{ route('checkout.store') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label">Fulfillment type</label>
                        <select name="fulfillment_type" class="form-select" data-fulfillment-select>
                            <option value="pickup" @selected(old('fulfillment_type') === 'pickup')>Pickup from canteen desk</option>
                            <option value="delivery" @selected(old('fulfillment_type') === 'delivery')>Delivery to hostel or campus location</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Payment method</label>
                        <select name="payment_method" class="form-select">
                            <option value="cash-on-delivery" @selected(old('payment_method') === 'cash-on-delivery')>Cash on Delivery</option>
                            <option value="flutterwave" @selected(old('payment_method') === 'flutterwave')>Flutterwave</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="col-md-6" data-delivery-location-group>
                        <label class="form-label">Delivery or pickup location</label>
                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            value="{{ old('location', $user->location) }}"
                            placeholder="Hostel, department, or pickup desk"
                            data-delivery-location-input
                        >
                        <div class="form-text" data-delivery-location-help>
                            Add a hostel, lecture block, or meeting point when you choose delivery.
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Order notes</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Optional delivery instructions or order notes">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 d-grid d-md-flex gap-3">
                        <button type="submit" class="btn btn-campus btn-lg">Place order</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary btn-lg">Back to cart</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="section-card p-4 mb-4">
                <h2 class="h4 mb-3">Pricing summary</h2>
                <div class="d-flex justify-content-between mb-2">
                    <span>Pickup subtotal</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $pickupSummary['subtotal'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Delivery fee</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $deliverySummary['delivery_fee'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Pickup total</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $pickupSummary['total'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Delivery total</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $deliverySummary['total'], 2) }}</strong>
                </div>
            </div>

            <div class="section-card p-4">
                <h2 class="h5 mb-3">Visible payment methods</h2>
                <div class="d-grid gap-2">
                    <div class="border rounded-4 p-3">Cash on Delivery for students who prefer to pay when the meal arrives</div>
                    <div class="border rounded-4 p-3">Flutterwave secure checkout for online payment confirmation</div>
                </div>
            </div>
        </div>
    </div>
@endsection
