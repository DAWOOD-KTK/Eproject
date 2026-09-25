@extends('layouts.farmer')

@section('title', 'Sales & Order Insights')

@section('content')
<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow text-success me-2"></i> Sales History & Insights</h2>
        <p class="text-muted mb-0">Track total revenue, examine order fulfillment rates, and discover your most demanded produce</p>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold">GROSS REVENUE</span>
                <h3 class="fw-bold text-success mb-0">${{ number_format($totalSales, 2) }}</h3>
                <small class="text-muted">Direct in-person sales</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold">TOTAL ORDERS</span>
                <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
                <small class="text-muted">All-time received</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold">SUCCESSFUL PICKUPS</span>
                <h3 class="fw-bold text-primary mb-0">{{ $completedOrders }}</h3>
                <small class="text-muted">Completed fulfillment</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3 bg-white">
                <span class="text-muted small fw-bold">CANCELLED / DECLINED</span>
                <h3 class="fw-bold text-danger mb-0">{{ $cancelledOrders }}</h3>
                <small class="text-muted">Prior to cutoff</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Best-Selling Products Performance -->
        <div class="col-lg-7">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-award-fill text-warning me-2"></i> Best-Selling Produce Breakdown</h5>

                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Produce Item</th>
                                <th>Units Reserved</th>
                                <th>Gross Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $item)
                                <tr>
                                    <td><strong class="text-dark">{{ $item->product_name }}</strong></td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->units_sold }} units</span></td>
                                    <td class="fw-bold text-success">${{ number_format($item->gross_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No sales recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Trend -->
        <div class="col-lg-5">
            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-calendar3 text-success me-2"></i> Monthly Sales Activity</h5>

                @forelse($monthlySales as $ms)
                    <div class="p-3 bg-light rounded-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-dark d-block">{{ date('F Y', strtotime($ms->month . '-01')) }}</strong>
                            <small class="text-muted">{{ $ms->order_count }} orders</small>
                        </div>
                        <span class="fs-5 fw-bold text-success">${{ number_format($ms->revenue, 2) }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Monthly statistics will be compiled as orders occur.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
