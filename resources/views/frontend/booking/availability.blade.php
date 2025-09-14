@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Room Availability - ' . $hotel->name . ' - Trivelo')

@section('content')
    <!-- Booking Header -->
    <section class="booking-header bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('hotel.details', $hotel->id) }}">{{ $hotel->name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Book Now</li>
                        </ol>
                    </nav>
                    
                    <!-- Booking Info -->
                    <div class="row">
                        <div class="col-lg-8">
                            <h2>Available Rooms</h2>
                            <p class="text-muted mb-0">{{ $hotel->name }} • {{ $hotel->location->city ?? $hotel->city }}</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <div class="booking-summary">
                                <strong>{{ $checkIn->format('M d, Y') }} - {{ $checkOut->format('M d, Y') }}</strong><br>
                                <span class="text-muted">{{ $nights }} night{{ $nights != 1 ? 's' : '' }} • {{ $searchParams['guests'] }} guest{{ $searchParams['guests'] != 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Rooms -->
    <section class="available-rooms py-5">
        <div class="container">
            @if($roomsWithPricing->count() > 0)
                <div class="row g-4">
                    @foreach($roomsWithPricing as $room)
                        <div class="col-12">
                            <div class="card border shadow-sm room-availability-card">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Room Image -->
                                        <div class="col-lg-3 mb-3 mb-lg-0">
                                            <img src="{{ $room->main_image }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $room->type }}"
                                                 style="height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                        
                                        <!-- Room Details -->
                                        <div class="col-lg-6 mb-3 mb-lg-0">
                                            <div class="room-details">
                                                <h4 class="mb-2">{{ $room->formatted_type }}</h4>
                                                <p class="text-muted mb-3">{{ Str::limit($room->description, 120) }}</p>
                                                
                                                <!-- Room Features -->
                                                <div class="room-features mb-3">
                                                    <div class="row g-2">
                                                        <div class="col-auto">
                                                            <span class="badge bg-light text-dark">
                                                                <i class="bi bi-people"></i> {{ $room->capacity }} guests
                                                            </span>
                                                        </div>
                                                        @if($room->bed_type)
                                                            <div class="col-auto">
                                                                <span class="badge bg-light text-dark">
                                                                    <i class="bi bi-house"></i> {{ $room->formatted_bed_type }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if($room->size_sqft)
                                                            <div class="col-auto">
                                                                <span class="badge bg-light text-dark">
                                                                    <i class="bi bi-arrows-angle-expand"></i> {{ $room->formatted_size }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if($room->is_accessible)
                                                            <div class="col-auto">
                                                                <span class="badge bg-info text-white">
                                                                    <i class="bi bi-universal-access"></i> Accessible
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if(!$room->is_smoking)
                                                            <div class="col-auto">
                                                                <span class="badge bg-success text-white">
                                                                    <i class="bi bi-slash-circle"></i> Non-smoking
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Room Amenities -->
                                                @if($room->amenities && $room->amenities->count() > 0)
                                                    <div class="room-amenities">
                                                        <strong class="small">Amenities:</strong>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @foreach($room->amenities->take(4) as $amenity)
                                                                <span class="badge bg-outline-primary">{{ $amenity->name }}</span>
                                                            @endforeach
                                                            @if($room->amenities->count() > 4)
                                                                <span class="badge bg-outline-secondary">+{{ $room->amenities->count() - 4 }} more</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Pricing & Booking -->
                                        <div class="col-lg-3">
                                            <div class="pricing-section text-lg-end">
                                                <div class="price-breakdown mb-3">
                                                    <div class="avg-price mb-1">
                                                        <small class="text-muted">Avg per night</small><br>
                                                        <span class="h5 text-primary">${{ number_format($room->avg_price_per_night, 2) }}</span>
                                                    </div>
                                                    
                                                    <div class="total-price mb-3">
                                                        <hr class="my-2">
                                                        <strong class="text-success">
                                                            Total: ${{ number_format($room->total_price, 2) }}
                                                        </strong><br>
                                                        <small class="text-muted">for {{ $nights }} night{{ $nights != 1 ? 's' : '' }}</small>
                                                    </div>
                                                </div>
                                                
                                                <!-- Book Now Button -->
                                                <form action="{{ route('booking.form') }}" method="GET">
                                                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                    <input type="hidden" name="check_in" value="{{ $searchParams['check_in'] }}">
                                                    <input type="hidden" name="check_out" value="{{ $searchParams['check_out'] }}">
                                                    <input type="hidden" name="guests" value="{{ $searchParams['guests'] }}">
                                                    
                                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                                                        Book This Room
                                                    </button>
                                                </form>
                                                
                                                <div class="text-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-shield-check"></i>
                                                        Free cancellation
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- No Rooms Available -->
                <div class="row">
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="mb-3">No Rooms Available</h3>
                            <p class="text-muted mb-4">
                                Sorry, no rooms are available for your selected dates and guest count.
                                <br>Please try different dates or adjust your requirements.
                            </p>
                            
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('hotel.details', $hotel->id) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left"></i> Back to Hotel
                                </a>
                                <a href="{{ route('search') }}" class="btn btn-primary">
                                    Search Other Hotels
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Booking Policies -->
    <section class="booking-policies py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0">
                        <div class="card-body">
                            <h5 class="mb-3">Important Booking Information</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bi bi-calendar-check text-primary me-3 mt-1"></i>
                                        <div>
                                            <h6>Free Cancellation</h6>
                                            <small class="text-muted">Cancel up to 24 hours before check-in for a full refund</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bi bi-clock text-primary me-3 mt-1"></i>
                                        <div>
                                            <h6>Check-in: 3:00 PM</h6>
                                            <small class="text-muted">Check-out: 11:00 AM</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bi bi-credit-card text-primary me-3 mt-1"></i>
                                        <div>
                                            <h6>Secure Payment</h6>
                                            <small class="text-muted">Your payment information is encrypted and secure</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bi bi-telephone text-primary me-3 mt-1"></i>
                                        <div>
                                            <h6>24/7 Support</h6>
                                            <small class="text-muted">Get help anytime with our customer support team</small>
                                        </div>
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
    .room-availability-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .room-availability-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .booking-summary {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
    
    @media (min-width: 992px) {
        .booking-summary {
            margin-top: 0;
        }
    }
    
    .price-breakdown {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }
    
    .badge.bg-outline-primary {
        background-color: transparent !important;
        color: #0d6efd;
        border: 1px solid #0d6efd;
    }
    
    .badge.bg-outline-secondary {
        background-color: transparent !important;
        color: #6c757d;
        border: 1px solid #6c757d;
    }
</style>
@endpush