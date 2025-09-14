@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Search Hotels - Trivelo')

@section('content')
    <!-- Search Results Header -->
    <section class="search-header bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Refined Search Widget -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-3">
                            <form action="{{ route('search') }}" method="GET" id="refineSearchForm">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="destination" 
                                                   value="{{ request('destination') }}"
                                                   placeholder="Destination">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="check_in" 
                                                   value="{{ request('check_in') }}"
                                                   min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="check_out" 
                                                   value="{{ request('check_out') }}"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <select class="form-select form-select-sm" name="guests">
                                            @for($i = 1; $i <= 6; $i++)
                                                <option value="{{ $i }}" {{ request('guests', 2) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Guest{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <select class="form-select form-select-sm" name="rooms">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ request('rooms', 1) == $i ? 'selected' : '' }}>
                                                    {{ $i }} Room{{ $i > 1 ? 's' : '' }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Search Results Summary -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1">Search Results</h4>
                            <p class="text-muted mb-0">
                                {{ $hotels->total() }} hotel{{ $hotels->total() != 1 ? 's' : '' }} found
                                @if(request('destination'))
                                    in <strong>{{ request('destination') }}</strong>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="d-flex align-items-center gap-3">
                            <label class="form-label mb-0 small">Sort by:</label>
                            <select class="form-select form-select-sm" style="width: auto;" onchange="updateSort(this.value)">
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Guest Rating</option>
                                <option value="star_rating" {{ request('sort') == 'star_rating' ? 'selected' : '' }}>Star Rating</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Hotel Name</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="search-results py-4">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Results</h6>
                        </div>
                        <div class="card-body">
                            <!-- Price Range Filter -->
                            <div class="mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-2">Price Range</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" placeholder="Min" name="min_price" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" placeholder="Max" name="max_price" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Star Rating Filter -->
                            <div class="mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-2">Star Rating</h6>
                                @for($star = 5; $star >= 1; $star--)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="star_rating[]" value="{{ $star }}" id="star{{ $star }}"
                                               {{ in_array($star, request('star_rating', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="star{{ $star }}">
                                            @for($i = 0; $i < $star; $i++)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @endfor
                                            @for($i = $star; $i < 5; $i++)
                                                <i class="bi bi-star text-muted"></i>
                                            @endfor
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Guest Rating Filter -->
                            <div class="mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-2">Guest Rating</h6>
                                @foreach([4.5, 4.0, 3.5, 3.0] as $rating)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="guest_rating[]" value="{{ $rating }}" id="rating{{ $rating }}"
                                               {{ in_array($rating, request('guest_rating', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rating{{ $rating }}">
                                            {{ $rating }}+ <small class="text-muted">& above</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Amenities Filter -->
                            <div class="mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-2">Popular Amenities</h6>
                                @php
                                    $commonAmenities = [
                                        'wifi' => 'Free WiFi',
                                        'parking' => 'Free Parking',
                                        'pool' => 'Swimming Pool',
                                        'gym' => 'Fitness Center',
                                        'spa' => 'Spa',
                                        'restaurant' => 'Restaurant',
                                        'bar' => 'Bar/Lounge',
                                        'room_service' => 'Room Service',
                                        'pets' => 'Pet Friendly',
                                        'airport_shuttle' => 'Airport Shuttle'
                                    ];
                                @endphp
                                @foreach($commonAmenities as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $key }}" id="amenity{{ $key }}"
                                               {{ in_array($key, request('amenities', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="amenity{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Filter Actions -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                                    Apply Filters
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                    Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hotel Results -->
                <div class="col-lg-9">
                    @if($hotels->count() > 0)
                        <div class="row g-4">
                            @foreach($hotels as $hotel)
                                <div class="col-12">
                                    <div class="card h-100 shadow-sm border-0 hotel-result-card">
                                        <div class="row g-0">
                                            <!-- Hotel Image -->
                                            <div class="col-md-4">
                                                <div class="position-relative h-100">
                                                    <img src="{{ $hotel->main_image }}" 
                                                         class="card-img hotel-result-image" 
                                                         alt="{{ $hotel->name }}"
                                                         style="height: 250px; object-fit: cover;">
                                                    
                                                    <!-- Hotel Badges -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-primary mb-1">{{ $hotel->star_rating_text }}</span>
                                                        @if($hotel->is_featured)
                                                            <br><span class="badge bg-warning">Featured</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Image Gallery Indicator -->
                                                    @if($hotel->images && count($hotel->images) > 1)
                                                        <div class="position-absolute bottom-0 end-0 m-2">
                                                            <span class="badge bg-dark bg-opacity-75">
                                                                <i class="bi bi-images"></i> {{ count($hotel->images) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Hotel Details -->
                                            <div class="col-md-8">
                                                <div class="card-body d-flex flex-column h-100">
                                                    <div class="flex-grow-1">
                                                        <!-- Hotel Name & Location -->
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <h5 class="card-title mb-1">
                                                                    <a href="{{ route('hotel.details', $hotel->id) }}" class="text-decoration-none">
                                                                        {{ $hotel->name }}
                                                                    </a>
                                                                </h5>
                                                                <p class="text-muted mb-2">
                                                                    <i class="bi bi-geo-alt"></i> 
                                                                    {{ $hotel->location->city ?? $hotel->city }}, {{ $hotel->location->country ?? $hotel->country }}
                                                                </p>
                                                            </div>
                                                            
                                                            <!-- Guest Rating -->
                                                            @if($hotel->average_rating)
                                                                <div class="text-end">
                                                                    <span class="badge bg-success fs-6 mb-1">{{ $hotel->formatted_rating }}</span>
                                                                    <br><small class="text-muted">({{ $hotel->total_reviews ?? 0 }} reviews)</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Hotel Description -->
                                                        <p class="card-text text-muted small mb-3">
                                                            {{ Str::limit($hotel->description, 150) }}
                                                        </p>
                                                        
                                                        <!-- Amenities Preview -->
                                                        @if($hotel->amenities && is_array($hotel->amenities))
                                                            <div class="mb-3">
                                                                @foreach(array_slice($hotel->amenities, 0, 6) as $amenity)
                                                                    <span class="badge bg-light text-dark me-1 mb-1">{{ $amenity }}</span>
                                                                @endforeach
                                                                @if(count($hotel->amenities) > 6)
                                                                    <span class="badge bg-light text-primary">+{{ count($hotel->amenities) - 6 }} more</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Price & Booking -->
                                                    <div class="d-flex justify-content-between align-items-end">
                                                        <div>
                                                            @php
                                                                $priceRange = $hotel->getPriceRange();
                                                            @endphp
                                                            @if($priceRange['min'])
                                                                <div class="price-info">
                                                                    <small class="text-muted">From</small>
                                                                    <span class="h5 text-primary fw-bold">${{ number_format($priceRange['min']) }}</span>
                                                                    <small class="text-muted">/night</small>
                                                                    @if($priceRange['max'] && $priceRange['max'] != $priceRange['min'])
                                                                        <br><small class="text-muted">Up to ${{ number_format($priceRange['max']) }}/night</small>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="text-end">
                                                            <a href="{{ route('hotel.details', $hotel->id) }}" class="btn btn-primary">
                                                                View Details
                                                            </a>
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
                        @if($hotels->hasPages())
                            <div class="d-flex justify-content-center mt-5">
                                {{ $hotels->appends(request()->query())->links() }}
                            </div>
                        @endif
                        
                    @else
                        <!-- No Results -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-search" style="font-size: 3rem; color: #ddd;"></i>
                            </div>
                            <h4 class="mb-3">No Hotels Found</h4>
                            <p class="text-muted mb-4">
                                We couldn't find any hotels matching your criteria. 
                                <br>Try adjusting your filters or search terms.
                            </p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('search') }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left"></i> New Search
                                </a>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                    Clear Filters
                                </button>
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
    .hotel-result-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hotel-result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .hotel-result-image {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        width: 100%;
    }
    
    .price-info {
        line-height: 1.2;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .form-check {
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        margin-top: 0.2rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    @media (max-width: 768px) {
        .hotel-result-image {
            height: 200px;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .col-md-4, .col-md-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort functionality
    window.updateSort = function(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    };
    
    // Filter functionality
    window.applyFilters = function() {
        const form = document.getElementById('refineSearchForm');
        const formData = new FormData(form);
        const url = new URL(window.location);
        
        // Add filter parameters
        const filters = document.querySelectorAll('[name^="star_rating"], [name^="guest_rating"], [name^="amenities"], [name="min_price"], [name="max_price"]');
        filters.forEach(filter => {
            if (filter.type === 'checkbox' && filter.checked) {
                url.searchParams.append(filter.name, filter.value);
            } else if (filter.type === 'number' && filter.value) {
                url.searchParams.set(filter.name, filter.value);
            }
        });
        
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    };
    
    // Clear filters
    window.clearFilters = function() {
        const url = new URL(window.location);
        const keysToKeep = ['destination', 'check_in', 'check_out', 'guests', 'rooms'];
        
        // Remove all parameters except search criteria
        const params = new URLSearchParams();
        keysToKeep.forEach(key => {
            if (url.searchParams.has(key)) {
                params.set(key, url.searchParams.get(key));
            }
        });
        
        url.search = params.toString();
        window.location.href = url.toString();
    };
});
</script>
@endpush