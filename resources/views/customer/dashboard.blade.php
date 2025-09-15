@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'My Dashboard - Trivelo')

@section('content')
    <!-- Dashboard Header -->
    <section class="dashboard-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">Welcome back, {{ $user->name }}!</h1>
                    <p class="mb-0 text-white-50">Manage your bookings, profile, and travel preferences</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('home') }}" class="btn btn-outline-light">
                        <i class="bi bi-search"></i> Search Hotels
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Stats -->
    <section class="dashboard-stats py-4 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $bookingStats['total_bookings'] }}</h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $bookingStats['upcoming_bookings'] }}</h3>
                            <p>Upcoming Trips</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $bookingStats['completed_bookings'] }}</h3>
                            <p>Completed Stays</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-icon bg-danger">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $bookingStats['cancelled_bookings'] }}</h3>
                            <p>Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dashboard Content -->
    <section class="dashboard-content py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Upcoming Bookings -->
                    @if($upcomingBookings && $upcomingBookings->count() > 0)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-calendar-event text-primary"></i> Upcoming Trips</h5>
                                <a href="{{ route('customer.bookings', ['status' => 'upcoming']) }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                @foreach($upcomingBookings as $booking)
                                    <div class="booking-item border-bottom p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                @if($booking->hotel->main_image)
                                                    <img src="{{ $booking->hotel->main_image }}" 
                                                         class="img-fluid rounded" 
                                                         alt="{{ $booking->hotel->name }}"
                                                         style="height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px;">
                                                        <i class="bi bi-building text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="mb-1">{{ $booking->hotel->name }}</h6>
                                                <p class="text-muted small mb-1">
                                                    <i class="bi bi-geo-alt"></i> {{ $booking->hotel->location->city ?? $booking->hotel->city }}
                                                </p>
                                                <p class="text-muted small mb-0">
                                                    {{ $booking->room->formatted_type ?? 'Room' }}
                                                </p>
                                            </div>
                                            <div class="col-md-2">
                                                <small class="text-muted">Check-in</small><br>
                                                <strong>{{ $booking->check_in_date->format('M d, Y') }}</strong>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <a href="{{ route('customer.booking.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Recent Bookings -->
                    @if($recentBookings && $recentBookings->count() > 0)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Recent Bookings</h5>
                                <a href="{{ route('customer.bookings') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                            <div class="card-body p-0">
                                @foreach($recentBookings as $booking)
                                    <div class="booking-item border-bottom p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <span class="badge bg-{{ $booking->status_color }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-5">
                                                <h6 class="mb-1">{{ $booking->hotel->name }}</h6>
                                                <p class="text-muted small mb-0">
                                                    Booking #{{ $booking->booking_number }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ $booking->formatted_dates }}</small>
                                            </div>
                                            <div class="col-md-2">
                                                <strong class="text-success">${{ number_format($booking->total_amount, 2) }}</strong>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <a href="{{ route('customer.booking.show', $booking) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No Bookings State -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-calendar-x text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mb-3">No Bookings Yet</h5>
                                <p class="text-muted mb-4">Start your journey by finding the perfect hotel for your next trip.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search Hotels
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-lightning text-warning"></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i> Search Hotels
                                </a>
                                <a href="{{ route('customer.bookings') }}" class="btn btn-outline-info">
                                    <i class="bi bi-calendar-check"></i> View All Bookings
                                </a>
                                <a href="{{ route('customer.profile') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-person-gear"></i> Update Profile
                                </a>
                                <a href="{{ route('customer.reviews') }}" class="btn btn-outline-success">
                                    <i class="bi bi-star"></i> My Reviews
                                </a>
                                <a href="{{ route('customer.wishlist') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-heart"></i> My Wishlist
                                </a>
                                <a href="{{ route('customer.notifications') }}" class="btn btn-outline-info">
                                    <i class="bi bi-bell"></i> Notifications
                                    @if($user->unread_notifications_count > 0)
                                        <span class="badge bg-danger ms-1">{{ $user->unread_notifications_count }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Summary -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-person-circle text-primary"></i> Profile Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="profile-info">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="profile-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                                
                                <div class="profile-stats">
                                    <div class="row text-center">
                                        <div class="col-6 border-end">
                                            <div class="stat-value">{{ $bookingStats['total_bookings'] }}</div>
                                            <div class="stat-label">Trips</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="stat-value">{{ $user->reviews->count() }}</div>
                                            <div class="stat-label">Reviews</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite Hotels -->
                    @if($favoriteHotels && $favoriteHotels->count() > 0)
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white">
                                <h6 class="mb-0"><i class="bi bi-heart-fill text-danger"></i> Your Favorite Hotels</h6>
                            </div>
                            <div class="card-body p-0">
                                @foreach($favoriteHotels as $hotel)
                                    <div class="favorite-hotel-item border-bottom p-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $hotel->main_image }}" 
                                                 class="me-3 rounded" 
                                                 alt="{{ $hotel->name }}"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ Str::limit($hotel->name, 20) }}</h6>
                                                <small class="text-muted">{{ $hotel->city }}</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="star-rating">
                                                    @for($i = 0; $i < $hotel->star_rating; $i++)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tips & Support -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-info-circle text-info"></i> Tips & Support</h6>
                        </div>
                        <div class="card-body">
                            <div class="tips-list">
                                <div class="tip-item mb-3">
                                    <i class="bi bi-lightbulb text-warning"></i>
                                    <small class="ms-2">Book in advance for better rates</small>
                                </div>
                                <div class="tip-item mb-3">
                                    <i class="bi bi-shield-check text-success"></i>
                                    <small class="ms-2">All bookings are protected</small>
                                </div>
                                <div class="tip-item mb-3">
                                    <i class="bi bi-headset text-primary"></i>
                                    <small class="ms-2">24/7 customer support available</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-sm btn-outline-info w-100">
                                    Contact Support
                                </a>
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
    .dashboard-header {
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

    .booking-item:last-child {
        border-bottom: none !important;
    }

    .booking-item:hover {
        background-color: #f8f9fa;
    }

    .profile-avatar {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
    }

    .favorite-hotel-item:last-child {
        border-bottom: none !important;
    }

    .tip-item {
        display: flex;
        align-items: center;
    }

    .card {
        border: none;
        transition: box-shadow 0.15s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to action buttons
    const actionButtons = document.querySelectorAll('.btn');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.href && !this.href.includes('#')) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + this.innerHTML;
                this.disabled = true;
            }
        });
    });

    // Auto-refresh stats every 30 seconds (for real-time updates)
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            // Could add AJAX call to refresh stats
            console.log('Dashboard stats could be refreshed here');
        }
    }, 30000);

    // Welcome animation
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
</script>
@endpush