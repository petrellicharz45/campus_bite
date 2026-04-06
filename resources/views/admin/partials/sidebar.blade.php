<div class="section-card p-4 h-100">
    <div class="small text-uppercase fw-bold text-secondary mb-3">Admin Navigation</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid me-2"></i>Dashboard
    </a>
    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="bi bi-basket me-2"></i>Products
    </a>
    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="bi bi-receipt me-2"></i>Orders
    </a>
    <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <i class="bi bi-gear me-2"></i>Settings
    </a>

    <hr>

    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="sidebar-brand-mark">
            @if ($companySettings->logo_url)
                <img src="{{ $companySettings->logo_url }}" alt="{{ $companySettings->company_name }}" class="sidebar-brand-logo">
            @else
                <i class="bi bi-shop-window"></i>
            @endif
        </div>
        <div>
            <div class="fw-semibold">{{ $companySettings->company_name }}</div>
            <div class="small text-secondary">{{ $companySettings->support_email }}</div>
        </div>
    </div>

    <div class="small text-secondary">
        Update your logo and business contact details here so the storefront stays consistent for students and staff.
    </div>
</div>
