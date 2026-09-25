@extends('layouts.app')

@section('title', 'My Saved Favorites')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-heart-fill text-danger me-2"></i> Saved Favorites & Restock Alerts</h2>
            <p class="text-muted mb-0">Quick access to your preferred farmers and favorite seasonal produce</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="bi bi-search me-1"></i> Discover More
            </a>
        </div>
    </div>

    <!-- Favorite Farmers Section -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <h4 class="fw-bold mb-3"><i class="bi bi-shop text-success me-2"></i> Preferred Farmers & Stalls</h4>

        <div class="row g-3">
            @forelse($farmers as $farmer)
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0">
                                <a href="{{ route('farmers.show', $farmer->id) }}" class="text-dark text-decoration-none">
                                    {{ $farmer->stall_name ?? $farmer->name }}
                                </a>
                            </h6>
                            <form action="{{ route('customer.favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="Farmer">
                                <input type="hidden" name="id" value="{{ $farmer->id }}">
                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove Favorite">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>
                        </div>

                        <small class="text-muted mb-2">
                            <i class="bi bi-geo-alt text-danger"></i> {{ $farmer->market ? $farmer->market->market_name : 'Local Market' }}
                        </small>

                        <div class="small text-secondary mb-3">
                            <div><strong>Days:</strong> {{ $farmer->operating_days ?? 'Weekend' }}</div>
                            <div><strong>Pickup:</strong> {{ $farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</div>
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-success btn-sm rounded-pill w-100">
                                View Stall Products &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-3 text-muted">
                    <p class="small mb-0">No farmers saved as favorites yet. Click the heart icon on any farmer's stall page!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Favorite Products Section -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <h4 class="fw-bold mb-3"><i class="bi bi-basket-fill text-success me-2"></i> Favorite Seasonal Produce</h4>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="p-3 border rounded-3 bg-light h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                {{ $product->category }}
                            </span>
                            <form action="{{ route('customer.favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="Product">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove Favorite">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </form>
                        </div>

                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h6>

                        <small class="text-muted mb-2">{{ $product->farmer->stall_name ?? $product->farmer->name }}</small>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-success fs-5">${{ number_format($product->price, 2) }}</span>
                            @if($product->stock_quantity > 0 && $product->is_available)
                                <span class="badge bg-success text-white" style="font-size: 0.65rem;">{{ $product->stock_quantity }} {{ $product->unit }} left</span>
                            @else
                                <span class="badge bg-danger text-white" style="font-size: 0.65rem;">Restock Soon</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill w-100" {{ $product->stock_quantity <= 0 || !$product->is_available ? 'disabled' : '' }}>
                                    <i class="bi bi-plus-lg me-1"></i> Pre-Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-3 text-muted">
                    <p class="small mb-0">No produce items saved yet. Save items to receive quick access and restock notifications!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
