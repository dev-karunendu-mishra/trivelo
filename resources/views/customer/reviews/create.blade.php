@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Write Review - Trivelo')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">Write Review</h1>
                    <p class="mb-0 text-white-50">Share your experience at {{ $booking->hotel->name }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('customer.reviews') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Back to Reviews
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Form -->
    <section class="review-form py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Hotel Information Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if($booking->hotel->main_image)
                                        <img src="{{ $booking->hotel->main_image }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $booking->hotel->name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <i class="bi bi-building text-muted fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h4 class="mb-2">{{ $booking->hotel->name }}</h4>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-geo-alt"></i> {{ $booking->hotel->location->city ?? $booking->hotel->city }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <strong>Your Stay:</strong> 
                                        {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}
                                        ({{ $booking->nights }} night{{ $booking->nights > 1 ? 's' : '' }})
                                    </p>
                                    <p class="text-muted mb-0">
                                        <strong>Booking:</strong> #{{ $booking->booking_number }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-star-fill text-warning"></i> Rate & Review</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.reviews.store', $booking) }}" method="POST" id="reviewForm">
                                @csrf
                                
                                <!-- Rating Section -->
                                <div class="mb-4">
                                    <label class="form-label">Overall Rating <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <div class="stars-container d-flex align-items-center">
                                            <div class="stars me-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star star" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                                @endfor
                                            </div>
                                            <span id="ratingText" class="text-muted">Click to rate</span>
                                        </div>
                                        <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
                                        @error('rating')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Review Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label">Review Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}"
                                           placeholder="Summarize your experience in a few words"
                                           maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span id="titleCount">0</span>/255 characters
                                    </div>
                                </div>

                                <!-- Review Comment -->
                                <div class="mb-4">
                                    <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror" 
                                              id="comment" 
                                              name="comment" 
                                              rows="8"
                                              placeholder="Tell others about your stay. What did you like? What could be improved? Be specific and honest to help fellow travelers."
                                              minlength="10"
                                              maxlength="1000">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text d-flex justify-content-between">
                                        <span><span id="commentCount">0</span>/1000 characters (minimum 10)</span>
                                        <span id="commentStatus" class="text-muted"></span>
                                    </div>
                                </div>

                                <!-- Review Guidelines -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Review Guidelines</h6>
                                    <ul class="mb-0 small">
                                        <li>Be honest and constructive in your feedback</li>
                                        <li>Focus on your personal experience during the stay</li>
                                        <li>Avoid profanity, discriminatory language, or personal attacks</li>
                                        <li>Don't include personal information like phone numbers or email addresses</li>
                                        <li>Reviews are moderated and will be published once approved</li>
                                    </ul>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="bi bi-check-lg"></i> Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Previous Reviews Section -->
                    @if($booking->hotel->approvedReviews->count() > 0)
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-chat-square-text text-primary"></i> 
                                    Recent Reviews ({{ $booking->hotel->approvedReviews->count() }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach($booking->hotel->approvedReviews->take(3) as $review)
                                    <div class="review-preview {{ !$loop->last ? 'border-bottom' : '' }} pb-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="rating mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                                    @endfor
                                                    <span class="ms-2 text-muted">{{ $review->rating }}/5</span>
                                                </div>
                                                <h6 class="mb-1">{{ $review->title }}</h6>
                                                <p class="text-muted small mb-1">{{ Str::limit($review->comment, 150) }}</p>
                                                <small class="text-muted">
                                                    by {{ $review->user->name }} • {{ $review->created_at->format('M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
    }

    .rating-input .stars {
        font-size: 2rem;
    }

    .rating-input .star {
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 0.25rem;
    }

    .rating-input .star:hover,
    .rating-input .star.active {
        color: #ffc107 !important;
        transform: scale(1.1);
    }

    .rating-input .star.filled {
        color: #ffc107 !important;
    }

    .stars-container {
        padding: 1rem 0;
    }

    #ratingText {
        font-size: 1.1rem;
        font-weight: 500;
        min-width: 150px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .character-count {
        text-align: right;
        font-size: 0.875rem;
        color: #6c757d;
    }

    .review-preview {
        font-size: 0.9rem;
    }

    .review-preview .rating i {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .rating-input .stars {
            font-size: 1.5rem;
        }
        
        .stars-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stars {
            margin-right: 0 !important;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
let currentRating = {{ old('rating', 0) }};

// Rating functionality
function setRating(rating) {
    currentRating = rating;
    document.getElementById('rating').value = rating;
    updateStars();
    updateRatingText(rating);
}

function updateStars() {
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        const starRating = index + 1;
        if (starRating <= currentRating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill', 'filled');
        } else {
            star.classList.remove('bi-star-fill', 'filled');
            star.classList.add('bi-star');
        }
    });
}

function updateRatingText(rating) {
    const ratingText = document.getElementById('ratingText');
    const texts = {
        1: 'Poor - Did not meet expectations',
        2: 'Fair - Below average experience',
        3: 'Good - Average experience',
        4: 'Very Good - Above average experience',
        5: 'Excellent - Exceeded expectations'
    };
    ratingText.textContent = texts[rating] || 'Click to rate';
    ratingText.className = rating ? 'text-warning fw-semibold' : 'text-muted';
}

// Character counting
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const commentTextarea = document.getElementById('comment');
    const titleCount = document.getElementById('titleCount');
    const commentCount = document.getElementById('commentCount');
    const commentStatus = document.getElementById('commentStatus');
    const submitBtn = document.getElementById('submitBtn');

    // Initialize rating if there's an old value
    if (currentRating > 0) {
        updateStars();
        updateRatingText(currentRating);
    }

    // Title character count
    function updateTitleCount() {
        const count = titleInput.value.length;
        titleCount.textContent = count;
        titleCount.className = count > 200 ? 'text-warning' : '';
    }

    // Comment character count and validation
    function updateCommentCount() {
        const count = commentTextarea.value.length;
        commentCount.textContent = count;
        
        if (count < 10) {
            commentStatus.textContent = `${10 - count} more characters needed`;
            commentStatus.className = 'text-danger';
        } else {
            commentStatus.textContent = 'Good length';
            commentStatus.className = 'text-success';
        }
        
        commentCount.className = count > 800 ? 'text-warning' : '';
    }

    // Form validation
    function validateForm() {
        const isValid = currentRating > 0 && 
                       titleInput.value.trim().length > 0 && 
                       commentTextarea.value.trim().length >= 10;
        
        submitBtn.disabled = !isValid;
        submitBtn.className = isValid ? 'btn btn-primary' : 'btn btn-outline-primary';
    }

    // Event listeners
    titleInput.addEventListener('input', () => {
        updateTitleCount();
        validateForm();
    });

    commentTextarea.addEventListener('input', () => {
        updateCommentCount();
        validateForm();
    });

    // Initialize counts
    updateTitleCount();
    updateCommentCount();
    validateForm();

    // Form submission
    document.getElementById('reviewForm').addEventListener('submit', function() {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        submitBtn.disabled = true;
    });

    // Star hover effects
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            updateRatingText(rating);
        });

        star.addEventListener('mouseleave', function() {
            if (currentRating > 0) {
                updateRatingText(currentRating);
            } else {
                document.getElementById('ratingText').textContent = 'Click to rate';
                document.getElementById('ratingText').className = 'text-muted';
            }
        });
    });
});
</script>
@endpush