<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? $companySettings->company_name }}</title>
    <meta
        name="description"
        content="{{ $companySettings->company_name }} offers student-friendly meals, pickup, delivery, and simple online ordering from {{ $companySettings->support_location }}."
    >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php($cartSummary = \App\Support\Cart::summary())

    <div class="page-shell d-flex flex-column">
        @include('partials.nav')

        <main class="flex-grow-1 py-4 py-lg-5">
            <div class="container">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>

        <footer class="border-top bg-white py-4 mt-auto">
            <div class="container d-flex flex-column flex-lg-row justify-content-between gap-4 small text-secondary">
                <div class="footer-brand">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="brand-mark">
                            @if ($companySettings->logo_url)
                                <img src="{{ $companySettings->logo_url }}" alt="{{ $companySettings->company_name }}" class="brand-logo-image">
                            @else
                                <i class="bi bi-shop-window"></i>
                            @endif
                        </span>
                        @unless ($companySettings->logo_url)
                            <strong class="text-dark">{{ $companySettings->company_name }}</strong>
                        @endunless
                    </div>
                    Student-friendly meals, pickup, delivery, and canteen ordering.

                    @if (! empty($socialLinks))
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            @foreach ($socialLinks as $socialLink)
                                <a
                                    href="{{ $socialLink['url'] }}"
                                    class="social-icon-link footer-social-link"
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-label="{{ $socialLink['label'] }}"
                                    title="{{ $socialLink['label'] }}"
                                >
                                    <i class="bi {{ $socialLink['icon'] }}"></i>
                                    <span>{{ $socialLink['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <div><i class="bi bi-telephone me-2"></i>{{ $companySettings->support_phone }}</div>
                    <div><i class="bi bi-envelope me-2"></i>{{ $companySettings->support_email }}</div>
                    <div><i class="bi bi-geo-alt me-2"></i>{{ $companySettings->support_location }}</div>
                    <div class="policy-hours-text mt-2"><i class="bi bi-clock me-2"></i>{!! nl2br(e($companySettings->operating_hours)) !!}</div>
                </div>

                <div class="footer-links">
                    <div class="fw-semibold text-dark mb-2">Customer care</div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('pages.privacy') }}" class="footer-text-link">Privacy Policy</a>
                        <a href="{{ route('pages.terms') }}" class="footer-text-link">Terms of Service</a>
                        <a href="{{ route('pages.refunds') }}" class="footer-text-link">Refund and Order Policy</a>
                        <a href="{{ route('pages.hours') }}" class="footer-text-link">Operating Hours</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
