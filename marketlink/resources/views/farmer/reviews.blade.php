@extends('layouts.farmer')

@section('title', 'Customer Reviews')

@section('content')
<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-1"><i class="bi bi-star-half text-warning me-2"></i> Customer Reviews & Responses</h2>
        <p class="text-muted mb-0">View customer ratings and publish official stall replies to feedback</p>
    </div>

    <div class="card p-4 border-0 shadow-sm rounded-3 bg-white">
        @forelse($reviews as $review)
            <div class="p-3 border rounded-3 mb-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <strong class="text-dark">{{ $review->customer->name }}</strong>
                        @if($review->product)
                            <span class="text-muted small">reviewed <strong>{{ $review->product->name }}</strong></span>
                        @endif
                    </div>
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                        <span class="text-dark fw-bold ms-1" style="font-size: 0.8rem;">{{ $review->rating }}/5</span>
                    </div>
                </div>

                <p class="text-secondary small mb-2">"{{ $review->comment }}"</p>
                <small class="text-muted d-block" style="font-size: 0.72rem;">Posted on {{ $review->created_at->format('M d, Y') }}</small>

                <!-- Farmer's Existing Reply or Reply Form -->
                @if($review->farmer_reply)
                    <div class="mt-3 p-3 bg-white border-start border-success border-3 rounded small">
                        <strong class="text-success d-block mb-1"><i class="bi bi-reply-fill me-1"></i> Your Published Response:</strong>
                        <p class="text-dark mb-1">{{ $review->farmer_reply }}</p>
                        <small class="text-muted" style="font-size: 0.7rem;">Replied on {{ $review->farmer_replied_at ? $review->farmer_replied_at->format('M d, Y') : '' }}</small>
                    </div>
                @else
                    <div class="mt-3 pt-2 border-top">
                        <form action="{{ route('farmer.reviews.reply', $review->id) }}" method="POST">
                            @csrf
                            <label class="form-label small fw-bold text-muted">Respond to Customer:</label>
                            <div class="input-group">
                                <input type="text" name="farmer_reply" class="form-control form-control-sm" placeholder="e.g. Thank you for visiting our stall! We look forward to seeing you this Saturday..." required>
                                <button type="submit" class="btn btn-success btn-sm px-3 fw-bold">Post Reply</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5 text-muted">
                <i class="bi bi-star fs-1 d-block mb-2"></i>
                <h6>No reviews received yet.</h6>
                <p class="small mb-0">Once customers complete pre-orders, their feedback will appear here.</p>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
