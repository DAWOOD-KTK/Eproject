@extends('layouts.app')

@section('title', $farmer->stall_name ?? $farmer->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('farmers.index') }}" class="text-success text-decoration-none">Farmers</a></li>
            <li class="breadcrumb-item active">{{ $farmer->stall_name ?? $farmer->name }}</li>
        </ol>
    </nav>

    <!-- Farmer Profile Card -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                        {{ $farmer->market ? $farmer->market->market_name : 'Local Producer' }}
                    </span>

                    @auth
                        @if(Auth::user()->isCustomer())
                            <form action="{{ route('customer.favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="Farmer">
                                <input type="hidden" name="id" value="{{ $farmer->id }}">
                                <button type="submit" class="btn btn-sm {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3">
                                    <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }} me-1"></i>
                                    {{ $isFavorite ? 'Saved to Favorites' : 'Add to Favorites' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                <h2 class="fw-bold mb-1">{{ $farmer->stall_name ?? $farmer->name }}</h2>
                <div class="text-muted small mb-3">
                    <i class="bi bi-person-fill text-success me-1"></i> Contact Person: {{ $farmer->contact_person ?? $farmer->name }}
                    | <i class="bi bi-star-fill text-warning me-1"></i> <strong>{{ $farmer->averageRating() }} / 5.0</strong> ({{ $farmer->farmerReviews->count() }} reviews)
                </div>

                <p class="text-secondary small mb-4">
                    {{ $farmer->bio ?? 'Family run local producer committed to providing clean, chemical-free and freshly harvested seasonal produce to our local community.' }}
                </p>

                <div class="row g-2">
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3 small">
                            <span class="text-muted d-block mb-1"><i class="bi bi-calendar-check text-success me-1"></i> Operating Days:</span>
                            <strong class="text-dark">{{ $farmer->operating_days ?? 'Saturday, Sunday' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3 small">
                            <span class="text-muted d-block mb-1"><i class="bi bi-clock-history text-success me-1"></i> Pickup Slots:</span>
                            <strong class="text-dark">{{ $farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</strong>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3 small">
                            <span class="text-muted d-block mb-1"><i class="bi bi-hourglass-split text-warning me-1"></i> Order Cutoff:</span>
                            <strong class="text-success">{{ $farmer->cutoff_hours }}h before pickup</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stall Map Location -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-1">
                    <div class="p-2 small fw-bold text-muted">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Stall Location: {{ $farmer->address }}
                    </div>
                    <div id="stallMap" style="height: 220px; width: 100%; border-radius: 8px;"></div>
                    <div class="p-2 text-center">
                        <a href="https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $farmer->latitude ?? 24.8607 }}%2C{{ $farmer->longitude ?? 67.0011 }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
                            <i class="bi bi-cursor-fill me-1"></i> Get Route Directions to Stall
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Harvest Catalog -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-basket-fill text-success me-2"></i> Weekly Stock & Produce Catalog ({{ $farmer->farmerProducts->count() }})
        </h4>
        <span class="text-muted small">Reserve online for market pickup • Cash paid in person</span>
    </div>

    <div class="row g-4 mb-5">
        @forelse($farmer->farmerProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card card-custom h-100 p-3 bg-white d-flex flex-column">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 align-self-start mb-2" style="font-size: 0.72rem;">
                        {{ $product->category }}
                    </span>

                    <h5 class="fw-bold mb-1">
                        <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                            {{ $product->name }}
                        </a>
                    </h5>

                    <p class="text-muted small mb-2 text-truncate" title="{{ $product->description }}">
                        {{ $product->description }}
                    </p>

                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-5 fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                            <small class="text-muted">/ {{ $product->unit }}</small>
                            <div class="text-muted" style="font-size: 0.7rem;">Stock: {{ $product->stock_quantity }} {{ $product->unit }}</div>
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-plus-lg me-1"></i> Pre-Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">This farmer has not listed any products for this week yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Customer Reviews Section -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <h4 class="fw-bold mb-3"><i class="bi bi-chat-heart-fill text-danger me-2"></i> Customer Reviews & Ratings</h4>

        @forelse($farmer->farmerReviews as $review)
            <div class="p-3 border rounded-3 bg-light mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <strong class="text-dark">{{ $review->customer->name }}</strong>
                        @if($review->product)
                            <span class="text-muted small">for <a href="{{ route('products.show', $review->product->id) }}" class="text-success text-decoration-none">{{ $review->product->name }}</a></span>
                        @endif
                    </div>
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                </div>

                <p class="text-secondary small mb-2">{{ $review->comment }}</p>
                <small class="text-muted d-block" style="font-size: 0.72rem;">Reviewed on {{ $review->created_at->format('M d, Y') }}</small>

                <!-- Farmer's Official Reply -->
                @if($review->farmer_reply)
                    <div class="mt-2 p-2 bg-white rounded border-start border-success border-3 small">
                        <strong class="text-success d-block mb-1"><i class="bi bi-reply-fill"></i> Farmer Response:</strong>
                        <p class="text-muted mb-0">{{ $review->farmer_reply }}</p>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-muted small mb-0">No customer reviews yet. Be the first to order and review this farmer stall!</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const stallMap = L.map('stallMap').setView([{{ $farmer->latitude ?? 24.8607 }}, {{ $farmer->longitude ?? 67.0011 }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(stallMap);

        L.marker([{{ $farmer->latitude ?? 24.8607 }}, {{ $farmer->longitude ?? 67.0011 }}])
            .addTo(stallMap)
            .bindPopup("<strong>{{ addslashes($farmer->stall_name ?? $farmer->name) }}</strong><br>{{ addslashes($farmer->address) }}<br>Pickup: {{ $farmer->pickup_windows }}")
            .openPopup();
    });
</script>
@endpush
