@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-success text-decoration-none">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-success text-decoration-none">{{ $product->category }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Main Product Card -->
    <div class="card card-custom p-4 p-md-5 border-0 shadow-sm bg-white mb-4">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                        {{ $product->category }}
                    </span>

                    @auth
                        @if(Auth::user()->isCustomer())
                            <form action="{{ route('customer.favorites.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="Product">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-sm {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3">
                                    <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }} me-1"></i>
                                    {{ $isFavorite ? 'Saved to Favorites' : 'Save Favorite' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                <h1 class="display-6 fw-bold mb-2">{{ $product->name }}</h1>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="text-warning small">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $product->averageRating() ? '-fill' : '' }}"></i>
                        @endfor
                        <span class="text-dark fw-bold ms-1">{{ $product->averageRating() }} / 5.0</span>
                    </div>
                    <span class="text-muted small">({{ $product->reviewCount() }} customer reviews)</span>
                </div>

                <div class="d-flex align-items-baseline gap-2 mb-3">
                    <span class="display-6 fw-bold text-success">${{ number_format($product->price, 2) }}</span>
                    <span class="text-muted fs-5">/ per {{ $product->unit }}</span>
                </div>

                <p class="text-secondary mb-4 lead" style="font-size: 1.05rem;">
                    {{ $product->description ?? 'Grown and harvested with love by local regional producers. Available for pre-order pickup at the farmers market stall.' }}
                </p>

                <!-- Stock & Pre-Order Form -->
                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold small text-dark">Availability Status:</span>
                        @if($product->stock_quantity > 0 && $product->is_available)
                            <span class="badge bg-success text-white px-3 py-1 rounded-pill">
                                In Stock ({{ $product->stock_quantity }} {{ $product->unit }} remaining)
                            </span>
                        @else
                            <span class="badge bg-danger text-white px-3 py-1 rounded-pill">Sold Out</span>
                        @endif
                    </div>

                    @if($product->stock_quantity > 0 && $product->is_available)
                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div style="width: 110px;">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                            </div>
                            <button type="submit" class="btn btn-success flex-grow-1 rounded-pill fw-bold">
                                <i class="bi bi-bag-plus-fill me-1"></i> Add to Pre-Order Basket
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 rounded-pill fw-bold" disabled>Currently Unavailable</button>
                    @endif
                </div>

                <div class="small text-muted">
                    <i class="bi bi-cash-stack text-warning me-1"></i> <strong>Pickup Settlement:</strong> Payment is settled in person upon collection at the farmer stall.
                </div>
            </div>

            <!-- Farmer Stall Information Box -->
            <div class="col-lg-5">
                <div class="card card-custom p-4 border bg-light h-100">
                    <span class="text-muted small fw-bold text-uppercase mb-2">Producer & Stall Information</span>
                    <h5 class="fw-bold mb-1">
                        <a href="{{ route('farmers.show', $product->farmer_id) }}" class="text-dark text-decoration-none">
                            {{ $product->farmer->stall_name ?? $product->farmer->name }}
                        </a>
                    </h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-person text-success me-1"></i> Owner: {{ $product->farmer->name }}
                    </p>

                    <div class="p-3 bg-white rounded-3 small mb-3 border">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Market:</span>
                            <span class="fw-bold">{{ $product->farmer->market ? $product->farmer->market->market_name : 'Local Market' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Operating Days:</span>
                            <span class="fw-bold">{{ $product->farmer->operating_days ?? 'Weekend' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Pickup Window:</span>
                            <span class="fw-bold">{{ $product->farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Order Cutoff:</span>
                            <span class="fw-bold text-success">{{ $product->farmer->cutoff_hours }}h before pickup</span>
                        </div>
                    </div>

                    <a href="{{ route('farmers.show', $product->farmer_id) }}" class="btn btn-outline-success btn-sm rounded-pill w-100 mt-auto">
                        View Stall & Other Products &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews & Ratings -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-chat-left-text-fill text-success me-2"></i> Product Reviews ({{ $product->reviews->count() }})</h4>
            @auth
                @if(Auth::user()->isCustomer())
                    <button class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="bi bi-pencil-square me-1"></i> Write a Review
                    </button>
                @endif
            @endauth
        </div>

        @forelse($product->reviews as $review)
            <div class="p-3 border rounded-3 bg-light mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="text-dark">{{ $review->customer->name }}</strong>
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-secondary small mb-1">{{ $review->comment }}</p>
                <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $review->created_at->format('M d, Y') }}</small>

                @if($review->farmer_reply)
                    <div class="mt-2 p-2 bg-white rounded border-start border-success border-3 small">
                        <strong class="text-success d-block mb-1"><i class="bi bi-reply-fill"></i> Farmer Response:</strong>
                        <p class="text-muted mb-0">{{ $review->farmer_reply }}</p>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-muted small mb-0">No reviews yet for this product. Be the first to try it and share your feedback!</p>
        @endforelse
    </div>
</div>

<!-- Modal for Submitting Review -->
@auth
    @if(Auth::user()->isCustomer())
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Review {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Star Rating (1 to 5)</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent Freshness</option>
                                <option value="4">⭐⭐⭐⭐ 4 - Very Good</option>
                                <option value="3">⭐⭐⭐ 3 - Good</option>
                                <option value="2">⭐⭐ 2 - Fair</option>
                                <option value="1">⭐ 1 - Poor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Your Comments</label>
                            <textarea name="comment" class="form-control" rows="3" required placeholder="Describe product taste, freshness, quality..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth
@endsection
