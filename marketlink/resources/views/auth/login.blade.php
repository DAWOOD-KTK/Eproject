@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 shadow-sm border-0">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 bg-success bg-opacity-10 text-success rounded-circle mb-2">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Welcome Back</h4>
                    <p class="text-muted small">Sign in to your MarketLink account</p>
                </div>

                <!-- One-click Demo Credentials Bar for Testing/Judges -->
                <div class="alert alert-light border p-2 mb-3 rounded-3">
                    <small class="text-muted fw-bold d-block mb-1 text-center"><i class="bi bi-key-fill text-warning me-1"></i> Quick Test Credentials (1-Click Fill):</small>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="fillCreds('admin@marketlink.com', 'admin123')">Admin</button>
                        <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="fillCreds('farmer@marketlink.com', 'farmer123')">Farmer</button>
                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.75rem;" onclick="fillCreds('customer@marketlink.com', 'customer123')">Customer</button>
                    </div>
                </div>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="loginEmail" class="form-control border-start-0" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label small fw-bold mb-0">Password</label>
                        </div>
                        <div class="input-group mt-1">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="loginPassword" class="form-control border-start-0" required placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Remember my login</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-1">Don't have an account yet?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('register', ['role' => 'customer']) }}" class="small text-success fw-bold text-decoration-none">Register as Customer</a>
                        <span class="text-muted">•</span>
                        <a href="{{ route('register', ['role' => 'farmer']) }}" class="small text-success fw-bold text-decoration-none">Register as Farmer Stall</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCreds(email, pass) {
        document.getElementById('loginEmail').value = email;
        document.getElementById('loginPassword').value = pass;
    }
</script>
@endsection
