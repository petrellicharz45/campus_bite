@extends('layouts.app')

@section('content')
    @php($heroFoodImage = $featuredProducts->first()?->image_url ?? 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80')
    @php($socialLabels = collect($socialLinks)->pluck('label')->reject(fn ($label) => $label === 'WhatsApp')->values())

    <section class="hero-card p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="brand-badge bg-campus mb-3">
                    <i class="bi bi-stars"></i>Built for student convenience
                </span>
                <h1 class="display-5 fw-bold mb-3">{{ $companySettings->company_name }} makes campus food ordering fast, clear, and reliable.</h1>
                <p class="lead text-secondary mb-4">
                    Order affordable meals and snacks for pickup or delivery, see payment methods before checkout,
                    and track your orders from your client panel.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('menu') }}" class="btn btn-campus btn-lg">Browse Menu</a>
                    <a href="{{ route('cart.index') }}" class="btn btn-campus-outline btn-lg">View Cart</a>
                </div>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Delivery fee</div>
                            <div class="h4 mb-0">{{ $currencyCode }} 2.50</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Payment methods</div>
                            <div class="h6 mb-0">Cash on Delivery, Flutterwave</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="metric-card">
                            <div class="small text-secondary">Service area</div>
                            <div class="h6 mb-0">{{ $companySettings->support_location }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="d-grid gap-3">
                    <div class="hero-food-panel">
                        <img src="{{ $heroFoodImage }}" alt="Freshly prepared campus meals" class="hero-food-image">
                        <div class="hero-food-overlay">
                            <span class="brand-badge bg-campus mb-3">
                                <i class="bi bi-camera-fill"></i>Fresh from the canteen
                            </span>
                            <h2 class="h3 text-white mb-2">Hot meals, snacks, and drinks ready between classes.</h2>
                            <p class="mb-0 text-white-50">
                                Order from the menu, pay your way, and pick up or receive delivery at your preferred campus spot.
                            </p>
                        </div>
                    </div>

                    <div class="section-card p-4 bg-white">
                        <h2 class="h4 mb-3">Quick search</h2>
                        <form method="GET" action="{{ route('home') }}" class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Search food or drinks</label>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    class="form-control"
                                    placeholder="Jollof, coffee, shawarma..."
                                >
                            </div>
                            <div class="col-12">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">All categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 d-grid">
                                <button class="btn btn-campus" type="submit">Filter menu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="promo-strip p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h2 class="h3 mb-2">This week on campus</h2>
                    <p class="text-secondary mb-0">
                        Highlight student favourites, limited lunch bundles, and new canteen specials so returning customers always see what is fresh this week.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('register') }}" class="btn btn-campus">Join and order now</a>
                </div>
            </div>
        </div>

    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-1">Featured picks</h2>
                <p class="text-secondary mb-0">Popular items chosen for speed, value, and campus appeal.</p>
            </div>
            <a href="{{ route('menu') }}" class="btn btn-campus-outline">See full menu</a>
        </div>

        <div class="row g-4">
            @foreach ($featuredProducts as $product)
                <div class="col-md-6 col-xl-3">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </section>

    @if ($youtubeEmbedUrl)
        <section class="mb-5">
            <div class="section-card p-4 p-lg-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <span class="small text-uppercase fw-bold text-campus d-inline-block mb-2">Food stories</span>
                        <h2 class="h3 mb-3">Watch what is cooking at {{ $companySettings->company_name }}</h2>
                        <p class="text-secondary mb-3">
                            Share food preparation clips, student meal highlights, or canteen promos through YouTube to make the brand feel more active and engaging.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($socialLinks as $socialLink)
                                @if ($socialLink['label'] === 'YouTube' || $socialLink['label'] === 'WhatsApp')
                                    <a
                                        href="{{ $socialLink['url'] }}"
                                        class="btn btn-campus-outline"
                                        target="_blank"
                                        rel="noreferrer"
                                    >
                                        <i class="bi {{ $socialLink['icon'] }} me-2"></i>{{ $socialLink['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="video-embed-shell">
                            <iframe
                                src="{{ $youtubeEmbedUrl }}"
                                title="Campus food video"
                                loading="lazy"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="mb-5">
        <div class="section-card p-4 p-lg-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h2 class="h3 mb-3">Why students choose {{ $companySettings->company_name }}</h2>
                    <p class="text-secondary mb-0">
                        Everything is arranged to help students order quickly, pay easily, and get clear updates
                        from menu selection to final delivery or pickup.
                    </p>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h3 class="h6">Simple payment choices</h3>
                                <p class="text-secondary mb-0">Students can pay with cash on delivery or complete checkout online with Flutterwave.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h3 class="h6">Easy order review</h3>
                                <p class="text-secondary mb-0">The cart makes it easy to adjust quantities, remove items, and compare pickup and delivery totals.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h3 class="h6">Campus-focused service</h3>
                                <p class="text-secondary mb-0">Meals, pricing, and delivery locations are designed around daily campus routines and student budgets.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric-card">
                                <h3 class="h6">Clear order tracking</h3>
                                <p class="text-secondary mb-0">Clients can view order details, open receipts, and follow payment and delivery progress from one panel.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="small text-uppercase fw-bold text-campus mb-2">Student feedback</div>
                    <p class="mb-2">Students get a simple ordering flow, clear payment choices, and quick access to receipts and order updates.</p>
                    <div class="small text-secondary">Designed for fast campus ordering routines</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="small text-uppercase fw-bold text-campus mb-2">Contact visibility</div>
                    <p class="mb-2">Call, email, or message the canteen team quickly. Contact details stay visible in the top bar, footer, and checkout flow.</p>
                    <div class="small text-secondary">Support desk ready during class hours</div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="small text-uppercase fw-bold text-campus mb-2">Social engagement</div>
                    <p class="mb-2">Menu updates, promos, and quick support links stay connected through the store's active social channels and WhatsApp contact.</p>
                    <div class="small text-secondary">
                        {{ $socialLabels->isNotEmpty() ? $socialLabels->join(', ') : 'Social links ready to connect when added' }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-1">Menu preview</h2>
                <p class="text-secondary mb-0">A student-friendly spread of breakfast, lunch, snacks, and drinks.</p>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-md-6 col-xl-4">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </section>
@endsection
