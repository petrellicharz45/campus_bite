@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-3">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Company settings</h1>
                    <p class="text-secondary mb-0">Manage the brand details students see across the storefront, footer, and policy pages.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-4">
                    <div class="section-card p-4 h-100">
                        <div class="small text-uppercase fw-bold text-secondary mb-3">Brand preview</div>

                        <div class="settings-logo-frame mb-3">
                            @if ($settings->logo_url)
                                <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="settings-logo-image">
                            @else
                                <div class="settings-logo-placeholder">
                                    <i class="bi bi-shop-window"></i>
                                </div>
                            @endif
                        </div>

                        <h2 class="h4 mb-1">{{ $settings->company_name }}</h2>
                        <div class="text-secondary mb-3">This brand block appears in the navbar, footer, admin sidebar, and trust pages.</div>

                        <div class="small text-secondary">
                            <div class="mb-2"><i class="bi bi-envelope me-2"></i>{{ $settings->support_email }}</div>
                            <div class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $settings->support_phone }}</div>
                            <div class="mb-2"><i class="bi bi-geo-alt me-2"></i>{{ $settings->support_location }}</div>
                            <div class="policy-hours-text"><i class="bi bi-clock me-2"></i>{!! nl2br(e($settings->operating_hours)) !!}</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="section-card p-4">
                        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PATCH')

                            <div class="col-12">
                                <label for="company_name" class="form-label">Company name</label>
                                <input
                                    id="company_name"
                                    type="text"
                                    name="company_name"
                                    class="form-control"
                                    value="{{ old('company_name', $settings->company_name) }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="support_email" class="form-label">Support email</label>
                                <input
                                    id="support_email"
                                    type="email"
                                    name="support_email"
                                    class="form-control"
                                    value="{{ old('support_email', $settings->support_email) }}"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="support_phone" class="form-label">Support phone</label>
                                <input
                                    id="support_phone"
                                    type="text"
                                    name="support_phone"
                                    class="form-control"
                                    value="{{ old('support_phone', $settings->support_phone) }}"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label for="support_location" class="form-label">Business location</label>
                                <input
                                    id="support_location"
                                    type="text"
                                    name="support_location"
                                    class="form-control"
                                    value="{{ old('support_location', $settings->support_location) }}"
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label for="operating_hours" class="form-label">Operating hours</label>
                                <textarea
                                    id="operating_hours"
                                    name="operating_hours"
                                    class="form-control"
                                    rows="4"
                                    required
                                >{{ old('operating_hours', $settings->operating_hours) }}</textarea>
                                <div class="form-text">Use one line per day or day range.</div>
                            </div>

                            <div class="col-12">
                                <label for="logo" class="form-label">Company logo</label>
                                <input id="logo" type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text">Upload a square or landscape logo image for the navbar and footer.</div>
                            </div>

                            @if ($settings->logo_url)
                                <div class="col-12">
                                    <div class="form-check">
                                        <input
                                            id="remove_logo"
                                            type="checkbox"
                                            name="remove_logo"
                                            value="1"
                                            class="form-check-input"
                                            @checked(old('remove_logo'))
                                        >
                                        <label for="remove_logo" class="form-check-label">Remove current logo</label>
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-campus">Save settings</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back to dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
