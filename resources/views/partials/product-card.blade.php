<div class="product-card">
    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
            <div>
                <div class="small text-uppercase fw-bold text-secondary">{{ $product->category->name }}</div>
                <h3 class="h5 mb-1">{{ $product->name }}</h3>
            </div>
            <span class="badge text-bg-light border">{{ number_format($product->prep_time) }} min</span>
        </div>

        <p class="text-secondary mb-3">{{ $product->short_description }}</p>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="fw-bold text-campus">{{ $currencyCode }} {{ number_format((float) $product->price, 2) }}</div>
                @if ($product->calories)
                    <div class="small text-secondary">{{ number_format($product->calories) }} kcal</div>
                @endif
            </div>

            @if ($product->is_featured)
                <span class="badge rounded-pill text-bg-warning">Featured</span>
            @endif
        </div>

        <form method="POST" action="{{ route('cart.store') }}" class="d-flex gap-2">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="number" name="quantity" min="1" max="20" value="1" class="form-control" style="max-width: 88px;">
            <button type="submit" class="btn btn-campus flex-grow-1">
                <i class="bi bi-bag-plus me-1"></i>Add to cart
            </button>
        </form>
    </div>
</div>
