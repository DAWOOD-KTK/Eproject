@extends('layouts.app')

@section('title', 'Farm Fresh Just a Click Away')

@section('content')

<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3">
                    <i class="bi bi-patch-check-fill me-1"></i> Theme: eGreen Basket • 100% Direct from Farm
                </span>
                <h1 class="display-4 fw-extrabold text-dark tracking-tight mb-3">
                    Farm Fresh Produce, <span class="text-success">Just a Click Away.</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    Connect directly with local farmers market growers. Browse real-time weekly stock, reserve your harvest for weekend pickup, and enjoy peak seasonal freshness without the guesswork.
                </p>

                <!-- Search & Filter Bar -->
                <div class="card border-0 shadow-lg p-2 rounded-4 bg-white">
                    <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0 text-success"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none ps-0" placeholder="Heirloom tomatoes, honey, apples...">
                            </div>
                        </div>
                        <div class="col-md-4 border-start-md">
                            <select name="category" class="form-select border-0 shadow-none text-muted">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100 py-2 rounded-3 fw-bold">
                                Find Fresh
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Fast Statistics Counter -->
                <div class="row g-3 mt-4 text-dark">
                    <div class="col-auto">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-success fs-4"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $stats['markets'] }} Markets</h6>
                                <small class="text-muted">In your city</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto ms-3 border-start ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-badge-fill text-success fs-4"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $stats['farmers'] }} Active Stalls</h6>
                                <small class="text-muted">Local producers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto ms-3 border-start ps-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-basket-fill text-success fs-4"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $stats['products'] }}+ Items</h6>
                                <small class="text-muted">Fresh weekly</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Visual Card -->
            <div class="col-lg-5">
                <div class="card card-custom border-0 p-4 shadow-lg position-relative overflow-hidden" style="background: white;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success text-white rounded-pill px-3 py-1">Featured Stall</span>
                        <span class="text-warning small fw-bold"><i class="bi bi-star-fill"></i> 4.9 (48 reviews)</span>
                    </div>
                    <h5 class="fw-bold mb-1">Green Valley Organic Farm</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt text-success me-1"></i> Central Downtown Farmers Market (Stall #14)
                    </p>
                    <div class="p-3 bg-light rounded-3 mb-3">
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Operating Days:</span>
                            <span class="fw-bold text-dark">Saturday & Sunday</span>
                        </div>
                        <div class="d-flex justify-content-between small text-secondary mb-1">
                            <span>Pickup Windows:</span>
                            <span class="fw-bold text-dark">08:00 AM - 01:00 PM</span>
                        </div>
                        <div class="d-flex justify-content-between small text-secondary">
                            <span>Payment Settlement:</span>
                            <span class="fw-bold text-success">Cash / In-person at Stall</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('markets.index') }}" class="btn btn-outline-success btn-sm rounded-pill flex-grow-1">Browse Markets</a>
                        <a href="{{ route('products.index') }}" class="btn btn-success btn-sm rounded-pill flex-grow-1">Order For Pickup</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Categories Grid -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small tracking-wide">Produce Selection</span>
                <h3 class="fw-bold mb-0">Browse by Category</h3>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View All Products &rarr;</a>
        </div>

        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-decoration-none">
                        <div class="card card-custom text-center p-3 h-100 border-0 shadow-sm hover-top">
                            <div class="d-inline-flex align-items-center justify-content-center p-3 bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2" style="width: 52px; height: 52px;">
                                <i class="bi {{ $category->icon }} fs-4"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} items</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Fresh Produce -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small tracking-wide">Weekly Harvest</span>
                <h3 class="fw-bold mb-0">Fresh In Season For Market Pickup</h3>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Explore Catalog</a>
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom h-100 p-3 position-relative d-flex flex-column">
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

                        <div class="mb-3 small">
                            <i class="bi bi-shop text-success me-1"></i>
                            <a href="{{ route('farmers.show', $product->farmer_id) }}" class="text-secondary text-decoration-none fw-medium">
                                {{ $product->farmer->stall_name ?? $product->farmer->name }}
                            </a>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $product->farmer->market ? $product->farmer->market->market_name : 'Local Market' }}
                            </div>
                        </div>

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
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="bi bi-plus-lg me-1"></i> Pre-Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-basket text-muted fs-1"></i>
                    <p class="text-muted mt-2">No products currently listed.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Interactive Map Overview -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <span class="text-success fw-bold text-uppercase small tracking-wide">Geolocation & Navigation</span>
                <h3 class="fw-bold mb-1">Explore Local Markets & Farmer Stalls</h3>
                <p class="text-muted mb-0">Powered by OpenStreetMap. Locate nearest markets, identify pickup stalls, and view directions.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('map') }}" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-arrows-fullscreen me-1"></i> Full Interactive Map
                </a>
            </div>
        </div>

        <div class="card card-custom border-0 shadow-sm overflow-hidden p-2">
            <div id="homeMap" style="height: 380px; width: 100%; border-radius: 10px;"></div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <span class="text-success fw-bold text-uppercase small tracking-wide">Simple 3-Step Process</span>
        <h3 class="fw-bold mb-5">How MarketLink Works</h3>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100">
                    <div class="d-inline-flex p-3 bg-success bg-opacity-10 text-success rounded-circle mb-3 fs-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold">1. Browse Available Stock</h5>
                    <p class="text-muted small">
                        Discover nearby farmers markets and view real-time produce listings from local growers along with prices and quantities.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100">
                    <div class="d-inline-flex p-3 bg-success bg-opacity-10 text-success rounded-circle mb-3 fs-3">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <h5 class="fw-bold">2. Reserve for Pickup</h5>
                    <p class="text-muted small">
                        Select your preferred market day and pickup time window. Farmers receive your order and prepare your fresh harvest.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-light h-100">
                    <div class="d-inline-flex p-3 bg-success bg-opacity-10 text-success rounded-circle mb-3 fs-3">
                        <i class="bi bi-bag-heart"></i>
                    </div>
                    <h5 class="fw-bold">3. Pick Up & Settle Cash</h5>
                    <p class="text-muted small">
                        Head to the market stall at your chosen slot. Inspect your fresh basket and settle payment directly in person with the farmer!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
@if($reviews->isNotEmpty())
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-success fw-bold text-uppercase small tracking-wide">Community Feedback</span>
            <h3 class="fw-bold">What Local Shoppers Say</h3>
        </div>

        <div class="row g-4">
            @foreach($reviews as $rev)
                <div class="col-md-6 col-lg-3">
                    <div class="card card-custom h-100 p-3 bg-white">
                        <div class="d-flex text-warning mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }} me-1"></i>
                            @endfor
                        </div>
                        <p class="text-secondary small fst-italic mb-3">
                            "{{ Str::limit($rev->comment, 120) }}"
                        </p>
                        <div class="mt-auto border-top pt-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.88rem;">{{ $rev->customer->name }}</h6>
                            <small class="text-muted">Farmer: {{ $rev->farmer->stall_name ?? $rev->farmer->name }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize OpenStreetMap via Leaflet
        const map = L.map('homeMap').setView([24.8607, 67.0011], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marketIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        @foreach($markets as $m)
            L.marker([{{ $m->latitude }}, {{ $m->longitude }}], { icon: marketIcon })
                .addTo(map)
                .bindPopup("<strong>{{ addslashes($m->market_name) }}</strong><br>{{ addslashes($m->address) }}<br><em>{{ $m->operating_days }} ({{ $m->timings }})</em><br><a href='{{ route('markets.show', $m->id) }}' class='btn btn-success btn-sm text-white mt-1' style='font-size:0.75rem;'>View Market</a>");
        @endforeach
    });
</script>
@endpush
