@extends('layouts.app')

@section('title', 'Weekly Fresh Products')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Weekly Market Produce</h2>
            <p class="text-muted mb-0">Browse fresh fruits, vegetables, dairy, and artisanal goods from local growers</p>
        </div>
        <div>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                {{ $products->total() }} Products Listed
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card card-custom p-3 border-0 shadow-sm bg-white sticky-top" style="top: 85px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-funnel-fill text-success me-1"></i> Filter Produce</h5>
                    @if(request()->hasAny(['search', 'category', 'market_id', 'farmer_id', 'day', 'min_price', 'max_price', 'in_stock']))
                        <a href="{{ route('products.index') }}" class="small text-danger text-decoration-none fw-bold">Reset</a>
                    @endif
                </div>

                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Search Input -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Keyword</label>
                        <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Apples, tomatoes...">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Category</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Market Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Farmers Market</label>
                        <select name="market_id" class="form-select form-select-sm">
                            <option value="">All Markets</option>
                            @foreach($markets as $m)
                                <option value="{{ $m->id }}" {{ request('market_id') == $m->id ? 'selected' : '' }}>{{ $m->market_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Farmer Stall Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Farmer Stall</label>
                        <select name="farmer_id" class="form-select form-select-sm">
                            <option value="">All Farmers</option>
                            @foreach($farmers as $f)
                                <option value="{{ $f->id }}" {{ request('farmer_id') == $f->id ? 'selected' : '' }}>{{ $f->stall_name ?? $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Operating Day Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Pickup Market Day</label>
                        <select name="day" class="form-select form-select-sm">
                            <option value="">Any Day</option>
                            <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                            <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="Friday" {{ request('day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Price Range ($)</label>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" name="min_price" step="0.5" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" step="0.5" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- In Stock Only -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="in_stock" value="1" class="form-check-input" id="inStockCheck" {{ request('in_stock') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="inStockCheck">In-Stock Only</label>
                    </div>

                    <!-- Sort -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Sort By</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Harvest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Catalog Grid -->
        <div class="col-lg-9">
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-lg-4">
                        <div class="card card-custom h-100 p-3 bg-white d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    {{ $product->category }}
                                </span>
                                @if($product->stock_quantity > 0 && $product->is_available)
                                    <span class="badge bg-light text-success border" style="font-size: 0.65rem;">
                                        <i class="bi bi-check2-circle me-1"></i> In Stock
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white" style="font-size: 0.65rem;">Sold Out</span>
                                @endif
                            </div>

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
                                    • {{ $product->farmer->operating_days ?? 'Weekend' }}
                                </div>
                            </div>

                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fs-5 fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                                    <small class="text-muted">/ {{ $product->unit }}</small>
                                    <div class="text-muted" style="font-size: 0.7rem;">Available: {{ $product->stock_quantity }} {{ $product->unit }}</div>
                                </div>
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3" {{ $product->stock_quantity <= 0 || !$product->is_available ? 'disabled' : '' }}>
                                        <i class="bi bi-plus-lg me-1"></i> Pre-Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h5 class="fw-bold mt-2">No products matched your criteria</h5>
                        <p class="text-muted small">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View All Products</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
