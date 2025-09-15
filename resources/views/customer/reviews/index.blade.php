@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'My Reviews - Trivelo')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">My Reviews</h1>
                    <p class="mb-0 text-white-50">Reviews and ratings you've shared</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Stats -->
    <section class="review-stats py-4 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $reviewStats['total_reviews'] ?? 0 }}</h3>
                            <p>Total Reviews</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ number_format($reviewStats['average_rating'] ?? 0, 1) }}</h3>
                            <p>Average Rating</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $reviewStats['recent_reviews'] ?? 0 }}</h3>
                            <p>Recent Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Content -->
    <section class="reviews-content py-5">
        <div class="container">
            @if($reviews->count() > 0)
                <div class="row">
                    @foreach($reviews as $review)
                        <div class="col-12 mb-4">
                            <div class="review-card card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Hotel Image -->
                                        <div class="col-md-2">
                                            @if($review->hotel->main_image)
                                                <img src="{{ $review->hotel->main_image }}"
                                                     class="img-fluid rounded"
                                                     alt="{{ $review->hotel->name }}"
                                                     style="height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                    <i class="bi bi-building text-muted fs-2"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Review Content -->
                                        <div class="col-md-8">
                                            <div class="review-header mb-2">
                                                <h5 class="mb-1">{{ $review->hotel->name }}</h5>
                                                <div class="rating mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                                    @endfor
                                                    <span class="ms-2 text-muted">{{ $review->rating }}/5</span>
                                                </div>
                                            </div>

                                            <h6 class="review-title">{{ $review->title }}</h6>
                                            <p class="review-comment text-muted">{{ $review->comment }}</p>

                                            <div class="review-meta">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    Reviewed on {{ $review->created_at->format('M d, Y') }}
                                                </small>
                                                @if($review->booking)
                                                    <small class="text-muted ms-3">
                                                        <i class="bi bi-receipt me-1"></i>
                                                        Booking #{{ $review->booking->booking_number }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Status & Actions -->
                                        <div class="col-md-2">
                                            <div class="text-center">
                                                <span class="badge bg-{{ $review->status === 'approved' ? 'success' : ($review->status === 'pending' ? 'warning' : 'danger') }} mb-3">
                                                    {{ ucfirst($review->status) }}
                                                </span>

                                                @if($review->status === 'approved')
                                                    <div class="mb-2">
                                                        <i class="bi bi-eye text-muted"></i>
                                                        <small class="text-muted d-block">Published</small>
                                                    </div>
                                                @elseif($review->status === 'pending')
                                                    <div class="mb-2">
                                                        <i class="bi bi-clock text-warning"></i>
                                                        <small class="text-muted d-block">Under Review</small>
                                                    </div>
                                                @endif

                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('hotels.show', $review->hotel) }}" target="_blank">
                                                                <i class="bi bi-building me-2"></i> View Hotel
                                                            </a>
                                                        </li>
                                                        @if($review->booking)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('customer.booking.show', $review->booking) }}">
                                                                    <i class="bi bi-receipt me-2"></i> View Booking
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($review->status === 'pending' || $review->status === 'rejected')
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('customer.reviews.edit', $review) }}">
                                                                    <i class="bi bi-pencil me-2"></i> Edit Review
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger" onclick="deleteReview('{{ $review->id }}')">
                                                                    <i class="bi bi-trash me-2"></i> Delete Review
                                                                </button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state text-center py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <i class="bi bi-chat-square-text text-muted mb-4" style="font-size: 4rem;"></i>
                            <h3 class="text-muted mb-3">No Reviews Yet</h3>
                            <p class="text-muted mb-4">
                                Share your experience by writing reviews for hotels you've stayed at. 
                                Your feedback helps other travelers make better decisions.
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('customer.bookings') }}" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> View My Bookings
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-search"></i> Search Hotels
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Reviews Section -->
            @if($pendingReviews ?? false)
                <div class="pending-reviews mt-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Ready to Review</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">You have completed bookings that haven't been reviewed yet.</p>
                            <div class="row">
                                @foreach($pendingReviews as $booking)
                                    <div class="col-md-4 mb-3">
                                        <div class="pending-review-item border rounded p-3">
                                            <h6>{{ $booking->hotel->name }}</h6>
                                            <small class="text-muted">Stayed: {{ $booking->check_out_date->format('M d, Y') }}</small>
                                            <div class="mt-2">
                                                <a href="{{ route('customer.reviews.create', $booking) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-star"></i> Write Review
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #2d3748;
    }

    .stat-info p {
        color: #718096;
        margin-bottom: 0;
        font-weight: 500;
    }

    .review-card {
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .review-title {
        color: #2d3748;
        font-weight: 600;
    }

    .review-comment {
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .rating i {
        font-size: 1rem;
    }

    .pending-review-item {
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    .pending-review-item:hover {
        background: #e9ecef;
        transform: translateY(-1px);
    }

    .empty-state {
        min-height: 400px;
        display: flex;
        align-items: center;
    }

    .dropdown-toggle::after {
        display: none;
    }

    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
        }
        
        .review-card .row > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on load
    const reviewCards = document.querySelectorAll('.review-card');
    reviewCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Stats cards animation
    const statsCards = document.querySelectorAll('.stat-card');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Delete review function
function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/customer/reviews/${reviewId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush