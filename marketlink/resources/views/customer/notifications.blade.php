@extends('layouts.app')

@section('title', 'In-App Alerts & Notifications')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-bell-fill text-warning me-2"></i> In-App Alerts & Notifications</h2>
        <p class="text-muted mb-0">Stay informed about order status updates, pickup readiness, restock notices, and platform news</p>
    </div>

    <div class="card card-custom p-4 border-0 shadow-sm bg-white">
        @forelse($notifications as $notif)
            <div class="p-3 border rounded-3 mb-3 {{ $notif->is_read ? 'bg-light' : 'bg-success bg-opacity-10 border-success' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold mb-0 text-dark">
                        @if($notif->type === 'order_status')
                            <i class="bi bi-box-seam text-primary me-2"></i>
                        @elseif($notif->type === 'restock')
                            <i class="bi bi-arrow-repeat text-success me-2"></i>
                        @else
                            <i class="bi bi-megaphone-fill text-warning me-2"></i>
                        @endif
                        {{ $notif->title }}
                    </h6>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <p class="text-secondary small mb-2">{{ $notif->message }}</p>
                @if($notif->link)
                    <a href="{{ $notif->link }}" class="btn btn-outline-success btn-sm rounded-pill px-3" style="font-size: 0.75rem;">
                        View Related &rarr;
                    </a>
                @endif
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                <h6>You're all caught up!</h6>
                <p class="small mb-0">No new alerts at this time.</p>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
