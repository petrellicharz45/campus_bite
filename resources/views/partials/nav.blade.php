<div class="sticky-top">
    <div class="top-contact-bar border-bottom">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2 py-2">
            <div class="small text-secondary d-flex flex-wrap gap-3">
                <span><i class="bi bi-telephone me-1"></i>{{ $companySettings->support_phone }}</span>
                <span><i class="bi bi-envelope me-1"></i>{{ $companySettings->support_email }}</span>
            </div>

            @if (! empty($socialLinks))
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="small text-secondary me-1">Connect with us</span>
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

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand brand-lockup" href="{{ route('home') }}">
                <span class="brand-mark">
                    @if ($companySettings->logo_url)
                        <img src="{{ $companySettings->logo_url }}" alt="{{ $companySettings->company_name }}" class="brand-logo-image">
                    @else
                        <i class="bi bi-shop-window"></i>
                    @endif
                </span>
                @unless ($companySettings->logo_url)
                    <span class="fw-bold text-campus">{{ $companySettings->company_name }}</span>
                @endunless
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu') ? 'active fw-semibold' : '' }}" href="{{ route('menu') }}">Menu</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('pages.hours') ? 'active fw-semibold' : '' }}" href="{{ route('pages.hours') }}">Hours</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cart.*') ? 'active fw-semibold' : '' }}" href="{{ route('cart.index') }}">Cart</a></li>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 gap-lg-3">
                    <div class="small text-secondary">
                        <i class="bi bi-credit-card-2-front me-1"></i>Cash on Delivery or Flutterwave
                    </div>

                    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-campus-outline">
                        <i class="bi bi-cart3 me-1"></i>{{ $cartSummary['item_count'] }} item(s)
                    </a>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-campus">Admin Panel</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="btn btn-sm btn-campus">Client Panel</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary" type="submit">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-campus">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
