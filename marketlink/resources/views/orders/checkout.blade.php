@extends('layouts.app')

@section('title', 'Select Pickup Slots & Confirm Pre-Order')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-success text-decoration-none">Basket</a></li>
            <li class="breadcrumb-item active">Pickup Scheduling</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-calendar2-check text-success me-2"></i> Schedule Market Pickup</h2>
        <p class="text-muted mb-0">Select your pickup date and time window for each farmer stall below.</p>
    </div>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Pickup Schedule per Farmer -->
            <div class="col-lg-8">
                @foreach($groupedCart as $farmerId => $group)
                    @php $farmer = $group['farmer']; @endphp
                    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <div>
                                <h5 class="fw-bold text-success mb-0">
                                    <i class="bi bi-shop me-1"></i> {{ $farmer->stall_name ?? $farmer->name }}
                                </h5>
                                <small class="text-muted">
                                    {{ $farmer->market ? $farmer->market->market_name : 'Local Market' }}
                                    • Stall: {{ $farmer->address }}
                                </small>
                            </div>
                            <span class="badge bg-light text-dark border">
                                Order Cutoff: {{ $farmer->cutoff_hours }}h prior
                            </span>
                        </div>

                        <!-- Produce Summary in this Stall -->
                        <div class="p-2 bg-light rounded-3 mb-3 small">
                            <span class="fw-bold text-dark d-block mb-1">Items in this stall pre-order:</span>
                            <ul class="mb-0 ps-3 text-secondary">
                                @foreach($group['items'] as $item)
                                    <li>{{ $item['quantity'] }} {{ $item['unit'] }} × {{ $item['name'] }} (${{ number_format($item['price'] * $item['quantity'], 2) }})</li>
                                @endforeach
                            </ul>
                            <div class="text-end fw-bold text-success mt-1">Stall Total: ${{ number_format($group['subtotal'], 2) }}</div>
                        </div>

                        <!-- Pickup Scheduling Controls -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pickup Date <span class="text-danger">*</span></label>
                                <input type="date" name="farmer_orders[{{ $farmerId }}][pickup_date]" class="form-control" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+2 days')) }}" required>
                                <small class="text-muted" style="font-size: 0.72rem;">Farmer Operating Days: {{ $farmer->operating_days ?? 'Weekend' }}</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Available Pickup Window <span class="text-danger">*</span></label>
                                <select name="farmer_orders[{{ $farmerId }}][pickup_time_slot]" class="form-select" required>
                                    <option value="08:00 AM - 09:30 AM">08:00 AM - 09:30 AM (Morning Early)</option>
                                    <option value="09:30 AM - 11:00 AM" selected>09:30 AM - 11:00 AM (Mid-Morning)</option>
                                    <option value="11:00 AM - 12:30 PM">11:00 AM - 12:30 PM (Noon)</option>
                                    <option value="12:30 PM - 02:00 PM">12:30 PM - 02:00 PM (Afternoon)</option>
                                </select>
                                <small class="text-muted" style="font-size: 0.72rem;">General Window: {{ $farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Special Pickup Instructions / Notes (Optional)</label>
                                <input type="text" name="farmer_orders[{{ $farmerId }}][customer_notes]" class="form-control" placeholder="e.g. Please pick ripe produce, slice bread, or note specific arrival time...">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Customer & Pickup Info Confirmation -->
            <div class="col-lg-4">
                <div class="card card-custom p-4 border-0 shadow-sm bg-white sticky-top" style="top: 85px;">
                    <h5 class="fw-bold mb-3">Customer & Pickup Details</h5>

                    <div class="p-3 bg-light rounded-3 small mb-3">
                        <div class="mb-1"><span class="text-muted">Customer:</span> <strong class="text-dark">{{ $customer->name }}</strong></div>
                        <div class="mb-1"><span class="text-muted">Contact:</span> <strong class="text-dark">{{ $customer->phone ?? 'Not provided' }}</strong></div>
                        <div><span class="text-muted">Email:</span> <strong class="text-dark">{{ $customer->email }}</strong></div>
                    </div>

                    <div class="alert alert-success bg-opacity-10 border-success border-opacity-25 rounded-3 small mb-4">
                        <h6 class="fw-bold text-success mb-1"><i class="bi bi-shield-check me-1"></i> Cash at Pickup Guaranteed</h6>
                        <p class="mb-0 text-secondary">
                            Payment is settled upon collecting your freshly harvested produce at the farmer stall. No advance digital payment required!
                        </p>
                    </div>

                    <div class="alert alert-warning bg-opacity-10 border-warning border-opacity-25 rounded-3 small mb-4">
                        <i class="bi bi-clock-history me-1 text-warning"></i>
                        <strong>Cutoff Notice:</strong> You can modify quantities or cancel your order anytime until the farmer's cutoff window.
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold fs-6">
                        <i class="bi bi-check-circle-fill me-1"></i> Confirm & Place Pre-Order
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-link text-muted small w-100 text-center mt-2 text-decoration-none">
                        &larr; Back to Pre-Order Basket
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
