@extends('layouts.farmer')

@section('title', 'Stall & Pickup Settings')

@section('content')
<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-sliders text-success me-2"></i> Stall & Pickup Settings</h2>
        <p class="text-muted mb-0">Configure your public stall name, operating days, pickup time windows, and map coordinates</p>
    </div>

    <div class="card p-4 p-md-5 border-0 shadow-sm rounded-3 bg-white">
        <form action="{{ route('farmer.settings.update') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Stall / Business Display Name <span class="text-danger">*</span></label>
                    <input type="text" name="stall_name" class="form-control" value="{{ old('stall_name', $farmer->stall_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Contact Person / Owner <span class="text-danger">*</span></label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $farmer->contact_person) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Contact Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $farmer->phone) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Primary Attending Market</label>
                    <select name="market_id" class="form-select">
                        <option value="">-- Independent Stall / No Market --</option>
                        @foreach($markets as $m)
                            <option value="{{ $m->id }}" {{ $farmer->market_id == $m->id ? 'selected' : '' }}>{{ $m->market_name }} ({{ $m->city }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Operating Days <span class="text-danger">*</span></label>
                    <input type="text" name="operating_days" class="form-control" value="{{ old('operating_days', $farmer->operating_days) }}" required placeholder="e.g. Saturday, Sunday">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Pickup Time Windows <span class="text-danger">*</span></label>
                    <input type="text" name="pickup_windows" class="form-control" value="{{ old('pickup_windows', $farmer->pickup_windows) }}" required placeholder="e.g. 08:00 AM - 01:00 PM">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Order Cutoff Window (Hours Prior) <span class="text-danger">*</span></label>
                    <input type="number" name="cutoff_hours" class="form-control" value="{{ old('cutoff_hours', $farmer->cutoff_hours) }}" min="1" max="72" required>
                    <small class="text-muted" style="font-size: 0.72rem;">Customer changes close this many hours before pickup</small>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold">Stall Location Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $farmer->address) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Map Pin Latitude</label>
                    <input type="text" name="latitude" id="latInput" class="form-control" value="{{ old('latitude', $farmer->latitude ?? 24.8607) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Map Pin Longitude</label>
                    <input type="text" name="longitude" id="lngInput" class="form-control" value="{{ old('longitude', $farmer->longitude ?? 67.0011) }}">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-bold">Farm / Stall Bio & Story</label>
                    <textarea name="bio" class="form-control" rows="3">{{ old('bio', $farmer->bio) }}</textarea>
                </div>
            </div>

            <!-- Interactive Pin Setter Map -->
            <div class="mt-4">
                <label class="form-label small fw-bold">Adjust Stall Location Pin (Click on Map to Set Coordinates):</label>
                <div id="settingsMap" style="height: 250px; width: 100%; border-radius: 8px;" class="border"></div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                    <i class="bi bi-save me-1"></i> Save Stall Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let curLat = parseFloat(document.getElementById('latInput').value) || 24.8607;
        let curLng = parseFloat(document.getElementById('lngInput').value) || 67.0011;

        const map = L.map('settingsMap').setView([curLat, curLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([curLat, curLng], { draggable: true }).addTo(map);

        function updateCoords(lat, lng) {
            document.getElementById('latInput').value = lat.toFixed(6);
            document.getElementById('lngInput').value = lng.toFixed(6);
        }

        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            updateCoords(pos.lat, pos.lng);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
@endpush
