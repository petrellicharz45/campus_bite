@extends('layouts.app')

@section('content')
    <section class="section-card p-4 p-lg-5 mb-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <span class="small text-uppercase fw-bold text-campus d-inline-block mb-2">Customer Information</span>
                <h1 class="display-6 fw-bold mb-3">{{ $title }}</h1>
                <p class="lead text-secondary mb-0">{{ $intro }}</p>
            </div>
            <div class="col-lg-4">
                <div class="metric-card h-100">
                    <h2 class="h5 mb-3">Need help quickly?</h2>
                    <div class="small text-secondary mb-2"><i class="bi bi-telephone me-2"></i>{{ $supportSummary['phone'] }}</div>
                    <div class="small text-secondary mb-2"><i class="bi bi-envelope me-2"></i>{{ $supportSummary['email'] }}</div>
                    <div class="small text-secondary mb-3"><i class="bi bi-geo-alt me-2"></i>{{ $supportSummary['location'] }}</div>
                    <a href="{{ route('cart.index') }}" class="btn btn-campus w-100">Continue to cart</a>
                </div>
            </div>
        </div>
    </section>

    <section class="row g-4 mb-4">
        @foreach ($sections as $section)
            <div class="col-lg-6">
                <div class="section-card p-4 h-100">
                    <h2 class="h4 mb-3">{{ $section['title'] }}</h2>
                    <p class="text-secondary">{{ $section['body'] }}</p>

                    @if (! empty($section['items']))
                        <ul class="policy-list mb-0">
                            @foreach ($section['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </section>

    <section class="section-card p-4 p-lg-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <h2 class="h4 mb-3">Operating hours summary</h2>
                <div class="policy-hours-text">
                    @foreach ($supportSummary['hours'] as $hourLine)
                        <div class="{{ $loop->last ? '' : 'mb-2' }}"><i class="bi bi-clock me-2"></i>{{ $hourLine }}</div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="h4 mb-3">More ways to reach us</h2>
                <p class="text-secondary">
                    For urgent delivery updates, payment questions, or availability checks, contact the canteen team during service hours.
                </p>
                @if (! empty($socialLinks))
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($socialLinks as $socialLink)
                            <a
                                href="{{ $socialLink['url'] }}"
                                class="social-icon-link footer-social-link"
                                target="_blank"
                                rel="noreferrer"
                            >
                                <i class="bi {{ $socialLink['icon'] }}"></i>
                                <span>{{ $socialLink['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-secondary">Phone and email support are available whenever the canteen is open.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
