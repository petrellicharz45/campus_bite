@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Manage products</h1>
                    <p class="text-secondary mb-0">Add, edit, or remove menu items shown on the {{ $companySettings->company_name }} storefront.</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-campus">Add product</a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Total products</div>
                        <div class="display-6 fw-bold">{{ $summary['total'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Featured items</div>
                        <div class="display-6 fw-bold">{{ $summary['featured'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="small text-secondary">Available now</div>
                        <div class="display-6 fw-bold">{{ $summary['available'] }}</div>
                    </div>
                </div>
            </div>

            <div class="section-card p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Availability</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img
                                                src="{{ $product->image_url }}"
                                                alt="{{ $product->name }}"
                                                width="56"
                                                height="56"
                                                class="rounded-4 object-fit-cover"
                                            >
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-secondary">{{ $product->short_description }}</div>
                                                @if ($product->is_featured)
                                                    <span class="badge text-bg-warning mt-1">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $currencyCode }} {{ number_format((float) $product->price, 2) }}</td>
                                    <td>
                                        @if ($product->is_available)
                                            <span class="badge text-bg-success">Available</span>
                                        @else
                                            <span class="badge text-bg-secondary">Unavailable</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
