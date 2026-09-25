@extends('layouts.farmer')

@section('title', 'Manage Pre-Orders')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-clipboard-check text-success me-2"></i> Manage Incoming Pre-Orders</h2>
            <p class="text-muted mb-0">Accept incoming customer reservations, pack harvests, and notify customers when ready</p>
        </div>
        <div>
            <span class="badge bg-light text-dark border p-2">
                <i class="bi bi-clock-history text-success me-1"></i> Cutoff Window: <strong>{{ $farmer->cutoff_hours }}h</strong> before pickup
            </span>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card p-3 border-0 shadow-sm rounded-3 bg-white mb-4">
        <form action="{{ route('farmer.orders') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="status" class="form-select">
                    <option value="">All Order Statuses</option>
                    <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed (New Incoming)</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="ready_for_pickup" {{ request('status') == 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed Pickups</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled / Declined</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Filter by pickup date">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1 rounded-pill fw-bold">Filter</button>
                @if(request()->hasAny(['status', 'date']))
                    <a href="{{ route('farmer.orders') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Pre-Orders Cards / Table -->
    <div class="row g-4">
        @forelse($orders as $order)
            <div class="col-lg-6">
                <div class="card p-4 border-0 shadow-sm rounded-3 bg-white h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge {{ $order->status_badge }} rounded-pill px-3 py-1 mb-1">
                                {{ $order->status_label }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">#{{ $order->order_number }}</h5>
                        </div>
                        <div class="text-end">
                            <span class="fs-4 fw-bold text-success">${{ number_format($order->total_amount, 2) }}</span>
                            <small class="text-muted d-block">(Cash at pickup)</small>
                        </div>
                    </div>

                    <!-- Customer & Pickup Slot Info -->
                    <div class="p-3 bg-light rounded-3 my-2 small">
                        <div class="row g-1">
                            <div class="col-sm-6">
                                <span class="text-muted">Customer:</span>
                                <strong class="text-dark d-block">{{ $order->customer->name }}</strong>
                                <span class="text-muted">{{ $order->customer->phone }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted">Pickup Window:</span>
                                <strong class="text-dark d-block">{{ $order->pickup_date->format('l, M d, Y') }}</strong>
                                <span class="text-success fw-bold">{{ $order->pickup_time_slot }}</span>
                            </div>
                        </div>

                        @if($order->customer_notes)
                            <div class="mt-2 pt-2 border-top">
                                <span class="text-muted">Customer Request:</span>
                                <span class="text-dark fst-italic">"{{ $order->customer_notes }}"</span>
                            </div>
                        @endif
                    </div>

                    <!-- Items List -->
                    <div class="mb-3">
                        <span class="fw-bold small text-muted text-uppercase">Items to Pack:</span>
                        <ul class="list-group list-group-flush small mt-1">
                            @foreach($order->items as $item)
                                <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                                    <span><strong>{{ $item->quantity }} {{ $item->unit }}</strong> × {{ $item->product_name }}</span>
                                    <span class="text-secondary">${{ number_format($item->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Status Update Actions Form -->
                    <div class="mt-auto pt-3 border-top">
                        <form action="{{ route('farmer.orders.status', $order->id) }}" method="POST">
                            @csrf
                            <div class="row g-2">
                                <div class="col-md-7">
                                    <select name="order_status" class="form-select form-select-sm">
                                        <option value="accepted" {{ $order->order_status == 'accepted' ? 'selected' : '' }}>Accept Order</option>
                                        <option value="ready_for_pickup" {{ $order->order_status == 'ready_for_pickup' ? 'selected' : '' }}>Mark Ready for Pickup</option>
                                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Mark Completed (Paid & Collected)</option>
                                        <option value="declined">Decline / Cancel (Out of Stock)</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">
                                        Update Status
                                    </button>
                                </div>
                                <div class="col-12 mt-1">
                                    <input type="text" name="farmer_notes" class="form-control form-control-sm" value="{{ $order->farmer_notes }}" placeholder="Note for customer (e.g. packed in crate #3)...">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <h6>No pre-orders matched your filters.</h6>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
