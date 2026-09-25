@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}" class="text-success text-decoration-none">My Pre-Orders</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <!-- Order Header -->
    <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="badge {{ $order->status_badge }} rounded-pill px-3 py-1 mb-2">
                    {{ $order->status_label }}
                </span>
                <h3 class="fw-bold mb-1">Pre-Order #{{ $order->order_number }}</h3>
                <small class="text-muted">
                    Placed on {{ $order->created_at->format('M d, Y h:i A') }} • Farmer Stall: <strong>{{ $order->farmer->stall_name ?? $order->farmer->name }}</strong>
                </small>
            </div>
            <div class="text-md-end">
                <span class="text-muted small d-block">Total to Pay at Pickup:</span>
                <span class="fs-3 fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                <small class="text-muted d-block">(Settled in cash/direct upon collection)</small>
            </div>
        </div>

        <!-- Order Tracking Timeline -->
        <div class="row g-2 mt-4 text-center">
            @php
                $statuses = ['placed', 'accepted', 'ready_for_pickup', 'completed'];
                $currentIndex = array_search($order->order_status, $statuses);
                if ($order->order_status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($order->order_status === 'cancelled')
                <div class="col-12">
                    <div class="alert alert-danger mb-0 small">
                        <i class="bi bi-x-circle-fill me-1"></i> This order was <strong>cancelled</strong>.
                        @if($order->cancellation_reason)
                            Reason: <em>{{ $order->cancellation_reason }}</em>
                        @endif
                    </div>
                </div>
            @else
                <div class="col-3">
                    <div class="p-2 rounded-3 {{ $currentIndex >= 0 ? 'bg-success text-white' : 'bg-light text-muted' }} small fw-bold">
                        1. Placed
                    </div>
                </div>
                <div class="col-3">
                    <div class="p-2 rounded-3 {{ $currentIndex >= 1 ? 'bg-success text-white' : 'bg-light text-muted' }} small fw-bold">
                        2. Accepted
                    </div>
                </div>
                <div class="col-3">
                    <div class="p-2 rounded-3 {{ $currentIndex >= 2 ? 'bg-success text-white' : 'bg-light text-muted' }} small fw-bold">
                        3. Ready for Pickup
                    </div>
                </div>
                <div class="col-3">
                    <div class="p-2 rounded-3 {{ $currentIndex >= 3 ? 'bg-success text-white' : 'bg-light text-muted' }} small fw-bold">
                        4. Completed
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Items & Modification -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Reserved Produce Items</h5>
                    @if($order->canBeModifiedOrCancelled())
                        <span class="badge bg-warning text-dark small">
                            <i class="bi bi-pencil me-1"></i> Editable before cutoff
                        </span>
                    @endif
                </div>

                @if($order->canBeModifiedOrCancelled())
                    <!-- Modification Form -->
                    <form action="{{ route('customer.orders.modify', $order->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>Produce Item</th>
                                        <th>Price</th>
                                        <th style="width: 140px;">Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $item->product_name }}</strong>
                                                @if($item->product)
                                                    <div class="text-muted" style="font-size: 0.72rem;">Available: {{ $item->product->stock_quantity }} {{ $item->unit }}</div>
                                                @endif
                                            </td>
                                            <td>${{ number_format($item->price, 2) }} / {{ $item->unit }}</td>
                                            <td>
                                                <input type="number" name="quantities[{{ $item->id }}]" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1">
                                            </td>
                                            <td class="fw-bold text-success">${{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Customer Notes</label>
                            <input type="text" name="customer_notes" class="form-control form-control-sm" value="{{ $order->customer_notes }}" placeholder="Special harvest or packaging requests...">
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <small class="text-muted">Adjust quantities and click update.</small>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                                <i class="bi bi-arrow-repeat me-1"></i> Update Pre-Order
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Readonly View -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th>Produce Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ $item->product_name }}</strong>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }} / {{ $item->unit }}</td>
                                        <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                        <td class="fw-bold text-success">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Cancellation Option -->
            @if($order->canBeModifiedOrCancelled())
                <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                    <h5 class="fw-bold text-danger mb-2"><i class="bi bi-slash-circle me-1"></i> Cancel Pre-Order</h5>
                    <p class="text-muted small mb-3">
                        Need to change plans? You can cancel your order before the cutoff window ({{ $order->cutoff_time ? $order->cutoff_time->format('M d, Y h:i A') : 'Cutoff applies' }}). Items will be restored to the farmer's stock immediately.
                    </p>
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="cancellation_reason" class="form-control form-control-sm" placeholder="Reason for cancellation (optional)">
                            <button type="submit" class="btn btn-danger btn-sm px-3" onclick="return confirm('Are you sure you want to cancel this pre-order?')">
                                Cancel Order
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Review Section for Completed Orders -->
            @if($order->order_status === 'completed')
                <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                    <h5 class="fw-bold text-success mb-2"><i class="bi bi-star-fill text-warning me-1"></i> Rate & Review Your Harvest</h5>
                    <p class="text-muted small mb-3">
                        How was the freshness and pickup experience with {{ $order->farmer->stall_name ?? $order->farmer->name }}? Share your feedback with the community!
                    </p>

                    @foreach($order->items as $item)
                        @php
                            $existingReview = $order->reviews()->where('product_id', $item->product_id)->first();
                        @endphp

                        @if($existingReview)
                            <div class="p-3 bg-light rounded-3 mb-2 small">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-dark">{{ $item->product_name }}</strong>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $existingReview->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </span>
                                </div>
                                <p class="mb-0 text-muted fst-italic mt-1">"{{ $existingReview->comment }}"</p>
                            </div>
                        @else
                            <form action="{{ route('reviews.store') }}" method="POST" class="p-3 border rounded-3 mb-3 bg-light">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-dark small">{{ $item->product_name }}</strong>
                                    <select name="rating" class="form-select form-select-sm w-auto" required>
                                        <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                        <option value="4">⭐⭐⭐⭐ 4</option>
                                        <option value="3">⭐⭐⭐ 3</option>
                                        <option value="2">⭐⭐ 2</option>
                                        <option value="1">⭐ 1</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Write quick review on freshness, taste..." required>
                                    <button type="submit" class="btn btn-success btn-sm">Post</button>
                                </div>
                            </form>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pickup Stall Details & Embedded Map -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Pickup Stall Details</h5>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Pickup Date:</span>
                    <strong class="text-dark fs-6">{{ $order->pickup_date->format('l, F j, Y') }}</strong>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Pickup Window:</span>
                    <strong class="text-success fs-6">{{ $order->pickup_time_slot }}</strong>
                </div>

                <div class="mb-3 small">
                    <span class="text-muted d-block">Farmer Stall:</span>
                    <strong class="text-dark">{{ $order->farmer->stall_name ?? $order->farmer->name }}</strong>
                    <div class="text-muted">{{ $order->farmer->address }}</div>
                    <div class="text-muted">{{ $order->market ? $order->market->market_name : 'Local Market' }}</div>
                </div>

                @if($order->cutoff_time)
                    <div class="mb-3 small p-2 bg-light rounded-3">
                        <span class="text-muted d-block">Order Cutoff Time:</span>
                        <span class="text-danger fw-bold">{{ $order->cutoff_time->format('M d, Y h:i A') }}</span>
                    </div>
                @endif

                @if($order->customer_notes)
                    <div class="mb-3 small p-2 bg-light rounded-3">
                        <span class="text-muted d-block fw-bold">Your Notes:</span>
                        <div class="text-secondary">{{ $order->customer_notes }}</div>
                    </div>
                @endif

                @if($order->farmer_notes)
                    <div class="mb-3 small p-2 bg-success bg-opacity-10 rounded-3">
                        <span class="text-success d-block fw-bold">Farmer Note:</span>
                        <div class="text-dark">{{ $order->farmer_notes }}</div>
                    </div>
                @endif

                <!-- Stall Map Pin -->
                <div class="mt-3">
                    <div class="small fw-bold text-muted mb-2">Stall Map Location:</div>
                    <div id="orderStallMap" style="height: 200px; width: 100%; border-radius: 8px;"></div>
                    <a href="https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $order->farmer->latitude ?? 24.8607 }}%2C{{ $order->farmer->longitude ?? 67.0011 }}" target="_blank" class="btn btn-outline-success btn-sm w-100 rounded-pill mt-2">
                        <i class="bi bi-cursor-fill me-1"></i> Directions to Stall
                    </a>
                </div>
            </div>

            <!-- Quick Re-order -->
            @if($order->order_status === 'completed')
                <div class="card card-custom p-3 border-0 shadow-sm bg-white text-center">
                    <h6 class="fw-bold mb-1">Loved this harvest?</h6>
                    <p class="text-muted small mb-3">Re-order these same fresh items for next market day with 1 click.</p>
                    <form action="{{ route('customer.orders.reorder', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                            <i class="bi bi-arrow-repeat me-1"></i> Quick Re-Order All Items
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const stallMap = L.map('orderStallMap').setView([{{ $order->farmer->latitude ?? 24.8607 }}, {{ $order->farmer->longitude ?? 67.0011 }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(stallMap);

        L.marker([{{ $order->farmer->latitude ?? 24.8607 }}, {{ $order->farmer->longitude ?? 67.0011 }}])
            .addTo(stallMap)
            .bindPopup("<strong>{{ addslashes($order->farmer->stall_name ?? $order->farmer->name) }}</strong><br>{{ addslashes($order->farmer->address) }}")
            .openPopup();
    });
</script>
@endpush
