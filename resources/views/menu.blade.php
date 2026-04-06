@extends('layouts.app')

@section('content')
    <section class="section-card p-4 p-lg-5 mb-4">
        <div class="row g-4 align-items-end">
            <div class="col-lg-8">
                <h1 class="h2 mb-2">Campus menu</h1>
                <p class="text-secondary mb-0">
                    Browse meals by category, search by name, and add items directly to your shopping cart.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="badge text-bg-light border p-2">Payment methods: Cash on Delivery or Flutterwave</span>
            </div>
        </div>

        <form method="GET" action="{{ route('menu') }}" class="row g-3 mt-2">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search meals or drinks">
            </div>
            <div class="col-md-4">
                <label class="form-label">Category</label>
                <select class="form-select" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button class="btn btn-campus" type="submit">Apply filters</button>
            </div>
        </form>
    </section>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-6 col-xl-4">
                @include('partials.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">No products matched your search. Try a different category or keyword.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
