@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Booking Details - Trivelo')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">Booking Details</h1>
                    <p class="mb-0 text-white-50">Booking #{{ $booking->booking_number }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('customer.bookings') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Content -->
    <section class="booking-details-content py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Booking Status Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Booking Status</h5>
                                <span class="badge bg-{{ $booking->status_color }} fs-6">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Booking Number</h6>
                                    <p class="mb-3"><strong>{{ $booking->booking_number }}</strong></p>
                                    
                                    <h6 class="text-muted mb-1">Booking Date</h6>
                                    <p class="mb-3">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Total Amount</h6>
                                    <p class="mb-3"><strong class="text-success fs-4">${{ number_format($booking->total_amount, 2) }}</strong></p>
                                    
                                    @if($booking->payment)
                                        <h6 class="text-muted mb-1">Payment Status</h6>
                                        <p class="mb-3">
                                            <span class="badge bg-{{ $booking->payment->status === 'completed' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($booking->payment->status) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-building text-primary"></i> Hotel Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    @if($booking->hotel->main_image)
                                        <img src="{{ $booking->hotel->main_image }}"
                                             class="img-fluid rounded" 
                                             alt="{{ $booking->hotel->name }}">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="bi bi-building text-muted fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h4 class="mb-3">{{ $booking->hotel->name }}</h4>
                                    
                                    <div class="hotel-info">
                                        <p class="mb-2">
                                            <i class="bi bi-geo-alt text-muted me-2"></i>
                                            {{ $booking->hotel->address ?? 'Address not available' }}
                                        </p>
                                        
                                        <p class="mb-2">
                                            <i class="bi bi-geo text-muted me-2"></i>
                                            {{ $booking->hotel->location->city ?? $booking->hotel->city }},
                                            {{ $booking->hotel->location->country ?? 'Country not specified' }}
                                        </p>
                                        
                                        @if($booking->hotel->phone)
                                            <p class="mb-2">
                                                <i class="bi bi-telephone text-muted me-2"></i>
                                                {{ $booking->hotel->phone }}
                                            </p>
                                        @endif
                                        
                                        @if($booking->hotel->email)
                                            <p class="mb-2">
                                                <i class="bi bi-envelope text-muted me-2"></i>
                                                {{ $booking->hotel->email }}
                                            </p>
                                        @endif
                                        
                                        <div class="star-rating mb-2">
                                            @for($i = 0; $i < $booking->hotel->star_rating; $i++)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $booking->hotel->star_rating }}-star hotel)</span>
                                        </div>
                                        
                                        @if($booking->hotel->description)
                                            <p class="text-muted small">{{ Str::limit($booking->hotel->description, 200) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room & Stay Details -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-door-open text-primary"></i> Room & Stay Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Room Type</h6>
                                    <p class="mb-3">{{ $booking->room->formatted_type ?? 'Standard Room' }}</p>
                                    
                                    <h6 class="text-muted mb-2">Guests</h6>
                                    <p class="mb-3">{{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}</p>
                                    
                                    @if($booking->special_requests)
                                        <h6 class="text-muted mb-2">Special Requests</h6>
                                        <p class="mb-3">{{ $booking->special_requests }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="stay-dates bg-light rounded p-3">
                                        <div class="row text-center">
                                            <div class="col-6 border-end">
                                                <small class="text-muted d-block">Check-in</small>
                                                <h5 class="mb-1">{{ $booking->check_in_date->format('d') }}</h5>
                                                <small>{{ $booking->check_in_date->format('M Y') }}</small><br>
                                                <small class="text-muted">{{ $booking->check_in_date->format('l') }}</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Check-out</small>
                                                <h5 class="mb-1">{{ $booking->check_out_date->format('d') }}</h5>
                                                <small>{{ $booking->check_out_date->format('M Y') }}</small><br>
                                                <small class="text-muted">{{ $booking->check_out_date->format('l') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3 pt-3 border-top">
                                            <strong>{{ $booking->nights }} Night{{ $booking->nights > 1 ? 's' : '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-person text-primary"></i> Guest Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Name</h6>
                                    <p class="mb-3">{{ $booking->user->name }}</p>
                                    
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="mb-3">{{ $booking->user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    @if($booking->user->phone)
                                        <h6 class="text-muted mb-1">Phone</h6>
                                        <p class="mb-3">{{ $booking->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-lightning text-warning"></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($booking->can_cancel)
                                    <button class="btn btn-outline-danger" onclick="cancelBooking('{{ $booking->id }}')">
                                        <i class="bi bi-x-circle"></i> Cancel Booking
                                    </button>
                                @endif
                                
                                <button class="btn btn-outline-primary" onclick="downloadBookingPDF('{{ $booking->id }}')">
                                    <i class="bi bi-download"></i> Download Invoice
                                </button>
                                
                                @if($booking->status === 'completed' && !$booking->review)
                                    <a href="{{ route('customer.reviews.create', $booking) }}" class="btn btn-outline-success">
                                        <i class="bi bi-star"></i> Write Review
                                    </a>
                                @endif
                                
                                <a href="mailto:support@trivelo.com?subject=Booking Inquiry - {{ $booking->booking_number }}" class="btn btn-outline-info">
                                    <i class="bi bi-headset"></i> Contact Support
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Booking Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Booking Confirmed</h6>
                                        <small class="text-muted">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</small>
                                    </div>
                                </div>
                                
                                @if($booking->payment && $booking->payment->status === 'completed')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Payment Completed</h6>
                                            <small class="text-muted">{{ $booking->payment->updated_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($booking->status === 'cancelled')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">Booking Cancelled</h6>
                                            <small class="text-muted">{{ $booking->updated_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                @else
                                    @if($booking->check_in_date->isFuture())
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-warning"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Check-in</h6>
                                                <small class="text-muted">{{ $booking->check_in_date->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($booking->check_out_date->isFuture())
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Check-out</h6>
                                                <small class="text-muted">{{ $booking->check_out_date->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Help & Support -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-info-circle text-info"></i> Need Help?</h6>
                        </div>
                        <div class="card-body">
                            <div class="help-items">
                                <div class="help-item mb-3">
                                    <i class="bi bi-telephone text-primary"></i>
                                    <div class="ms-3">
                                        <strong>Call Us</strong><br>
                                        <small class="text-muted">+1 (555) 123-4567</small><br>
                                        <small class="text-muted">24/7 Support</small>
                                    </div>
                                </div>
                                <div class="help-item mb-3">
                                    <i class="bi bi-envelope text-primary"></i>
                                    <div class="ms-3">
                                        <strong>Email Support</strong><br>
                                        <small class="text-muted">support@trivelo.com</small><br>
                                        <small class="text-muted">Response within 4 hours</small>
                                    </div>
                                </div>
                                <div class="help-item">
                                    <i class="bi bi-chat-dots text-primary"></i>
                                    <div class="ms-3">
                                        <strong>Live Chat</strong><br>
                                        <small class="text-muted">Available 9 AM - 9 PM</small>
                                    </div>
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
    .page-header {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
    }

    .stay-dates {
        border: 2px solid #e9ecef;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-content h6 {
        font-size: 0.9rem;
        color: #2d3748;
    }

    .help-item {
        display: flex;
        align-items: flex-start;
    }

    .help-item i {
        font-size: 1.25rem;
        margin-top: 2px;
    }

    .card {
        transition: box-shadow 0.15s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    @media (max-width: 768px) {
        .stay-dates .row > div {
            margin-bottom: 1rem;
        }
        
        .timeline {
            padding-left: 1.5rem;
        }
        
        .timeline-marker {
            left: -1.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
        // Show loading state
        const cancelBtn = event.target;
        const originalText = cancelBtn.innerHTML;
        cancelBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Cancelling...';
        cancelBtn.disabled = true;
        
        // Redirect to cancel route
        window.location.href = `/booking/${bookingId}/cancel`;
    }
}

function downloadBookingPDF(bookingId) {
    // Show loading state
    const downloadBtn = event.target;
    const originalText = downloadBtn.innerHTML;
    downloadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Downloading...';
    downloadBtn.disabled = true;
    
    // Create download link
    const link = document.createElement('a');
    link.href = `/customer/bookings/${bookingId}/invoice`;
    link.download = `booking-${bookingId}-invoice.pdf`;
    link.click();
    
    // Reset button state
    setTimeout(() => {
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }, 2000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to action buttons
    const actionButtons = document.querySelectorAll('.btn');
    actionButtons.forEach(button => {
        if (button.href && !button.onclick && !button.href.includes('#')) {
            button.addEventListener('click', function() {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + this.innerHTML;
                this.disabled = true;
            });
        }
    });

    // Animate cards on load
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush