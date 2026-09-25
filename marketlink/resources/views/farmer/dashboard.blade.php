@extends('layouts.farmer')

@section('title', 'Farmer Dashboard')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">{{ $farmer->stall_name ?? $farmer->name }} 🌾</h2>
            <p class="text-muted mb-0">Weekly inventory status, incoming pre-orders, and market sales overview</p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('farmer.template.apply') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3" onclick="return confirm('Apply weekly template to replenish stock for this week?')">
                    <i class="bi bi-arrow-repeat me-1"></i> Apply Weekly Stock Template
                </button>
            </form>
            <a href="{{ route('farmer.products') }}" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Manage Produce
            </a>
        </div>
    </div>

    <!-- Farmer KPI Cards (Directly matching SRS Page 10 requirement: Total Orders, Pending Orders, Revenue Summary) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Orders</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">{{ $completedOrders }} completed pickups</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Pending Orders</span>
                        <h3 class="fw-bold text-warning mb-0">{{ $pendingOrders }}</h3>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">{{ $readyOrders }} marked ready for pickup</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Revenue Summary</span>
                        <h3 class="fw-bold text-success mb-0">${{ number_format($revenueSummary, 2) }}</h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-cash-stack fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">Settled in person upon pickup</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Active Stock</span>
                        <h3 class="fw-bold text-info mb-0">{{ $activeProductsCount }}</h3>
                    </div>
                    <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">{{ $soldOutCount }} items currently sold out</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Pre-Orders -->
        <div class="col-lg-8">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-inbox-fill text-success me-2"></i> Incoming Pre-Orders</h5>
                    <a href="{{ route('farmer.orders') }}" class="small text-success fw-bold text-decoration-none">Manage All Orders &rarr;</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Pickup Date & Slot</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $order->customer->name }}</div>
                                        <small class="text-muted">{{ $order->customer->phone }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $order->pickup_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $order->pickup_time_slot }}</small>
                                    </td>
                                    <td>{{ $order->items->sum('quantity') }} items</td>
                                    <td class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $order->status_badge }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('farmer.orders') }}" class="btn btn-outline-success btn-sm rounded-pill py-0 px-2" style="font-size: 0.75rem;">
                                            Update
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No incoming orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Best-Selling Products -->
        <div class="col-lg-4">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-trophy-fill text-warning me-2"></i> Best-Selling Produce</h5>
                @forelse($bestSellers as $bs)
                    <div class="p-2 border-bottom mb-2 small d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-dark d-block">{{ $bs->product_name }}</strong>
                            <small class="text-muted">{{ $bs->total_qty }} units reserved</small>
                        </div>
                        <span class="fw-bold text-success">${{ number_format($bs->total_sales, 2) }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Sales data will appear here once orders are processed.</p>
                @endforelse
            </div>

            <!-- Stall Pickup Schedule Details -->
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
                <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size: 0.78rem;">Stall Pickup Configuration</h6>
                <div class="small text-secondary mb-2">
                    <span class="text-muted d-block">Market:</span>
                    <strong class="text-dark">{{ $farmer->market ? $farmer->market->market_name : 'No Market Assigned' }}</strong>
                </div>
                <div class="small text-secondary mb-2">
                    <span class="text-muted d-block">Operating Days:</span>
                    <strong class="text-dark">{{ $farmer->operating_days ?? 'Weekend' }}</strong>
                </div>
                <div class="small text-secondary mb-3">
                    <span class="text-muted d-block">Order Cutoff:</span>
                    <strong class="text-success">{{ $farmer->cutoff_hours }} hours prior to pickup</strong>
                </div>
                <a href="{{ route('farmer.settings') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
                    <i class="bi bi-gear me-1"></i> Edit Stall Settings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
