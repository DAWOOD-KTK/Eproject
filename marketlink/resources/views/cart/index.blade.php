@extends('layouts.app')

@section('title', 'Pre-Order Basket')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-basket2-fill text-success me-2"></i> Pre-Order Basket</h2>
            <p class="text-muted mb-0">Items are grouped by farmer stall for market pickup coordination</p>
        </div>
        @if(!empty($groupedCart))
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Empty entire basket?')">
                    <i class="bi bi-trash3 me-1"></i> Clear Basket
                </button>
            </form>
        @endif
    </div>

    @if(empty($groupedCart))
        <div class="card card-custom p-5 border-0 shadow-sm text-center bg-white">
            <div class="p-3 bg-light rounded-circle d-inline-flex mx-auto mb-3 text-muted fs-1">
                <i class="bi bi-basket"></i>
            </div>
            <h4 class="fw-bold mb-2">Your Basket is Empty</h4>
            <p class="text-muted small mb-4">Browse fresh fruits, vegetables, and artisanal goods from local growers.</p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-success rounded-pill px-4 fw-bold">
                    <i class="bi bi-shop me-1"></i> Browse Fresh Produce
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <!-- Grouped Cart Items -->
            <div class="col-lg-8">
                @foreach($groupedCart as $farmerId => $group)
                    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <div>
                                <h5 class="fw-bold mb-0 text-success">
                                    <i class="bi bi-shop me-1"></i> {{ $group['stall_name'] }}
                                </h5>
                                <small class="text-muted">
                                    {{ $group['market_name'] }} • Pickup: {{ $group['pickup_windows'] }} ({{ $group['operating_days'] }})
                                </small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                                Subtotal: ${{ number_format($group['subtotal'], 2) }}
                            </span>
                        </div>

                        <!-- Table of items for this farmer -->
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Produce Item</th>
                                        <th>Price</th>
                                        <th style="width: 140px;">Quantity</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['items'] as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('products.show', $item['id']) }}" class="text-dark fw-bold text-decoration-none">
                                                    {{ $item['name'] }}
                                                </a>
                                                <div class="text-muted" style="font-size: 0.72rem;">Available: {{ $item['stock'] }} {{ $item['unit'] }}</div>
                                            </td>
                                            <td>${{ number_format($item['price'], 2) }} / {{ $item['unit'] }}</td>
                                            <td>
                                                <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center gap-1">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm p-1" title="Update">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold text-success">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('cart.remove') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                                    <button type="submit" class="btn btn-link text-danger p-0" title="Remove">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary Card -->
            <div class="col-lg-4">
                <div class="card card-custom p-4 border-0 shadow-sm bg-white sticky-top" style="top: 85px;">
                    <h5 class="fw-bold mb-3">Pre-Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Produce Items:</span>
                        <span class="fw-bold">{{ count($cart) }} types</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Participating Stalls:</span>
                        <span class="fw-bold">{{ count($groupedCart) }} stalls</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fs-5 fw-bold text-dark">Estimated Total:</span>
                        <span class="fs-4 fw-bold text-success">${{ number_format($totalAmount, 2) }}</span>
                    </div>

                    <div class="alert alert-light border small text-muted mb-4">
                        <i class="bi bi-cash-stack text-warning me-1"></i>
                        <strong>Payment at Pickup:</strong> As per farmers market guidelines, no online payment is required now. Payment is settled in cash/in-person when collecting your pre-order at the market stall.
                    </div>

                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('orders.checkout') }}" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                                Proceed to Select Pickup Slots &rarr;
                            </a>
                        @else
                            <div class="alert alert-warning small mb-0">
                                You are signed in as {{ Auth::user()->role }}. Please switch to a customer account to place pre-orders.
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                            Log In to Complete Pre-Order
                        </a>
                        <small class="text-muted d-block text-center mt-2">New here? <a href="{{ route('register') }}" class="text-success fw-bold">Register as Customer</a></small>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
