@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', $hotel->name . ' - Hotel Details - Trivelo')

@section('content')
    <!-- Hotel Header -->
    <section class="hotel-header bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('search') }}">Hotels</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $hotel->name }}</li>
                        </ol>
                    </nav>
                    
                    <!-- Hotel Title & Info -->
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-2">
                                <!-- Star Rating -->
                                <div class="me-3">
                                    @for($i = 0; $i < $hotel->star_rating; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                    @for($i = $hotel->star_rating; $i < 5; $i++)
                                        <i class="bi bi-star text-muted"></i>
                                    @endfor
                                </div>
                                @if($hotel->is_featured)
                                    <span class="badge bg-warning text-dark">Featured</span>
                                @endif
                            </div>
                            
                            <h1 class="mb-2">{{ $hotel->name }}</h1>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i>
                                {{ $hotel->location->full_address ?? $hotel->full_address }}
                            </p>
                            
                            @if($hotel->average_rating)
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2">{{ $hotel->formatted_rating }}</span>
                                    <span class="text-muted">Based on {{ $hotel->total_reviews ?? 0 }} review{{ ($hotel->total_reviews ?? 0) != 1 ? 's' : '' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-lg-4 text-lg-end">
                            @php
                                $priceRange = $hotel->getPriceRange();
                            @endphp
                            @if($priceRange['min'])
                                <div class="price-display">
                                    <small class="text-muted">Starting from</small>
                                    <h3 class="text-primary mb-0">${{ number_format($priceRange['min']) }}</h3>
                                    <small class="text-muted">per night</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hotel Images Gallery -->
    <section class="hotel-gallery py-4">
        <div class="container">
            @if($hotel->images && count($hotel->images) > 0)
                <div class="row g-2">
                    <div class="col-lg-8">
                        <!-- Main Image -->
                        <img src="{{ $hotel->images->first()->image_url ?? $hotel->main_image }}" 
                             class="img-fluid rounded shadow-sm main-hotel-image" 
                             alt="{{ $hotel->name }}"
                             style="height: 400px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-2">
                            @foreach($hotel->images->skip(1)->take(4) as $image)
                                <div class="col-6">
                                    <img src="{{ $image->image_url }}" 
                                         class="img-fluid rounded shadow-sm gallery-image" 
                                         alt="{{ $image->alt_text }}"
                                         style="height: 190px; width: 100%; object-fit: cover;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageGalleryModal"
                                         data-image="{{ $image->image_url }}">
                                </div>
                            @endforeach
                            
                            @if($hotel->images->count() > 5)
                                <div class="col-6">
                                    <div class="position-relative">
                                        <img src="{{ $hotel->images[5]->image_url }}" 
                                             class="img-fluid rounded shadow-sm gallery-image" 
                                             alt="More images"
                                             style="height: 190px; width: 100%; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-75 rounded"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageGalleryModal"
                                             style="cursor: pointer;">
                                            <span class="text-white h5">+{{ $hotel->images->count() - 5 }} more</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Hotel Details Content -->
    <section class="hotel-content py-4">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8 mb-4">
                    <!-- Hotel Description -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h4 class="mb-3">About {{ $hotel->name }}</h4>
                            <p class="text-muted">{{ $hotel->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Amenities -->
                    @if($hotel->amenities && (is_array($hotel->amenities) ? count($hotel->amenities) : $hotel->amenities->count()) > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="mb-3">Amenities</h4>
                                <div class="row">
                                    @php
                                        $amenitiesList = is_array($hotel->amenities) ? $hotel->amenities : $hotel->amenities->pluck('name')->toArray();
                                    @endphp
                                    @foreach($amenitiesList as $amenity)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span>{{ $amenity }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Available Rooms -->
                    @if($hotel->activeRooms && $hotel->activeRooms->count() > 0)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="mb-3">Available Rooms</h4>
                                <div class="row g-4">
                                    @foreach($hotel->activeRooms->take(3) as $room)
                                        <div class="col-12">
                                            <div class="room-card p-3 border rounded">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-3">
                                                        @if($room->images && $room->images->first())
                                                            <img src="{{ $room->images->first()->image_url }}" 
                                                                 class="img-fluid rounded" 
                                                                 alt="{{ $room->type }}"
                                                                 style="height: 120px; width: 100%; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                                                <i class="bi bi-house text-muted" style="font-size: 2rem;"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <h5 class="mb-2">{{ $room->type }}</h5>
                                                        <p class="text-muted mb-2">{{ Str::limit($room->description, 100) }}</p>
                                                        <div class="room-features">
                                                            <small class="text-muted">
                                                                <i class="bi bi-people"></i> {{ $room->max_occupancy }} guests
                                                                @if($room->bed_type)
                                                                    • <i class="bi bi-house"></i> {{ $room->bed_type }}
                                                                @endif
                                                                • {{ $room->size_sqm }} m²
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 text-lg-end">
                                                        <div class="price-info mb-2">
                                                            <h5 class="text-primary mb-0">${{ number_format($room->base_price) }}</h5>
                                                            <small class="text-muted">per night</small>
                                                        </div>
                                                        <button class="btn btn-outline-primary btn-sm">
                                                            Select Room
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($hotel->activeRooms->count() > 3)
                                    <div class="text-center mt-3">
                                        <button class="btn btn-outline-primary" onclick="showAllRooms()">
                                            View All {{ $hotel->activeRooms->count() }} Rooms
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Hotel Policies -->
                    @if($hotel->policies)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h4 class="mb-3">Hotel Policies</h4>
                                @php
                                    $policies = is_array($hotel->policies) ? $hotel->policies : json_decode($hotel->policies, true);
                                @endphp
                                @if($policies)
                                    @foreach($policies as $policyType => $policyValue)
                                        <div class="mb-2">
                                            <strong>{{ ucwords(str_replace('_', ' ', $policyType)) }}:</strong>
                                            <span class="text-muted">{{ is_array($policyValue) ? implode(', ', $policyValue) : $policyValue }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Booking Widget -->
                    <div class="card border-0 shadow-sm mb-4 position-sticky" style="top: 100px;">
                        <div class="card-body">
                            <h5 class="mb-3">Book Your Stay</h5>
                            <form action="{{ route('booking.availability') }}" method="GET" id="bookingForm">
                                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label" for="check_in">Check-in</label>
                                    <input type="date" class="form-control" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label" for="check_out">Check-out</label>
                                    <input type="date" class="form-control" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="form-label" for="guests">Guests</label>
                                        <select class="form-select" name="guests" id="guests">
                                            @for($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="rooms_count">Rooms</label>
                                        <select class="form-select" name="rooms" id="rooms_count">
                                            @for($i = 1; $i <= 3; $i++)
                                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    @auth
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Check Availability
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                            Login to Book
                                        </a>
                                    @endauth
                                </div>
                            </form>
                            
                            @auth
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-shield-check"></i>
                                        Secure booking process
                                    </small>
                                </div>
                            @endauth
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Contact Information</h5>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <a href="mailto:{{ $hotel->email }}" class="text-decoration-none">{{ $hotel->email }}</a>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    <a href="tel:{{ $hotel->phone }}" class="text-decoration-none">{{ $hotel->phone }}</a>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt text-primary me-2 mt-1"></i>
                                    <div>
                                        <address class="mb-0">{{ $hotel->location->full_address ?? $hotel->full_address }}</address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Quick Info</h6>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="text-primary mb-1">{{ $hotel->star_rating }}</h6>
                                        <small class="text-muted">Star Rating</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="text-primary mb-1">{{ $hotel->activeRooms->count() ?? 0 }}</h6>
                                        <small class="text-muted">Rooms</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-primary mb-1">{{ $hotel->total_reviews ?? 0 }}</h6>
                                    <small class="text-muted">Reviews</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Similar Hotels -->
    @if($similarHotels->count() > 0)
        <section class="similar-hotels py-5 bg-light">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12">
                        <h3>Similar Hotels</h3>
                        <p class="text-muted">Other great options in {{ $hotel->location->city ?? $hotel->city }}</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    @foreach($similarHotels as $similarHotel)
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 shadow-sm border-0 hotel-card">
                                <div class="position-relative">
                                    <img src="{{ $similarHotel->main_image }}" 
                                         class="card-img-top" 
                                         alt="{{ $similarHotel->name }}"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary">{{ $similarHotel->star_rating_text }}</span>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-2">{{ $similarHotel->name }}</h6>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-geo-alt"></i> 
                                        {{ $similarHotel->location->city ?? $similarHotel->city }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        @if($similarHotel->average_rating)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-success">{{ $similarHotel->formatted_rating }}</span>
                                                <small class="text-muted">({{ $similarHotel->total_reviews ?? 0 }})</small>
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            @php
                                                $similarPrice = $similarHotel->getPriceRange();
                                            @endphp
                                            @if($similarPrice['min'])
                                                <div>
                                                    <span class="h6 text-primary">${{ number_format($similarPrice['min']) }}</span>
                                                    <small class="text-muted">/night</small>
                                                </div>
                                            @endif
                                            <a href="{{ route('hotel.details', $similarHotel->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Image Gallery Modal -->
    <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $hotel->name }} - Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="hotelGalleryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($hotel->images ?? [] as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url ?? $image }}" 
                                         class="d-block w-100" 
                                         alt="{{ $image->alt_text ?? $hotel->name }}"
                                         style="height: 500px; object-fit: contain; background: #f8f9fa;">
                                </div>
                            @endforeach
                        </div>
                        
                        @if(count($hotel->images ?? []) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#hotelGalleryCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#hotelGalleryCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .gallery-image {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .gallery-image:hover {
        transform: scale(1.05);
    }
    
    .main-hotel-image {
        cursor: pointer;
    }
    
    .hotel-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .room-card {
        transition: border-color 0.2s ease;
    }
    
    .room-card:hover {
        border-color: #0d6efd !important;
    }
    
    .price-display {
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .price-display {
            margin-top: 1rem;
            text-align: left;
        }
        
        .position-sticky {
            position: static !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date inputs with default values
    const checkinInput = document.querySelector('input[name="check_in"]');
    const checkoutInput = document.querySelector('input[name="check_out"]');
    
    // Set default dates
    if (!checkinInput.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkinInput.value = tomorrow.toISOString().split('T')[0];
    }
    
    if (!checkoutInput.value) {
        const dayAfterTomorrow = new Date();
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
        checkoutInput.value = dayAfterTomorrow.toISOString().split('T')[0];
    }
    
    // Handle check-in date change
    checkinInput.addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutDate = new Date(checkoutInput.value);
        
        // Set minimum checkout date
        const minCheckout = new Date(checkinDate);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutInput.min = minCheckout.toISOString().split('T')[0];
        
        // Update checkout if it's before new minimum
        if (checkoutDate <= checkinDate) {
            checkoutInput.value = minCheckout.toISOString().split('T')[0];
        }
    });
    
    // Gallery modal functionality
    const galleryImages = document.querySelectorAll('.gallery-image, .main-hotel-image');
    galleryImages.forEach(img => {
        img.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image') || this.src;
            // Find the carousel item with this image and make it active
            const carousel = document.querySelector('#hotelGalleryCarousel');
            const items = carousel.querySelectorAll('.carousel-item');
            
            items.forEach((item, index) => {
                const itemImg = item.querySelector('img');
                if (itemImg.src === imageSrc) {
                    // Remove active class from all items
                    items.forEach(i => i.classList.remove('active'));
                    // Add active class to this item
                    item.classList.add('active');
                }
            });
        });
    });
});

// Show all rooms function
function showAllRooms() {
    // This would typically show a modal or expand the room list
    alert('Show all rooms functionality would be implemented here');
}
</script>
@endpush