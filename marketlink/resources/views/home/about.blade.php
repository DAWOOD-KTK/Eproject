@extends('layouts.app')

@section('title', 'About Us - eGreen Basket Initiative')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5 g-5">
        <div class="col-lg-6">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-2">
                TechWiz 7 Project • Category: End-to-End Web Solutions
            </span>
            <h2 class="display-5 fw-bold text-dark mb-3">Connecting Local Producers with Communities</h2>
            <p class="text-secondary lead">
                MarketLink is an innovative full-stack Web platform designed under the <strong>eGreen Basket</strong> theme to bridge the communication gap between local farmers and health-conscious consumers.
            </p>
            <p class="text-muted">
                Historically, farmers markets operated on informal word-of-mouth or chalkboard notices, leaving shoppers uncertain of which producers would attend, what stock was available, or at what price. MarketLink brings predictability to both sides: farmers can publicize weekly harvests and manage incoming pre-orders, while customers can secure freshly harvested food for guaranteed pickup.
            </p>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white">
                <h5 class="fw-bold mb-3"><i class="bi bi-bullseye text-success me-2"></i> Our Core Mission</h5>
                <ul class="list-unstyled text-secondary small">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <div><strong>Zero Food Waste:</strong> Helping farmers harvest based on confirmed incoming pre-orders rather than risky estimations.</div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <div><strong>Freshness Guaranteed:</strong> Connecting customers to local harvests picked within hours of weekend market openings.</div>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <div><strong>Transparent In-Person Settlement:</strong> No complicated online payment gateways; pre-orders are paid for in cash at pickup.</div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <div><strong>Interactive Geolocation:</strong> OpenStreetMap integration displaying stall markers, directions, and market operating hours.</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Architectural Pillars -->
    <div class="row g-4 text-center my-4">
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100">
                <i class="bi bi-person-check-fill text-success fs-1 mb-2"></i>
                <h6 class="fw-bold">Role-Based Portals</h6>
                <p class="text-muted small mb-0">Separate dashboards tailored for Admins, Farmers/Vendors, and Customers.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100">
                <i class="bi bi-map-fill text-success fs-1 mb-2"></i>
                <h6 class="fw-bold">Map Discovery</h6>
                <p class="text-muted small mb-0">Powered by OpenStreetMap & Leaflet with pickup location markers and routing assistance.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100">
                <i class="bi bi-robot text-success fs-1 mb-2"></i>
                <h6 class="fw-bold">AI Assistant</h6>
                <p class="text-muted small mb-0">Intelligent chatbot answering market timings, farmer availability, and product stock queries.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100">
                <i class="bi bi-clock-history text-success fs-1 mb-2"></i>
                <h6 class="fw-bold">Cutoff Control</h6>
                <p class="text-muted small mb-0">Configurable cutoff windows allowing customers to modify or cancel orders smoothly.</p>
            </div>
        </div>
    </div>

    <!-- The Development Team -->
    <div class="mt-5 text-center">
        <h4 class="fw-bold mb-2">The Project Team</h4>
        <p class="text-muted small mb-4">Developed for TechWiz 7 World Tech Championship</p>
        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <div class="card card-custom p-3 border-0 shadow-sm">
                    <div class="bg-success text-white rounded-circle mx-auto d-flex align-items-center justify-content-center fs-3 mb-2" style="width: 60px; height: 60px;">
                        ML
                    </div>
                    <h6 class="fw-bold mb-0">TechWiz Developer Team</h6>
                    <small class="text-muted">Aptech Learning Center</small>
                    <p class="small text-secondary mt-2 mb-0">Specialized in Full-Stack Web Engineering with Laravel & Modern UI</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
