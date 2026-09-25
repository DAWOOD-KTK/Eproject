@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4 p-md-5 border-0 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="bg-success text-white rounded-circle fs-3 fw-bold d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Customer Account</span>
                        <small class="text-muted ms-2">Member since {{ $user->created_at->format('M Y') }}</small>
                    </div>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted" style="font-size: 0.72rem;">Email cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Contact Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required placeholder="+1-555-0123">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Preferred Farmers Market</label>
                            <select name="preferred_market_id" class="form-select">
                                <option value="">-- Select Preferred Market --</option>
                                @foreach($markets as $m)
                                    <option value="{{ $m->id }}" {{ $user->preferred_market_id == $m->id ? 'selected' : '' }}>{{ $m->market_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Residential Address</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $user->address) }}</textarea>
                            <small class="text-muted" style="font-size: 0.72rem;">Used for route calculation and pickup notifications</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
