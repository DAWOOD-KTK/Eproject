@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-2">Get In Touch</span>
        <h2 class="display-6 fw-bold">Contact the MarketLink Team</h2>
        <p class="text-muted">Have questions about joining as a farmer or need support with pre-orders? We are here to help!</p>
    </div>

    <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white">
                <h5 class="fw-bold mb-3"><i class="bi bi-chat-dots-fill text-success me-2"></i> Send us a Message</h5>
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Your Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Question about weekend pickup or stall registration">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Message</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                        <i class="bi bi-send me-1"></i> Send Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Information & Map -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 border-0 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-success me-2"></i> Headquarters & Support</h5>
                <ul class="list-unstyled text-secondary small mb-0">
                    <li class="mb-2"><i class="bi bi-building me-2 text-success"></i> MarketLink Project Hub, Aptech Learning Center</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2 text-success"></i> support@marketlink.com / admin@marketlink.com</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2 text-success"></i> +1 (555) 019-2834 / +92 300 1234567</li>
                    <li class="mb-2"><i class="bi bi-clock me-2 text-success"></i> Support Hours: Monday - Friday, 09:00 AM - 06:00 PM</li>
                    <li><i class="bi bi-shield-check me-2 text-success"></i> Event: Aptech TechWiz 7 Competition</li>
                </ul>
            </div>

            <!-- Map Showing Headquarters Location -->
            <div class="card card-custom border-0 shadow-sm overflow-hidden p-2">
                <div class="small fw-bold text-muted p-2"><i class="bi bi-map me-1 text-success"></i> Team Headquarters Location:</div>
                <div id="contactMap" style="height: 250px; width: 100%; border-radius: 8px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const contactMap = L.map('contactMap').setView([24.8607, 67.0011], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(contactMap);

        L.marker([24.8607, 67.0011])
            .addTo(contactMap)
            .bindPopup("<strong>MarketLink Project HQ</strong><br>Aptech TechWiz 7 Hub<br>Open Mon-Fri 9AM-6PM")
            .openPopup();
    });
</script>
@endpush
