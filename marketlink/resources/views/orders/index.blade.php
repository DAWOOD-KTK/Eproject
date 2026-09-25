@extends('layouts.app')

@section('title', 'My Pre-Orders')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-receipt text-success me-2"></i> My Pre-Orders</h2>
            <p class="text-muted mb-0">Track pickup status, view market stall locations, and modify or cancel upcoming orders</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Order More Produce
            </a>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="card card-custom p-2 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('customer.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-success text-white' : 'btn-light' }} rounded-pill px-3">
                All Orders
            </a>
            <a href="{{ route('customer.orders.index', ['status' => 'placed']) }}" class="btn btn-sm {{ request('status') == 'placed' ? 'btn-warning text-dark' : 'btn-light' }} rounded-pill px-3">
                Placed
            </a>
            <a href="{{ route('customer.orders.index', ['status' => 'accepted']) }}" class="btn btn-sm {{ request('status') == 'accepted' ? 'btn-info text-dark' : 'btn-light' }} rounded-pill px-3">
                Accepted by Farmer
            </a>
            <a href="{{ route('customer.orders.index', ['status' => 'ready_for_pickup']) }}" class="btn btn-sm {{ request('status') == 'ready_for_pickup' ? 'btn-primary text-white' : 'btn-light' }} rounded-pill px-3">
                Ready for Pickup
            </a>
            <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-success text-white' : 'btn-light' }} rounded-pill px-3">
                Completed
            </a>
            <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger text-white' : 'btn-light' }} rounded-pill px-3">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card card-custom border-0 shadow-sm bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Farmer Stall</th>
                        <th>Pickup Date & Slot</th>
                        <th>Items Count</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="text-success text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $order->farmer->stall_name ?? $order->farmer->name }}</strong>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt text-danger"></i> {{ $order->market ? $order->market->market_name : 'Local Market' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $order->pickup_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $order->pickup_time_slot }}</small>
                            </td>
                            <td>{{ $order->items->sum('quantity') }} items</td>
                            <td><strong class="text-success">${{ number_format($order->total_amount, 2) }}</strong> <small class="text-muted">(Cash at pickup)</small></td>
                            <td>
                                <span class="badge {{ $order->status_badge }} rounded-pill px-3 py-1">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                        Details
                                    </a>
                                    @if($order->order_status === 'completed')
                                        <form action="{{ route('customer.orders.reorder', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-light btn-sm rounded-pill text-success" title="Quick Reorder">
                                                <i class="bi bi-arrow-repeat"></i> Reorder
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                <h6>No pre-orders found.</h6>
                                <p class="small mb-3">Browse available market produce and place your first pre-order!</p>
                                <a href="{{ route('products.index') }}" class="btn btn-success btn-sm rounded-pill px-4">Browse Produce</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
