@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="section-card p-4 p-lg-5">
                <h1 class="h2 mb-3">Login</h1>
                <p class="text-secondary mb-4">Sign in to track orders, open receipts, and continue checkout securely.</p>

                <form method="POST" action="{{ route('login.attempt') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-12 form-check ms-1">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-campus">Login</button>
                    </div>
                </form>

                <p class="mt-4 mb-0 text-secondary">
                    Need an account? <a href="{{ route('register') }}" class="text-campus fw-semibold">Create one here</a>.
                </p>
            </div>
        </div>
    </div>
@endsection
