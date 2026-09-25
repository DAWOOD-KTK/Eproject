@extends('layouts.app')

@section('title', 'Browse Farmers Markets')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Local Farmers Markets</h2>
            <p class="text-muted mb-0">Discover weekly markets near you, check operating days, and browse attending stalls</p>
        </div>
        <div>
            <a href="{{ route('map') }}" class="btn btn-outline-success rounded-pill px-3">
                <i class="bi bi-map-fill me-1"></i> Interactive Full Map
            </a>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card card-custom border-0 p-3 mb-4 shadow-sm bg-white">
        <form action="{{ route('markets.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-success"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" value="{{ request('search') }}" placeholder="Search market name, street, or area...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="day" class="form-select">
                    <option value="">Any Operating Day</option>
                    <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="Friday" {{ request('day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1 rounded-pill fw-bold">Filter</button>
                @if(request()->hasAny(['search', 'day']))
                    <a href="{{ route('markets.index') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Map Preview of Filtered Markets -->
    <div class="card card-custom border-0 shadow-sm p-2 mb-4">
        <div id="marketsListMap" style="height: 320px; width: 100%; border-radius: 8px;"></div>
    </div>

    <!-- Markets Cards Grid -->
    <div class="row g-4">
        @forelse($markets as $market)
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom h-100 p-4 border-0 shadow-sm d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                            {{ $market->city }}
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-people-fill text-success me-1"></i> {{ $market->farmers_count }} Stalls
                        </span>
                    </div>

                    <h4 class="fw-bold mb-2">
                        <a href="{{ route('markets.show', $market->id) }}" class="text-dark text-decoration-none">
                            {{ $market->market_name }}
                        </a>
                    </h4>

                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $market->address }}
                    </p>

                    <div class="p-3 bg-light rounded-3 mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1"></i> Days:</span>
                            <span class="fw-bold text-dark">{{ $market->operating_days }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-clock me-1"></i> Timings:</span>
                            <span class="fw-bold text-dark">{{ $market->timings }}</span>
                        </div>
                    </div>

                    @if($market->description)
                        <p class="text-secondary small mb-3 text-truncate-2">
                            {{ Str::limit($market->description, 100) }}
                        </p>
                    @endif

                    <div class="mt-auto pt-3 border-top d-flex gap-2">
                        <a href="{{ route('markets.show', $market->id) }}" class="btn btn-success btn-sm rounded-pill flex-grow-1">
                            View Stalls & Produce
                        </a>
                        <a href="https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $market->latitude }}%2C{{ $market->longitude }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle px-2" title="Get Directions">
                            <i class="bi bi-cursor-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-geo-alt-slash fs-1 text-muted"></i>
                <h5 class="fw-bold mt-2">No markets found</h5>
                <p class="text-muted small">Try broadening your search criteria or reset filters.</p>
                <a href="{{ route('markets.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">View All Markets</a>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $markets->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('marketsListMap').setView([24.8607, 67.0011], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const marketIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const bounds = [];
        @foreach($allMarketsForMap as $m)
            const m{{ $m->id }} = L.marker([{{ $m->latitude }}, {{ $m->longitude }}], { icon: marketIcon })
                .addTo(map)
                .bindPopup("<strong>{{ addslashes($m->market_name) }}</strong><br>{{ addslashes($m->address) }}<br><em>{{ $m->operating_days }} ({{ $m->timings }})</em><br><a href='{{ route('markets.show', $m->id) }}' class='btn btn-success btn-sm text-white mt-1' style='font-size:0.75rem;'>Explore Stalls</a>");
            bounds.push([{{ $m->latitude }}, {{ $m->longitude }}]);
        @endforeach

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    });
</script>
@endpush
