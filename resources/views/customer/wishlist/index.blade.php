@extends('layouts.app')

@section('title', 'My Wishlist - Trivelo')

@section('content')
<div class="container-fluid px-4">
    @php
        $themeConfig = config('theme.' . $theme, config('theme.classic'));
    @endphp

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold" style="color: {{ $themeConfig['colors']['primary'] }};">
                        <i class="bi bi-heart-fill me-2"></i>My Wishlist
                    </h2>
                    <p class="text-muted mb-0">{{ $favorites->total() }} favorite hotels</p>
                </div>
            </div>
        </div>
    </div>

    @if($favorites->count() > 0)
        <!-- Hotels Grid -->
        <div class="row g-4 mb-5">
            @foreach($favorites as $hotel)
                <div class="col-lg-4 col-md-6" data-hotel-id="{{ $hotel->id }}">
                    <div class="card h-100 shadow-sm border-0 hotel-card" style="transition: all 0.3s ease;">
                        <!-- Hotel Image -->
                        <div class="position-relative">
                            @if($hotel->images->count() > 0)
                                <img src="{{ $hotel->images->first()->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $hotel->name }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                     style="height: 250px;">
                                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <!-- Remove from Wishlist Button -->
                            <button type="button" 
                                    class="btn btn-sm position-absolute top-0 end-0 m-2 wishlist-btn"
                                    style="background: rgba(255, 255, 255, 0.9); border: none; color: #dc3545;"
                                    onclick="removeFromWishlist({{ $hotel->id }})"
                                    title="Remove from wishlist">
                                <i class="bi bi-heart-fill"></i>
                            </button>

                            <!-- Rating Badge -->
                            @if($hotel->average_rating)
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    <span class="badge text-white px-2 py-1" 
                                          style="background: {{ $themeConfig['colors']['primary'] }};">
                                        <i class="bi bi-star-fill me-1"></i>
                                        {{ number_format($hotel->average_rating, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Hotel Details -->
                        <div class="card-body">
                            <h5 class="card-title mb-2 fw-bold">{{ $hotel->name }}</h5>
                            
                            @if($hotel->location)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $hotel->location->city }}, {{ $hotel->location->state }}
                                </p>
                            @endif

                            <p class="card-text text-muted small">
                                {{ Str::limit($hotel->description, 100) }}
                            </p>

                            <!-- Amenities -->
                            @if($hotel->amenities)
                                <div class="mb-3">
                                    @php
                                        $amenities = is_string($hotel->amenities) ? json_decode($hotel->amenities, true) : $hotel->amenities;
                                        $displayAmenities = array_slice($amenities ?? [], 0, 3);
                                    @endphp
                                    @foreach($displayAmenities as $amenity)
                                        <span class="badge bg-light text-dark border me-1 mb-1">{{ $amenity }}</span>
                                    @endforeach
                                    @if(count($amenities ?? []) > 3)
                                        <span class="badge bg-light text-dark border">+{{ count($amenities) - 3 }} more</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Price Range -->
                            @if($hotel->rooms->count() > 0)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="price-info">
                                        <span class="text-muted small">From</span>
                                        <span class="fw-bold" style="color: {{ $themeConfig['colors']['primary'] }};">
                                            ${{ number_format($hotel->rooms->min('price_per_night'), 0) }}
                                        </span>
                                        <span class="text-muted small">/night</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('hotels.show', $hotel) }}" 
                                   class="btn text-white"
                                   style="background: {{ $themeConfig['colors']['primary'] }};">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($favorites->hasPages())
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Wishlist pagination">
                        {{ $favorites->links() }}
                    </nav>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="py-5">
                    <i class="bi bi-heart display-1 text-muted mb-3"></i>
                    <h3 class="mb-3">Your Wishlist is Empty</h3>
                    <p class="text-muted mb-4">
                        Start adding hotels to your wishlist to keep track of places you'd love to visit.
                    </p>
                    <a href="{{ route('hotels.index') }}" 
                       class="btn btn-lg text-white"
                       style="background: {{ $themeConfig['colors']['primary'] }};">
                        <i class="bi bi-search me-2"></i>Explore Hotels
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .hotel-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .wishlist-btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .wishlist-btn:hover {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
function removeFromWishlist(hotelId) {
    if (!confirm('Are you sure you want to remove this hotel from your wishlist?')) {
        return;
    }

    fetch('{{ route("customer.wishlist.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            hotel_id: hotelId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the hotel card from the page
            const hotelCard = document.querySelector(`[data-hotel-id="${hotelId}"]`);
            if (hotelCard) {
                hotelCard.style.transition = 'all 0.3s ease';
                hotelCard.style.opacity = '0';
                hotelCard.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    hotelCard.remove();
                    // If this was the last hotel, reload the page to show empty state
                    if (document.querySelectorAll('[data-hotel-id]').length === 0) {
                        location.reload();
                    }
                }, 300);
            }

            // Show success message
            showAlert('success', data.message);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Something went wrong. Please try again.');
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000);
}
</script>
@endpush
@endsection