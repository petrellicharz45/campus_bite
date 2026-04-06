@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="section-card p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h2 mb-1">Add product</h1>
                        <p class="text-secondary mb-0">Create a new product for the menu.</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-campus-outline">Back to products</a>
                </div>

                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.products._form')

                    <div class="mt-4 d-flex gap-3">
                        <button class="btn btn-campus" type="submit">Save product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
