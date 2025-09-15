@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'My Bookings - Trivelo')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">My Bookings</h1>
                    <p class="mb-0 text-white-50">View and manage your hotel reservations</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters and Stats -->
    <section class="booking-filters py-4 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="booking-stats d-flex gap-4">
                        <div class="stat-item">
                            <span class="badge bg-primary">{{ $stats['total'] }}</span>
                            <small class="text-muted ms-1">Total</small>
                        </div>
                        <div class="stat-item">
                            <span class="badge bg-warning">{{ $stats['upcoming'] }}</span>
                            <small class="text-muted ms-1">Upcoming</small>
                        </div>
                        <div class="stat-item">
                            <span class="badge bg-success">{{ $stats['completed'] }}</span>
                            <small class="text-muted ms-1">Completed</small>
                        </div>
                        <div class="stat-item">
                            <span class="badge bg-danger">{{ $stats['cancelled'] }}</span>
                            <small class="text-muted ms-1">Cancelled</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Bookings</option>
                            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="check_in" {{ request('sort') === 'check_in' ? 'selected' : '' }}>Check-in Date</option>
                            <option value="amount" {{ request('sort') === 'amount' ? 'selected' : '' }}>Amount</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Bookings List -->
    <section class="bookings-content py-5">
        <div class="container">
            @if($bookings->count() > 0)
                <div class="row">
                    @foreach($bookings as $booking)
                        <div class="col-12 mb-4">
                            <div class="booking-card card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Hotel Image -->
                                        <div class="col-md-2">
                                            @if($booking->hotel->main_image)
                                                <img src="{{ $booking->hotel->main_image }}"
                                                     class="img-fluid rounded" 
                                                     alt="{{ $booking->hotel->name }}"
                                                     style="height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                    <i class="bi bi-building text-muted fs-2"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Hotel Details -->
                                        <div class="col-md-4">
                                            <h5 class="mb-1">{{ $booking->hotel->name }}</h5>
                                            <p class="text-muted mb-1">
                                                <i class="bi bi-geo-alt"></i> {{ $booking->hotel->location->city ?? $booking->hotel->city }}
                                            </p>
                                            <p class="text-muted small mb-0">
                                                {{ $booking->room->formatted_type ?? 'Room' }} • {{ $booking->guests }} Guest{{ $booking->guests > 1 ? 's' : '' }}
                                            </p>
                                            <p class="text-muted small mb-0">
                                                Booking #{{ $booking->booking_number }}
                                            </p>
                                        </div>

                                        <!-- Dates -->
                                        <div class="col-md-3">
                                            <div class="booking-dates">
                                                <div class="row text-center">
                                                    <div class="col-6 border-end">
                                                        <small class="text-muted d-block">Check-in</small>
                                                        <strong>{{ $booking->check_in_date->format('M d, Y') }}</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Check-out</small>
                                                        <strong>{{ $booking->check_out_date->format('M d, Y') }}</strong>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-2">
                                                    <small class="text-muted">{{ $booking->nights }} Night{{ $booking->nights > 1 ? 's' : '' }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status & Amount -->
                                        <div class="col-md-2">
                                            <div class="text-center">
                                                <span class="badge bg-{{ $booking->status_color }} mb-2">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <h5 class="text-success mb-1">${{ number_format($booking->total_amount, 2) }}</h5>
                                                <small class="text-muted">Total Amount</small>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-1">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('customer.booking.show', $booking) }}">
                                                            <i class="bi bi-eye me-2"></i> View Details
                                                        </a>
                                                    </li>
                                                    @if($booking->can_cancel)
                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                               href="{{ route('booking.cancel', $booking) }}"
                                                               onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                                <i class="bi bi-x-circle me-2"></i> Cancel Booking
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($booking->status === 'completed' && !$booking->review)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('customer.reviews.create', $booking) }}">
                                                                <i class="bi bi-star me-2"></i> Write Review
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="downloadBookingPDF('{{ $booking->id }}')">
                                                            <i class="bi bi-download me-2"></i> Download Invoice
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Info Row -->
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-plus me-1"></i> Booked on {{ $booking->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            @if($booking->payment && $booking->payment->status === 'completed')
                                                <small class="text-success">
                                                    <i class="bi bi-check-circle me-1"></i> Payment Completed
                                                </small>
                                            @elseif($booking->payment && $booking->payment->status === 'pending')
                                                <small class="text-warning">
                                                    <i class="bi bi-clock me-1"></i> Payment Pending
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state text-center py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <i class="bi bi-calendar-x text-muted mb-4" style="font-size: 4rem;"></i>
                            <h3 class="text-muted mb-3">
                                @if(request('status'))
                                    No {{ request('status') }} bookings found
                                @else
                                    No Bookings Yet
                                @endif
                            </h3>
                            <p class="text-muted mb-4">
                                @if(request('status'))
                                    You don't have any {{ request('status') }} bookings at the moment.
                                @else
                                    Start your journey by finding the perfect hotel for your next trip.
                                @endif
                            </p>
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search Hotels
                                </a>
                                @if(request('status'))
                                    <a href="{{ route('customer.bookings') }}" class="btn btn-outline-secondary">
                                        View All Bookings
                                    </a>
                                @endif
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

    .booking-card {
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .booking-dates {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        background: #f8f9fa;
    }

    .stat-item {
        display: flex;
        align-items: center;
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
        .booking-card .row > div {
            margin-bottom: 1rem;
        }
        
        .booking-dates {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButtons = this.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(button => {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + button.innerHTML;
            });
        });
    });

    // Booking card animations
    const bookingCards = document.querySelectorAll('.booking-card');
    bookingCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Download booking PDF function
function downloadBookingPDF(bookingId) {
    // Create a temporary link to download the PDF
    const link = document.createElement('a');
    link.href = '/customer/bookings/' + bookingId + '/invoice';
    link.download = 'booking-' + bookingId + '-invoice.pdf';
    link.click();
}

// Auto-refresh for upcoming bookings
if (window.location.search.includes('status=upcoming')) {
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            // Could add AJAX call to refresh upcoming bookings
            console.log('Could refresh upcoming bookings here');
        }
    }, 60000); // Refresh every minute for upcoming bookings
}
</script>
@endpush