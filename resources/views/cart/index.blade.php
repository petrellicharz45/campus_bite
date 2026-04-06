@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="section-card p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h2 mb-1">Shopping cart</h1>
                        <p class="text-secondary mb-0">Review your order, edit quantities, and choose the best checkout option.</p>
                    </div>
                    <a href="{{ route('menu') }}" class="btn btn-campus-outline">Add more items</a>
                </div>

                @forelse ($items as $item)
                    <div class="row g-3 align-items-center py-3 border-bottom">
                        <div class="col-md-2">
                            <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="img-fluid rounded-4">
                        </div>
                        <div class="col-md-4">
                            <h2 class="h5 mb-1">{{ $item['product']->name }}</h2>
                            <div class="text-secondary small">{{ $item['product']->category->name }}</div>
                            <div class="fw-semibold mt-1">{{ $currencyCode }} {{ number_format((float) $item['unit_price'], 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <form method="POST" action="{{ route('cart.update', $item['product']) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" min="0" max="20" name="quantity" value="{{ $item['quantity'] }}" class="form-control">
                                <button class="btn btn-outline-secondary" type="submit">Update</button>
                            </form>
                        </div>
                        <div class="col-md-2 text-md-end fw-bold">
                            {{ $currencyCode }} {{ number_format((float) $item['line_total'], 2) }}
                        </div>
                        <div class="col-md-1 text-md-end">
                            <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link text-danger p-0" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning mb-0">
                        Your cart is empty. Add meals from the menu to start your order.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="section-card p-4 mb-4">
                <h2 class="h4 mb-3">Order summary</h2>
                <div class="d-flex justify-content-between mb-2">
                    <span>Items</span>
                    <strong>{{ $pickupSummary['item_count'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $pickupSummary['subtotal'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Pickup total</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $pickupSummary['total'], 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Delivery total</span>
                    <strong>{{ $currencyCode }} {{ number_format((float) $deliverySummary['total'], 2) }}</strong>
                </div>

                @if ($pickupSummary['item_count'] > 0)
                    <div class="d-grid">
                        <a href="{{ route('checkout.index') }}" class="btn btn-campus btn-lg">Proceed to checkout</a>
                    </div>
                @endif
            </div>

            <div class="section-card p-4">
                <h2 class="h5 mb-3">Payment and contact</h2>
                <ul class="list-unstyled text-secondary mb-0">
                    <li class="mb-2"><i class="bi bi-credit-card-2-front me-2"></i>Pay with Cash on Delivery or Flutterwave</li>
                    <li class="mb-2"><i class="bi bi-truck me-2"></i>Delivery fee: {{ $currencyCode }} 2.50</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>Support: {{ $companySettings->support_phone }}</li>
                    <li><i class="bi bi-envelope me-2"></i>{{ $companySettings->support_email }}</li>
                </ul>

                @if (! empty($socialLinks))
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach ($socialLinks as $socialLink)
                            <a
                                href="{{ $socialLink['url'] }}"
                                class="social-icon-link"
                                target="_blank"
                                rel="noreferrer"
                                aria-label="{{ $socialLink['label'] }}"
                                title="{{ $socialLink['label'] }}"
                            >
                                <i class="bi {{ $socialLink['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
