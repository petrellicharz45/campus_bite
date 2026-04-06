@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            @include('partials.order-receipt-preview', ['order' => $order])

            <div class="mt-4 d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-campus" onclick="window.print()">Print receipt</button>
                <a href="{{ route('client.orders.show', $order) }}" class="btn btn-campus-outline">Back to order</a>
            </div>
        </div>
    </div>
@endsection
