@extends('layouts.admin')

@section('title', 'Manage Farmers Markets')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-geo-alt text-primary me-2"></i> Manage Farmers Markets</h2>
            <p class="text-muted mb-0">Create, update, and manage market hubs, operating days, timings, and map coordinates</p>
        </div>
        <button class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addMarketModal">
            <i class="bi bi-plus-lg me-1"></i> Add Farmers Market
        </button>
    </div>

    <!-- Markets Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Market Name</th>
                        <th>City / Address</th>
                        <th>Operating Schedule</th>
                        <th>Coordinates</th>
                        <th>Stalls Registered</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($markets as $market)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark">{{ $market->market_name }}</strong>
                            </td>
                            <td>
                                <div><span class="badge bg-light text-dark border">{{ $market->city }}</span></div>
                                <small class="text-muted">{{ $market->address }}</small>
                            </td>
                            <td>
                                <div><i class="bi bi-calendar3 text-success me-1"></i> {{ $market->operating_days }}</div>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $market->timings }}</small>
                            </td>
                            <td class="small text-muted">
                                {{ number_format($market->latitude, 4) }}, {{ number_format($market->longitude, 4) }}
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2 py-1">
                                    {{ $market->farmers_count }} Stalls
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="btn btn-outline-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editMarket{{ $market->id }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.markets.delete', $market->id) }}" method="POST" onsubmit="return confirm('Delete this market?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle px-2" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Market Modal -->
                                <div class="modal fade text-start" id="editMarket{{ $market->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.markets.update', $market->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Market</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Market Name</label>
                                                        <input type="text" name="market_name" class="form-control" value="{{ $market->market_name }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">City</label>
                                                            <input type="text" name="city" class="form-control" value="{{ $market->city }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Operating Days</label>
                                                            <input type="text" name="operating_days" class="form-control" value="{{ $market->operating_days }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Timings</label>
                                                        <input type="text" name="timings" class="form-control" value="{{ $market->timings }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Latitude</label>
                                                            <input type="text" name="latitude" class="form-control" value="{{ $market->latitude }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Longitude</label>
                                                            <input type="text" name="longitude" class="form-control" value="{{ $market->longitude }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Address</label>
                                                        <textarea name="address" class="form-control" rows="2" required>{{ $market->address }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="description" class="form-control" rows="2">{{ $market->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">Update Market</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No markets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $markets->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Add Market Modal -->
<div class="modal fade" id="addMarketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.markets.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Farmers Market</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Market Name <span class="text-danger">*</span></label>
                        <input type="text" name="market_name" class="form-control" required placeholder="e.g. Westside Organic Farmers Market">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" required placeholder="Metropolis">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Operating Days <span class="text-danger">*</span></label>
                            <input type="text" name="operating_days" class="form-control" required placeholder="e.g. Saturday, Sunday">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Timings <span class="text-danger">*</span></label>
                        <input type="text" name="timings" class="form-control" required placeholder="e.g. 08:00 AM - 01:00 PM">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Latitude <span class="text-danger">*</span></label>
                            <input type="text" name="latitude" class="form-control" value="24.8607" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Longitude <span class="text-danger">*</span></label>
                            <input type="text" name="longitude" class="form-control" value="67.0011" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required placeholder="Park square, street..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Market features, stalls, and specialties..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Save Market</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
