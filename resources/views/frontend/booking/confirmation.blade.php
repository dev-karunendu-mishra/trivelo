@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Booking Confirmation - ' . $booking->booking_number . ' - Trivelo')

@section('content')
    <!-- Success Header -->
    <section class="confirmation-header py-5 bg-success text-white">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-icon mb-3">
                        <i class="bi bi-check-circle" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="mb-3">Booking Confirmed!</h1>
                    <p class="lead mb-4">
                        Thank you for your reservation. Your booking has been confirmed and you will receive an email confirmation shortly.
                    </p>
                    <div class="booking-number">
                        <strong>Booking Reference: {{ $booking->booking_number }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details -->
    <section class="booking-details py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Hotel & Room Information -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Your Reservation Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Hotel Information -->
                                    <div class="hotel-info mb-4">
                                        <div class="d-flex align-items-start">
                                            @if($booking->hotel->main_image)
                                                <img src="{{ $booking->hotel->main_image }}" 
                                                     class="hotel-thumbnail me-3" 
                                                     alt="{{ $booking->hotel->name }}"
                                                     style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px;">
                                            @endif
                                            <div>
                                                <h5 class="mb-2">{{ $booking->hotel->name }}</h5>
                                                <div class="star-rating mb-2">
                                                    @for($i = 0; $i < $booking->hotel->star_rating; $i++)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @endfor
                                                    @if($booking->hotel->average_rating)
                                                        <span class="ms-2 text-muted">{{ $booking->hotel->formatted_rating }}/5</span>
                                                    @endif
                                                </div>
                                                <p class="text-muted mb-2">
                                                    <i class="bi bi-geo-alt"></i> {{ $booking->hotel->location->full_address ?? $booking->hotel->full_address }}
                                                </p>
                                                <div class="contact-info">
                                                    <small class="text-muted">
                                                        <i class="bi bi-telephone"></i> {{ $booking->hotel->phone }}
                                                        @if($booking->hotel->email)
                                                            | <i class="bi bi-envelope"></i> {{ $booking->hotel->email }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Room Information -->
                                    <div class="room-info mb-4">
                                        <h6 class="fw-bold mb-2">Room Details</h6>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <strong>Room Type:</strong><br>
                                                <span class="text-muted">{{ $booking->room->formatted_type }}</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Capacity:</strong><br>
                                                <span class="text-muted">{{ $booking->room->capacity }} guests</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Booking Dates & Duration -->
                                    <div class="booking-dates mb-4">
                                        <h6 class="fw-bold mb-3">Stay Details</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="date-info text-center p-3 bg-light rounded">
                                                    <i class="bi bi-calendar-check text-primary mb-2" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <strong>Check-in</strong><br>
                                                        <span class="text-primary">{{ $booking->check_in_date->format('M d, Y') }}</span><br>
                                                        <small class="text-muted">After 3:00 PM</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="date-info text-center p-3 bg-light rounded">
                                                    <i class="bi bi-moon text-primary mb-2" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <strong>Duration</strong><br>
                                                        <span class="text-primary">{{ $booking->nights }} night{{ $booking->nights != 1 ? 's' : '' }}</span><br>
                                                        <small class="text-muted">{{ $booking->adults }} adult{{ $booking->adults != 1 ? 's' : '' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="date-info text-center p-3 bg-light rounded">
                                                    <i class="bi bi-calendar-x text-primary mb-2" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <strong>Check-out</strong><br>
                                                        <span class="text-primary">{{ $booking->check_out_date->format('M d, Y') }}</span><br>
                                                        <small class="text-muted">Before 11:00 AM</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Guest Information -->
                                    <div class="guest-info mb-4">
                                        <h6 class="fw-bold mb-2">Guest Information</h6>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <strong>Name:</strong><br>
                                                <span class="text-muted">
                                                    {{ $booking->guest_details['first_name'] ?? 'N/A' }} 
                                                    {{ $booking->guest_details['last_name'] ?? '' }}
                                                </span>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Contact:</strong><br>
                                                <span class="text-muted">{{ $booking->guest_details['email'] ?? 'N/A' }}</span><br>
                                                <span class="text-muted">{{ $booking->guest_details['phone'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($booking->special_requests)
                                            <div class="mt-3">
                                                <strong>Special Requests:</strong><br>
                                                <span class="text-muted">{{ $booking->special_requests }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Price Summary -->
                                <div class="col-lg-4">
                                    <div class="price-summary bg-light p-3 rounded">
                                        <h6 class="fw-bold mb-3">Payment Summary</h6>
                                        
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Room rate ({{ $booking->nights }} night{{ $booking->nights != 1 ? 's' : '' }})</span>
                                            <span>${{ number_format($booking->subtotal, 2) }}</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Taxes & fees</span>
                                            <span>${{ number_format($booking->tax_amount, 2) }}</span>
                                        </div>
                                        
                                        @if($booking->discount_amount > 0)
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span>Discount</span>
                                                <span>-${{ number_format($booking->discount_amount, 2) }}</span>
                                            </div>
                                        @endif
                                        
                                        <hr>
                                        <div class="d-flex justify-content-between mb-0">
                                            <strong>Total Paid</strong>
                                            <strong class="text-success">${{ number_format($booking->total_amount, 2) }}</strong>
                                        </div>
                                        
                                        <div class="payment-status mt-3">
                                            <span class="badge bg-{{ $booking->payment_status_color }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 text-center h-100">
                                <div class="card-body">
                                    <i class="bi bi-envelope text-primary mb-3" style="font-size: 2rem;"></i>
                                    <h6>Email Confirmation</h6>
                                    <p class="text-muted small">
                                        A confirmation email has been sent to {{ $booking->guest_details['email'] ?? $booking->user->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 text-center h-100">
                                <div class="card-body">
                                    <i class="bi bi-calendar-x text-info mb-3" style="font-size: 2rem;"></i>
                                    <h6>Free Cancellation</h6>
                                    <p class="text-muted small">
                                        Cancel up to 24 hours before check-in for a full refund
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 text-center h-100">
                                <div class="card-body">
                                    <i class="bi bi-headset text-success mb-3" style="font-size: 2rem;"></i>
                                    <h6>24/7 Support</h6>
                                    <p class="text-muted small">
                                        Need help? Our support team is available round the clock
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="btn-group gap-3" role="group">
                                    <a href="{{ route('customer.bookings') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-list-ul"></i> View My Bookings
                                    </a>
                                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="bi bi-house"></i> Back to Home
                                    </a>
                                    <button class="btn btn-outline-secondary btn-lg" onclick="window.print()">
                                        <i class="bi bi-printer"></i> Print Confirmation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Next Steps -->
    <section class="next-steps py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="text-center mb-4">What Happens Next?</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="step-item d-flex">
                                <div class="step-number bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    1
                                </div>
                                <div>
                                    <h6>Confirmation Email</h6>
                                    <p class="text-muted small">You'll receive a detailed confirmation email within 5 minutes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-item d-flex">
                                <div class="step-number bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    2
                                </div>
                                <div>
                                    <h6>Pre-Arrival</h6>
                                    <p class="text-muted small">The hotel may contact you 24 hours before check-in</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-item d-flex">
                                <div class="step-number bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    3
                                </div>
                                <div>
                                    <h6>Check-in</h6>
                                    <p class="text-muted small">Present your ID and booking reference at the hotel reception</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-item d-flex">
                                <div class="step-number bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    4
                                </div>
                                <div>
                                    <h6>Enjoy Your Stay</h6>
                                    <p class="text-muted small">Have a wonderful experience and don't forget to leave a review!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .confirmation-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .success-icon {
        animation: bounceIn 0.6s ease-out;
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0.3;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
        70% {
            transform: scale(0.9);
            opacity: 0.9;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .date-info {
        transition: transform 0.2s ease;
    }

    .date-info:hover {
        transform: translateY(-2px);
    }

    .step-item {
        margin-bottom: 1.5rem;
    }

    .hotel-thumbnail {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .price-summary {
        border: 1px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 0.5rem;
    }

    @media print {
        .btn, .next-steps {
            display: none !important;
        }
        
        .confirmation-header {
            background: #28a745 !important;
            color: white !important;
        }
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-group .btn {
            margin: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save booking details to localStorage for offline access
    const bookingDetails = {
        booking_number: '{{ $booking->booking_number }}',
        hotel_name: '{{ $booking->hotel->name }}',
        check_in: '{{ $booking->check_in_date->format("Y-m-d") }}',
        check_out: '{{ $booking->check_out_date->format("Y-m-d") }}',
        total_amount: {{ $booking->total_amount }}
    };
    
    localStorage.setItem('last_booking', JSON.stringify(bookingDetails));
    
    // Show success animation
    setTimeout(() => {
        const successIcon = document.querySelector('.success-icon');
        if (successIcon) {
            successIcon.style.animation = 'bounceIn 0.6s ease-out';
        }
    }, 100);
    
    // Track booking completion event (for analytics)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'booking_completed', {
            'booking_id': '{{ $booking->booking_number }}',
            'hotel_id': {{ $booking->hotel->id }},
            'total_value': {{ $booking->total_amount }},
            'currency': 'USD'
        });
    }
});
</script>
@endpush