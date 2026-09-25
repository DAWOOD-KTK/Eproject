@extends('layouts.app')

@section('title', $market->market_name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('markets.index') }}" class="text-success text-decoration-none">Markets</a></li>
            <li class="breadcrumb-item active">{{ $market->market_name }}</li>
        </ol>
    </nav>

    <!-- Market Header Card -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 mb-2">
                    {{ $market->city }}
                </span>
                <h2 class="fw-bold mb-2">{{ $market->market_name }}</h2>
                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $market->address }}
                </p>
                <p class="text-secondary small mb-4">
                    {{ $market->description ?? 'Discover farm fresh seasonal produce, vegetables, eggs, and artisan products directly from registered local producers.' }}
                </p>

                <div class="row g-2">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 small">
                            <span class="text-muted d-block mb-1"><i class="bi bi-calendar-event text-success me-1"></i> Operating Days:</span>
                            <strong class="text-dark fs-6">{{ $market->operating_days }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 small">
                            <span class="text-muted d-block mb-1"><i class="bi bi-clock-history text-success me-1"></i> Operating Hours:</span>
                            <strong class="text-dark fs-6">{{ $market->timings }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Embedded Market Map -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-1">
                    <div id="singleMarketMap" style="height: 250px; width: 100%; border-radius: 8px;"></div>
                    <div class="p-2 text-center">
                        <a href="https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $market->latitude }}%2C{{ $market->longitude }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
                            <i class="bi bi-cursor-fill me-1"></i> Open Directions to Market
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attending Farmers & Stalls -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-shop text-success me-2"></i> Stalls & Farmers at this Market ({{ $market->farmers->count() }})
        </h4>
        <span class="text-muted small">Reserve weekly harvest for market day pickup</span>
    </div>

    <div class="row g-4 mb-5">
        @forelse($market->farmers as $farmer)
            <div class="col-lg-6">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <a href="{{ route('farmers.show', $farmer->id) }}" class="text-dark text-decoration-none">
                                    {{ $farmer->stall_name ?? $farmer->name }}
                                </a>
                            </h5>
                            <small class="text-muted"><i class="bi bi-person me-1"></i> {{ $farmer->name }} (Owner)</small>
                        </div>
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                            <i class="bi bi-star-fill text-warning"></i> {{ $farmer->averageRating() }} / 5.0
                        </span>
                    </div>

                    <p class="text-secondary small mb-3">
                        {{ $farmer->bio ?? 'Offering fresh harvest and pasture goods grown sustainably.' }}
                    </p>

                    <div class="p-2 bg-light rounded-3 small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Pickup Window:</span>
                            <span class="fw-bold">{{ $farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Pre-order Cutoff:</span>
                            <span class="fw-bold text-success">{{ $farmer->cutoff_hours }} hours before pickup</span>
                        </div>
                    </div>

                    <!-- Available Products from this Stall Preview -->
                    @if($farmer->farmerProducts->isNotEmpty())
                        <h6 class="small fw-bold text-muted mb-2">Available for Pickup:</h6>
                        <div class="row g-2 mb-3">
                            @foreach($farmer->farmerProducts as $prod)
                                <div class="col-6">
                                    <div class="p-2 border rounded-3 bg-white d-flex justify-content-between align-items-center small">
                                        <div class="overflow-hidden me-1">
                                            <a href="{{ route('products.show', $prod->id) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block">
                                                {{ $prod->name }}
                                            </a>
                                            <span class="text-muted" style="font-size: 0.72rem;">${{ number_format($prod->price, 2) }} / {{ $prod->unit }}</span>
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-success btn-sm p-1 rounded-circle" title="Add to Basket">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-auto pt-2 border-top">
                        <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-success btn-sm rounded-pill w-100">
                            View Stall & Full Catalog &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">No farmer stalls currently registered for this market.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('singleMarketMap').setView([{{ $market->latitude }}, {{ $market->longitude }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([{{ $market->latitude }}, {{ $market->longitude }}])
            .addTo(map)
            .bindPopup("<strong>{{ addslashes($market->market_name) }}</strong><br>{{ addslashes($market->address) }}")
            .openPopup();
    });
</script>
@endpush
