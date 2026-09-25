@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Platform Operations Dashboard</h2>
            <p class="text-muted mb-0">Overview of registered farmers, customers, markets, and pre-orders</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-people me-1"></i> Manage Users
            </a>
            <a href="{{ route('admin.markets') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-geo-alt me-1"></i> Add Market
            </a>
        </div>
    </div>

    <!-- Platform KPI Metrics (Matches SRS Page 11 requirement) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Total Farmers</span>
                <h3 class="fw-bold text-success mb-0">{{ $metrics['total_farmers'] }}</h3>
                <small class="text-muted">{{ $metrics['active_farmers'] }} active • <span class="text-warning fw-bold">{{ $metrics['pending_farmers'] }} pending</span></small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Total Customers</span>
                <h3 class="fw-bold text-primary mb-0">{{ $metrics['total_customers'] }}</h3>
                <small class="text-muted">Registered platform shoppers</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Farmers Markets</span>
                <h3 class="fw-bold text-dark mb-0">{{ $metrics['total_markets'] }}</h3>
                <small class="text-muted">Active market hubs</small>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold text-uppercase">Platform GMV / Orders</span>
                <h3 class="fw-bold text-info mb-0">${{ number_format($metrics['total_revenue'], 2) }}</h3>
                <small class="text-muted">{{ $metrics['total_orders'] }} pre-orders placed</small>
            </div>
        </div>
    </div>

    <!-- Pending Farmer Approvals Section -->
    @if($pendingFarmers->isNotEmpty())
        <div class="card p-4 border-0 shadow-sm rounded-3 bg-white border-start border-warning border-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-clock-history text-warning me-2"></i> Pending Farmer Registrations Awaiting Approval
                </h5>
                <span class="badge bg-warning text-dark">{{ $pendingFarmers->count() }} Pending</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th>Stall / Farm</th>
                            <th>Contact Person</th>
                            <th>Email & Phone</th>
                            <th>Operating Days</th>
                            <th class="text-end">Approval Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingFarmers as $pf)
                            <tr>
                                <td>
                                    <strong class="text-dark">{{ $pf->stall_name }}</strong>
                                    <div class="text-muted">{{ $pf->address }}</div>
                                </td>
                                <td>{{ $pf->contact_person ?? $pf->name }}</td>
                                <td>
                                    <div>{{ $pf->email }}</div>
                                    <div class="text-muted">{{ $pf->phone }}</div>
                                </td>
                                <td>{{ $pf->operating_days ?? 'Weekend' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.users.approve', $pf->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                                            <i class="bi bi-check-lg me-1"></i> Approve & Activate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Recent Pre-Orders across platform -->
        <div class="col-lg-7">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i> Recent Platform Pre-Orders</h5>
                    <a href="{{ route('admin.reports') }}" class="small text-primary fw-bold text-decoration-none">Full Reports &rarr;</a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="text-muted">
                            <tr>
                                <th>Order #</th>
                                <th>Farmer</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $ro)
                                <tr>
                                    <td><strong>{{ $ro->order_number }}</strong></td>
                                    <td>{{ $ro->farmer->stall_name ?? $ro->farmer->name }}</td>
                                    <td>{{ $ro->customer->name }}</td>
                                    <td class="fw-bold text-success">${{ number_format($ro->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $ro->status_badge }} rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ $ro->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Registered Users -->
        <div class="col-lg-5">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-people-fill text-success me-2"></i> Recent Registrations</h5>
                    <a href="{{ route('admin.users') }}" class="small text-primary fw-bold text-decoration-none">Manage All &rarr;</a>
                </div>

                @foreach($recentUsers as $ru)
                    <div class="p-2 border-bottom mb-2 small d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-dark d-block">{{ $ru->name }}</strong>
                            <small class="text-muted">{{ $ru->email }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $ru->role === 'farmer' ? 'bg-success' : ($ru->role === 'admin' ? 'bg-danger' : 'bg-primary') }} rounded-pill" style="font-size: 0.65rem;">
                                {{ ucfirst($ru->role) }}
                            </span>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $ru->created_at->format('M d') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
