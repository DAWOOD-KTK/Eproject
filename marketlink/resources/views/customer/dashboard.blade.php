@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted mb-0">Manage your market pickups, track pre-orders, and explore fresh seasonal harvest</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="bi bi-shop me-1"></i> Order Fresh Produce
            </a>
            <a href="{{ route('customer.profile') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-gear me-1"></i> Profile Settings
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-custom p-3 border-0 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Active Pickups</span>
                        <h3 class="fw-bold text-success mb-0">{{ $stats['active_orders'] }}</h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-custom p-3 border-0 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Completed</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $stats['completed_orders'] }}</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-custom p-3 border-0 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Total Orders</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-custom p-3 border-0 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small d-block">Saved Favorites</span>
                        <h3 class="fw-bold text-danger mb-0">{{ $stats['favorites_count'] }}</h3>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle">
                        <i class="bi bi-heart-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Active Pre-Orders -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calendar2-check-fill text-success me-2"></i> Upcoming Market Pickups</h5>
                    <a href="{{ route('customer.orders.index') }}" class="small text-success fw-bold text-decoration-none">View All Orders &rarr;</a>
                </div>

                @forelse($activeOrders as $order)
                    <div class="p-3 border rounded-3 mb-3 bg-light">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <div>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                                <span class="badge {{ $order->status_badge }} ms-2 rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <span class="fw-bold text-success fs-6">${{ number_format($order->total_amount, 2) }}</span>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center text-muted small">
                            <div>
                                <i class="bi bi-shop text-success me-1"></i> {{ $order->farmer->stall_name ?? $order->farmer->name }}
                                ({{ $order->market ? $order->market->market_name : 'Local Market' }})
                            </div>
                            <div>
                                <i class="bi bi-calendar-event me-1"></i> {{ $order->pickup_date->format('M d, Y') }} ({{ $order->pickup_time_slot }})
                            </div>
                        </div>

                        <div class="mt-2 pt-2 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                View & Track Pickup
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <p class="small mb-2">No active pre-orders currently scheduled.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-success btn-sm rounded-pill px-3">Explore Fresh Produce</a>
                    </div>
                @endforelse
            </div>

            <!-- Recent Past Orders -->
            @if($pastOrders->isNotEmpty())
            <div class="card card-custom p-4 border-0 shadow-sm bg-white">
                <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-secondary me-2"></i> Past Orders & Quick Re-Order</h5>
                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>Order #</th>
                                <th>Stall</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastOrders as $pOrder)
                                <tr>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $pOrder->id) }}" class="text-dark fw-bold text-decoration-none">
                                            {{ $pOrder->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $pOrder->farmer->stall_name ?? $pOrder->farmer->name }}</td>
                                    <td>{{ $pOrder->pickup_date->format('M d, Y') }}</td>
                                    <td class="fw-bold text-success">${{ number_format($pOrder->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        @if($pOrder->order_status === 'completed')
                                            <form action="{{ route('customer.orders.reorder', $pOrder->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;">
                                                    <i class="bi bi-arrow-repeat"></i> Reorder
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- In-app Alerts & Notifications -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bell-fill text-warning me-2"></i> In-App Alerts</h5>
                    <a href="{{ route('customer.notifications') }}" class="small text-success fw-bold text-decoration-none">View All</a>
                </div>

                @forelse($notifications as $notif)
                    <div class="p-2 border-bottom mb-2 small">
                        <strong class="text-dark d-block">{{ $notif->title }}</strong>
                        <p class="text-muted mb-1">{{ Str::limit($notif->message, 80) }}</p>
                        @if($notif->link)
                            <a href="{{ $notif->link }}" class="text-success fw-bold text-decoration-none" style="font-size: 0.75rem;">View &rarr;</a>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">No new alerts at this time.</p>
                @endforelse
            </div>

            <!-- Quick Shortcuts -->
            <div class="card card-custom p-4 border-0 shadow-sm bg-white">
                <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size: 0.8rem;">Quick Shortcuts</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('customer.favorites.index') }}" class="btn btn-light text-start py-2 px-3 rounded-3 small">
                        <i class="bi bi-heart-fill text-danger me-2"></i> View Saved Favorites
                    </a>
                    <a href="{{ route('map') }}" class="btn btn-light text-start py-2 px-3 rounded-3 small">
                        <i class="bi bi-map-fill text-success me-2"></i> Farmers Market Map
                    </a>
                    <a href="{{ route('customer.profile') }}" class="btn btn-light text-start py-2 px-3 rounded-3 small">
                        <i class="bi bi-person-circle text-primary me-2"></i> Update Delivery/Pickup Info
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
