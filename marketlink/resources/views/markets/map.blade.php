@extends('layouts.app')

@section('title', 'Interactive Market & Stall Finder Map')

@section('content')
<div class="container-fluid px-3 py-3">
    <div class="card card-custom border-0 shadow-sm overflow-hidden bg-white mb-3">
        <div class="p-3 bg-light border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h4 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-map-fill text-success me-2"></i> Geolocation & Pickup Navigation Map
                </h4>
                <small class="text-muted">Powered by OpenStreetMap. Locate nearest farmers markets, discover farmer stalls, and get directions.</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="small"><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" width="12"> <strong>Markets</strong></span>
                <span class="small"><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png" width="12"> <strong>Farmer Stalls</strong></span>
            </div>
        </div>

        <div class="row g-0">
            <!-- Sidebar Stall / Market List -->
            <div class="col-lg-4 border-end" style="max-height: 650px; overflow-y: auto;">
                <div class="p-3 border-bottom bg-white sticky-top">
                    <input type="text" id="mapSearch" class="form-control form-control-sm rounded-pill" placeholder="Filter by market or farmer name...">
                </div>

                <div class="p-2" id="mapLocationsList">
                    <h6 class="text-muted fw-bold small px-2 mt-2">Farmers Markets</h6>
                    @foreach($markets as $m)
                        <div class="p-2 border rounded-3 mb-2 bg-light location-item" onclick="focusMarker({{ $m->latitude }}, {{ $m->longitude }}, '{{ addslashes($m->market_name) }}')">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-success small">{{ $m->market_name }}</strong>
                                <span class="badge bg-success bg-opacity-25 text-success" style="font-size: 0.65rem;">Market</span>
                            </div>
                            <div class="text-muted small text-truncate">{{ $m->address }}</div>
                            <div class="d-flex justify-content-between text-secondary mt-1" style="font-size: 0.72rem;">
                                <span><i class="bi bi-calendar3"></i> {{ $m->operating_days }}</span>
                                <span><i class="bi bi-clock"></i> {{ $m->timings }}</span>
                            </div>
                        </div>
                    @endforeach

                    <h6 class="text-muted fw-bold small px-2 mt-3">Individual Farmer Stalls</h6>
                    @foreach($farmers as $f)
                        <div class="p-2 border rounded-3 mb-2 bg-light location-item" onclick="focusMarker({{ $f->latitude }}, {{ $f->longitude }}, '{{ addslashes($f->stall_name) }}')">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary small">{{ $f->stall_name }}</strong>
                                <span class="badge bg-primary bg-opacity-25 text-primary" style="font-size: 0.65rem;">Stall</span>
                            </div>
                            <div class="text-muted small text-truncate">{{ $f->address }}</div>
                            <div class="text-secondary mt-1" style="font-size: 0.72rem;">
                                <span><i class="bi bi-clock"></i> Pickup: {{ $f->pickup_windows ?? '08:00 AM - 01:00 PM' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Map View Area -->
            <div class="col-lg-8">
                <div id="fullMap" style="height: 650px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let mapInstance;
    const markers = {};

    document.addEventListener("DOMContentLoaded", function () {
        mapInstance = L.map('fullMap').setView([24.8607, 67.0011], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapInstance);

        const marketIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const farmerIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const bounds = [];

        // Market markers
        @foreach($markets as $m)
            const mm{{ $m->id }} = L.marker([{{ $m->latitude }}, {{ $m->longitude }}], { icon: marketIcon })
                .addTo(mapInstance)
                .bindPopup("<strong>Market: {{ addslashes($m->market_name) }}</strong><br>{{ addslashes($m->address) }}<br><em>{{ $m->operating_days }} ({{ $m->timings }})</em><br><a href='{{ route('markets.show', $m->id) }}' class='btn btn-success btn-sm text-white mt-1' style='font-size:0.75rem;'>View Market Stalls</a> <a href='https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $m->latitude }}%2C{{ $m->longitude }}' target='_blank' class='btn btn-outline-dark btn-sm mt-1' style='font-size:0.75rem;'>Directions</a>");
            bounds.push([{{ $m->latitude }}, {{ $m->longitude }}]);
        @endforeach

        // Farmer stall markers
        @foreach($farmers as $f)
            const ff{{ $f->id }} = L.marker([{{ $f->latitude }}, {{ $f->longitude }}], { icon: farmerIcon })
                .addTo(mapInstance)
                .bindPopup("<strong>Farmer Stall: {{ addslashes($f->stall_name) }}</strong><br>Owner: {{ addslashes($f->name) }}<br>{{ addslashes($f->address) }}<br>Pickup: {{ $f->pickup_windows }}<br><a href='{{ route('farmers.show', $f->id) }}' class='btn btn-primary btn-sm text-white mt-1' style='font-size:0.75rem;'>View Produce Catalog</a> <a href='https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=%3B{{ $f->latitude }}%2C{{ $f->longitude }}' target='_blank' class='btn btn-outline-dark btn-sm mt-1' style='font-size:0.75rem;'>Directions</a>");
            bounds.push([{{ $f->latitude }}, {{ $f->longitude }}]);
        @endforeach

        if (bounds.length > 0) {
            mapInstance.fitBounds(bounds, { padding: [30, 30] });
        }

        // Live filter in sidebar
        document.getElementById('mapSearch').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('#mapLocationsList .location-item').forEach(el => {
                const text = el.innerText.toLowerCase();
                el.style.display = text.includes(query) ? 'block' : 'none';
            });
        });
    });

    function focusMarker(lat, lng, name) {
        if (mapInstance) {
            mapInstance.setView([lat, lng], 15, { animate: true });
        }
    }
</script>
<style>
    .location-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .location-item:hover {
        background: #e8f5e9 !important;
        border-color: #198754 !important;
    }
</style>
@endpush
