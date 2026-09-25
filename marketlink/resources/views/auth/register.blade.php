@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card card-custom p-4 p-md-5 shadow-sm border-0">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">Join Market<span class="text-success">Link</span></h3>
                    <p class="text-muted">Choose your role to get started with local farm fresh shopping</p>
                </div>

                <!-- Role Selector Tabs -->
                <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="registerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill {{ $role === 'customer' ? 'active bg-success text-white' : 'text-dark' }}" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customerPane" type="button" role="tab">
                            <i class="bi bi-person me-2"></i> Register as Customer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill {{ $role === 'farmer' ? 'active bg-success text-white' : 'text-dark' }}" id="farmer-tab" data-bs-toggle="tab" data-bs-target="#farmerPane" type="button" role="tab">
                            <i class="bi bi-shop me-2"></i> Register as Farmer / Stall
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="registerTabsContent">
                    <!-- Customer Registration Form -->
                    <div class="tab-pane fade {{ $role === 'customer' ? 'show active' : '' }}" id="customerPane" role="tabpanel">
                        <form action="{{ route('register.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="customer">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Alice Walker">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +1-555-0123">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="alice@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Residential Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required placeholder="Street address, apartment, city...">{{ old('address') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required placeholder="Min 6 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-type password">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 mt-4 rounded-pill fw-bold">
                                <i class="bi bi-person-check me-1"></i> Create Customer Account
                            </button>
                        </form>
                    </div>

                    <!-- Farmer / Vendor Registration Form -->
                    <div class="tab-pane fade {{ $role === 'farmer' ? 'show active' : '' }}" id="farmerPane" role="tabpanel">
                        <form action="{{ route('register.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="farmer">

                            <div class="alert alert-success bg-opacity-10 border-0 rounded-3 mb-4 small">
                                <i class="bi bi-info-circle-fill text-success me-1"></i>
                                Register your farm or artisan market stall. You will be able to list your weekly harvest, publish prices, and receive customer pre-orders for market day pickup!
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Stall / Business Name <span class="text-danger">*</span></label>
                                    <input type="text" name="stall_name" class="form-control" value="{{ old('stall_name') }}" required placeholder="e.g. Green Valley Organic Produce">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Contact Person / Owner <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" required placeholder="e.g. John Miller">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Account / Login Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. John Miller">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Email Address (Login) <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="farmer@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Contact Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +1-555-0199">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Primary Market Attending</label>
                                    <select name="market_id" class="form-select">
                                        <option value="">-- Select Market --</option>
                                        @foreach($markets as $m)
                                            <option value="{{ $m->id }}" {{ old('market_id') == $m->id ? 'selected' : '' }}>{{ $m->market_name }} ({{ $m->city }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Operating Days</label>
                                    <input type="text" name="operating_days" class="form-control" value="{{ old('operating_days', 'Saturday, Sunday') }}" placeholder="e.g. Saturday, Sunday">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Pickup Time Windows</label>
                                    <input type="text" name="pickup_windows" class="form-control" value="{{ old('pickup_windows', '08:00 AM - 01:00 PM') }}" placeholder="e.g. 08:00 AM - 01:00 PM">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Order Cutoff Window (Hours Before Pickup)</label>
                                    <input type="number" name="cutoff_hours" class="form-control" value="{{ old('cutoff_hours', 12) }}" min="1" max="72">
                                    <small class="text-muted" style="font-size: 0.72rem;">Customer modification/cancellation closes this many hours before pickup</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Stall Location / Map Pin (Lat, Long)</label>
                                    <div class="input-group">
                                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', '24.8607') }}" placeholder="Latitude">
                                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', '67.0011') }}" placeholder="Longitude">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Stall / Farm Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="2" required placeholder="Stall number, pavilion location, or farm dispatch point...">{{ old('address') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Farm / Stall Bio & Story</label>
                                    <textarea name="bio" class="form-control" rows="2" placeholder="Tell customers about your farming methods, organic standards, and specialty produce...">{{ old('bio') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required placeholder="Min 6 characters">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-type password">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 mt-4 rounded-pill fw-bold">
                                <i class="bi bi-shop me-1"></i> Register Farmer Stall
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted small mb-0">Already registered? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Sign in here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
