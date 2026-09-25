@extends('layouts.app')

@section('title', 'Local Farmers & Stalls')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Local Farmers & Producers</h2>
            <p class="text-muted mb-0">Browse independent growers, view market attendance, and explore weekly fresh harvests</p>
        </div>
        <div>
            <a href="{{ route('map') }}" class="btn btn-outline-success rounded-pill px-3">
                <i class="bi bi-geo-alt-fill me-1"></i> View Stalls on Map
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card card-custom border-0 p-3 mb-4 shadow-sm bg-white">
        <form action="{{ route('farmers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-success"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Search stall or farmer name...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="market_id" class="form-select">
                    <option value="">All Markets</option>
                    @foreach($markets as $m)
                        <option value="{{ $m->id }}" {{ request('market_id') == $m->id ? 'selected' : '' }}>{{ $m->market_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="day" class="form-select">
                    <option value="">Any Operating Day</option>
                    <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1 rounded-pill fw-bold">Filter</button>
                @if(request()->hasAny(['search', 'market_id', 'day']))
                    <a href="{{ route('farmers.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Farmers Cards Grid -->
    <div class="row g-4">
        @forelse($farmers as $farmer)
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm d-flex flex-column bg-white">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                            {{ $farmer->market ? $farmer->market->market_name : 'Independent Stall' }}
                        </span>
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small">
                            <i class="bi bi-star-fill text-warning"></i> {{ $farmer->averageRating() }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-1">
                        <a href="{{ route('farmers.show', $farmer->id) }}" class="text-dark text-decoration-none">
                            {{ $farmer->stall_name ?? $farmer->name }}
                        </a>
                    </h4>
                    <small class="text-muted mb-3 d-block"><i class="bi bi-person me-1"></i> Grower: {{ $farmer->name }}</small>

                    <p class="text-secondary small mb-3 text-truncate-2">
                        {{ $farmer->bio ?? 'Offering premium seasonal harvest and farm fresh products cultivated using sustainable agricultural practices.' }}
                    </p>

                    <div class="p-3 bg-light rounded-3 mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1"></i> Market Days:</span>
                            <span class="fw-bold text-dark">{{ $farmer->operating_days ?? 'Weekend' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-clock me-1"></i> Pickup Window:</span>
                            <span class="fw-bold text-dark">{{ $farmer->pickup_windows ?? '08:00 AM - 01:00 PM' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-basket me-1"></i> Active Items:</span>
                            <span class="fw-bold text-success">{{ $farmer->farmerProducts->count() }} listed</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-top">
                        <a href="{{ route('farmers.show', $farmer->id) }}" class="btn btn-success btn-sm rounded-pill w-100">
                            View Stall & Produce Catalog &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-person-x fs-1 text-muted"></i>
                <h5 class="fw-bold mt-2">No farmers found</h5>
                <p class="text-muted small">Try broadening your search or select a different market.</p>
                <a href="{{ route('farmers.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View All Farmers</a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $farmers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
