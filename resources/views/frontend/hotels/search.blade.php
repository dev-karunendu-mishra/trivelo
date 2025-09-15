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
                                        <div class="input-group input-group-sm position-relative">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="destination" 
                                                   value="{{ request('destination') }}"
                                                   placeholder="Destination"
                                                   id="destinationInput"
                                                   autocomplete="off">
                                            <div id="destinationSuggestions" class="autocomplete-suggestions"></div>
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
                            <!-- View Toggle -->
                            <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                                <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="listView">
                                    <i class="bi bi-list"></i> List
                                </label>
                                
                                <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off">
                                <label class="btn btn-outline-primary" for="gridView">
                                    <i class="bi bi-grid-3x3-gap"></i> Grid
                                </label>
                                
                                <input type="radio" class="btn-check" name="viewMode" id="mapView" autocomplete="off">
                                <label class="btn btn-outline-primary" for="mapView">
                                    <i class="bi bi-map"></i> Map
                                </label>
                            </div>
                            
                            <div class="vr"></div>
                            
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
                    <form id="filtersForm" method="GET" action="{{ route('search') }}">
                        <!-- Preserve search parameters -->
                        @foreach(['destination', 'check_in', 'check_out', 'guests', 'rooms'] as $param)
                            @if(request($param))
                                <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                            @endif
                        @endforeach
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Results</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="realTimeFilter" checked>
                                        <label class="form-check-label small" for="realTimeFilter">
                                            Real-time
                                        </label>
                                    </div>
                                </div>
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
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        Apply Filters
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                        Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Hotel Results -->
                <div class="col-lg-9">
                    <!-- Map View Container -->
                    <div id="mapContainer" class="mb-4" style="display: none;">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-0">
                                <div id="hotelMap" style="height: 500px; border-radius: 0.375rem;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- List View Container -->
                    <div id="listContainer">
                        @if($hotels->count() > 0)
                            <div class="row g-4" id="hotelResults">
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
        </div>
    </section>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
    
    /* Autocomplete Styles */
    .autocomplete-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e9ecef;
        border-top: none;
        border-radius: 0 0 0.375rem 0.375rem;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: none;
    }
    
    .autocomplete-suggestion {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.2s ease;
    }
    
    .autocomplete-suggestion:hover,
    .autocomplete-suggestion.active {
        background-color: #f8f9fa;
    }
    
    .autocomplete-suggestion:last-child {
        border-bottom: none;
    }
    
    .autocomplete-loading {
        padding: 8px 12px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
    }
    
    /* Map Styles */
    .custom-hotel-marker {
        background: none !important;
        border: none !important;
    }
    
    .hotel-marker {
        background: #007bff;
        color: white;
        border: 2px solid white;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        line-height: 26px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .hotel-marker:hover {
        background: #0056b3;
        transform: scale(1.1);
        z-index: 1000;
    }
    
    .marker-price {
        display: block;
        padding: 0 8px;
        white-space: nowrap;
    }
    
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .custom-popup .leaflet-popup-content {
        margin: 12px;
        width: 260px !important;
    }
    
    .hotel-popup .popup-image img {
        border-radius: 4px;
    }
    
    .hotel-popup .star-rating i {
        font-size: 12px;
    }
    
    .hotel-popup .price {
        font-size: 16px;
        color: #007bff;
    }
    
    /* Grid View Styles */
    .grid-view .hotel-result-card {
        height: 100%;
    }
    
    .grid-view .hotel-result-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    
    .grid-view .hotel-result-card .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .grid-view .hotel-result-card .flex-grow-1 {
        flex-grow: 1;
    }
    
    .grid-view .amenities-preview {
        max-height: 60px;
        overflow: hidden;
    }
    
    .grid-view .hotel-description {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map = null;
    let markersGroup = null;
    const hotels = @json($hotels->items());
    
    // View mode toggle functionality
    const listViewBtn = document.getElementById('listView');
    const gridViewBtn = document.getElementById('gridView');
    const mapViewBtn = document.getElementById('mapView');
    const listContainer = document.getElementById('listContainer');
    const mapContainer = document.getElementById('mapContainer');
    
    if (listViewBtn && gridViewBtn && mapViewBtn) {
        listViewBtn.addEventListener('change', function() {
            if (this.checked) {
                switchToListView();
            }
        });
        
        gridViewBtn.addEventListener('change', function() {
            if (this.checked) {
                switchToGridView();
            }
        });
        
        mapViewBtn.addEventListener('change', function() {
            if (this.checked) {
                switchToMapView();
            }
        });
    }
    
    function switchToListView() {
        listContainer.style.display = 'block';
        mapContainer.style.display = 'none';
        listContainer.classList.remove('grid-view');
        localStorage.setItem('hotelSearchView', 'list');
        
        // Update results container layout
        const resultsContainer = document.getElementById('hotelResults');
        if (resultsContainer) {
            resultsContainer.className = 'row g-4';
        }
    }
    
    function switchToGridView() {
        listContainer.style.display = 'block';
        mapContainer.style.display = 'none';
        listContainer.classList.add('grid-view');
        localStorage.setItem('hotelSearchView', 'grid');
        
        // Update results container layout
        const resultsContainer = document.getElementById('hotelResults');
        if (resultsContainer) {
            resultsContainer.className = 'row g-4';
        }
        
        // Re-render hotels in grid format if we have data
        if (window.hotels && window.hotels.length > 0) {
            updateHotelResults(window.hotels);
        }
    }
    
    function switchToMapView() {
        listContainer.style.display = 'none';
        mapContainer.style.display = 'block';
        localStorage.setItem('hotelSearchView', 'map');
        
        // Initialize map if not already done
        if (!map) {
            initializeMap();
        }
    }
    
    function initializeMap() {
        // Default center (can be adjusted based on search location)
        let centerLat = 40.7128;
        let centerLng = -74.0060;
        let zoom = 10;
        
        // Try to center map based on hotels or search location
        if (hotels.length > 0) {
            const validHotels = hotels.filter(hotel => hotel.latitude && hotel.longitude);
            if (validHotels.length > 0) {
                // Calculate center from hotels
                const avgLat = validHotels.reduce((sum, hotel) => sum + parseFloat(hotel.latitude), 0) / validHotels.length;
                const avgLng = validHotels.reduce((sum, hotel) => sum + parseFloat(hotel.longitude), 0) / validHotels.length;
                centerLat = avgLat;
                centerLng = avgLng;
                
                // Adjust zoom based on spread
                const latRange = Math.max(...validHotels.map(h => parseFloat(h.latitude))) - Math.min(...validHotels.map(h => parseFloat(h.latitude)));
                const lngRange = Math.max(...validHotels.map(h => parseFloat(h.longitude))) - Math.min(...validHotels.map(h => parseFloat(h.longitude)));
                const maxRange = Math.max(latRange, lngRange);
                
                if (maxRange < 0.01) zoom = 14;
                else if (maxRange < 0.05) zoom = 12;
                else if (maxRange < 0.1) zoom = 11;
                else if (maxRange < 0.5) zoom = 9;
                else zoom = 8;
            }
        }
        
        map = L.map('hotelMap').setView([centerLat, centerLng], zoom);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Create markers group
        markersGroup = L.layerGroup().addTo(map);
        
        // Add hotel markers
        addHotelMarkers();
    }
    
    function addHotelMarkers() {
        if (!markersGroup) return;
        
        markersGroup.clearLayers();
        
        hotels.forEach(hotel => {
            if (!hotel.latitude || !hotel.longitude) return;
            
            const lat = parseFloat(hotel.latitude);
            const lng = parseFloat(hotel.longitude);
            
            if (isNaN(lat) || isNaN(lng)) return;
            
            // Create custom marker icon
            const markerIcon = L.divIcon({
                html: `<div class="hotel-marker">
                    <span class="marker-price">${hotel.min_price ? '$' + hotel.min_price : 'N/A'}</span>
                </div>`,
                className: 'custom-hotel-marker',
                iconSize: [60, 30],
                iconAnchor: [30, 30]
            });
            
            const marker = L.marker([lat, lng], { icon: markerIcon });
            
            // Create popup content
            const popupContent = `
                <div class="hotel-popup">
                    <div class="popup-image">
                        <img src="${hotel.main_image || '/images/default-hotel.jpg'}" alt="${hotel.name}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 4px;">
                    </div>
                    <div class="popup-content mt-2">
                        <h6 class="mb-1">${hotel.name}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="star-rating">
                                ${generateStarRating(hotel.star_rating)}
                            </div>
                            ${hotel.average_rating ? `<span class="badge bg-success">${hotel.average_rating}</span>` : ''}
                        </div>
                        <p class="text-muted small mb-2">${hotel.city}, ${hotel.country}</p>
                        ${hotel.min_price ? `<div class="price mb-2"><strong>From $${hotel.min_price}/night</strong></div>` : ''}
                        <a href="/hotels/${hotel.id}" class="btn btn-primary btn-sm w-100">View Details</a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent, {
                maxWidth: 280,
                className: 'custom-popup'
            });
            
            markersGroup.addLayer(marker);
        });
    }
    
    function generateStarRating(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= rating ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
        }
        return stars;
    }
    
    // Store hotels data globally for view switching
    window.hotels = hotels;
    
    // Restore saved view preference
    const savedView = localStorage.getItem('hotelSearchView');
    if (savedView === 'map' && hotels.length > 0) {
        mapViewBtn.checked = true;
        switchToMapView();
    } else if (savedView === 'grid') {
        gridViewBtn.checked = true;
        switchToGridView();
    } else {
        listViewBtn.checked = true;
        switchToListView();
    }
    
    // Restore saved sort preference
    const savedSort = localStorage.getItem('hotelSearchSort');
    const sortSelect = document.querySelector('select[onchange="updateSort(this.value)"]');
    if (savedSort && sortSelect) {
        sortSelect.value = savedSort;
    }
    
    // Destination autocomplete functionality
    const destinationInput = document.getElementById('destinationInput');
    const suggestionsContainer = document.getElementById('destinationSuggestions');
    let debounceTimer;
    let selectedIndex = -1;
    let suggestions = [];

    if (destinationInput && suggestionsContainer) {
        destinationInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();
            
            if (query.length < 2) {
                hideSuggestions();
                return;
            }
            
            debounceTimer = setTimeout(() => {
                fetchSuggestions(query);
            }, 300);
        });

        destinationInput.addEventListener('keydown', function(e) {
            if (!suggestionsContainer.style.display || suggestionsContainer.style.display === 'none') {
                return;
            }

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                    updateSelection();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    updateSelection();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                        selectSuggestion(suggestions[selectedIndex]);
                    }
                    break;
                case 'Escape':
                    hideSuggestions();
                    break;
            }
        });

        destinationInput.addEventListener('blur', function() {
            // Delay hiding to allow click on suggestion
            setTimeout(() => {
                hideSuggestions();
            }, 150);
        });

        destinationInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && suggestions.length > 0) {
                showSuggestions();
            }
        });
    }

    function fetchSuggestions(query) {
        showLoading();
        
        fetch(`/api/destinations?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestions = data;
                displaySuggestions(data);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                hideSuggestions();
            });
    }

    function displaySuggestions(suggestions) {
        if (suggestions.length === 0) {
            hideSuggestions();
            return;
        }

        suggestionsContainer.innerHTML = '';
        selectedIndex = -1;

        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-suggestion';
            item.textContent = suggestion.label;
            item.addEventListener('click', () => selectSuggestion(suggestion));
            suggestionsContainer.appendChild(item);
        });

        showSuggestions();
    }

    function selectSuggestion(suggestion) {
        destinationInput.value = suggestion.value;
        hideSuggestions();
        destinationInput.focus();
    }

    function updateSelection() {
        const items = suggestionsContainer.querySelectorAll('.autocomplete-suggestion');
        items.forEach((item, index) => {
            item.classList.toggle('active', index === selectedIndex);
        });
    }

    function showLoading() {
        suggestionsContainer.innerHTML = '<div class="autocomplete-loading">Searching...</div>';
        showSuggestions();
    }

    function showSuggestions() {
        suggestionsContainer.style.display = 'block';
    }

    function hideSuggestions() {
        suggestionsContainer.style.display = 'none';
        selectedIndex = -1;
    }

    // Sort functionality
    window.updateSort = function(sortValue) {
        // Save sort preference
        localStorage.setItem('hotelSearchSort', sortValue);
        
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    };
    
    // Real-time filtering functionality
    let filterTimeout;
    const realTimeCheckbox = document.getElementById('realTimeFilter');
    const filtersForm = document.getElementById('filtersForm');
    
    // Add change listeners to all filter inputs
    if (filtersForm) {
        const filterInputs = filtersForm.querySelectorAll('input, select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (realTimeCheckbox && realTimeCheckbox.checked) {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(() => {
                        applyFiltersAjax();
                    }, 500);
                }
            });
        });
    }
    
    // Filter functionality (form submission vs AJAX)
    window.applyFilters = function() {
        if (realTimeCheckbox && realTimeCheckbox.checked) {
            applyFiltersAjax();
        } else {
            const form = document.getElementById('filtersForm');
            if (form) {
                form.submit();
            }
        }
    };
    
    function applyFiltersAjax() {
        const form = document.getElementById('filtersForm');
        if (!form) return;
        
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);
        
        // Show loading state
        showLoadingState();
        
        fetch(`/api/search?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                updateHotelResults(data.hotels);
                updatePagination(data.pagination);
                updateResultsCount(data.pagination.total);
                updateMapMarkers(data.hotels);
                hideLoadingState();
                
                // Update URL without page refresh
                const newUrl = new URL(window.location);
                for (const [key, value] of searchParams.entries()) {
                    if (key !== '_token') {
                        newUrl.searchParams.set(key, value);
                    }
                }
                window.history.pushState({}, '', newUrl.toString());
            })
            .catch(error => {
                console.error('Error filtering hotels:', error);
                hideLoadingState();
            });
    }
    
    function showLoadingState() {
        const resultsContainer = document.getElementById('hotelResults');
        const loadingHtml = `
            <div class="col-12 text-center py-5" id="loadingIndicator">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Updating results...</p>
            </div>
        `;
        resultsContainer.innerHTML = loadingHtml;
    }
    
    function hideLoadingState() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        if (loadingIndicator) {
            loadingIndicator.remove();
        }
    }
    
    function updateHotelResults(hotels) {
        const resultsContainer = document.getElementById('hotelResults');
        
        if (hotels.length === 0) {
            resultsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-search" style="font-size: 3rem; color: #ddd;"></i>
                    </div>
                    <h4 class="mb-3">No Hotels Found</h4>
                    <p class="text-muted mb-4">
                        We couldn't find any hotels matching your criteria.
                        <br>Try adjusting your filters or search terms.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="/search" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> New Search
                        </a>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            Clear Filters
                        </button>
                    </div>
                </div>
            `;
            return;
        }
    
        resultsContainer.innerHTML = hotels.map(hotel => generateHotelCard(hotel)).join('');
    }
    
    function generateHotelCard(hotel) {
        const priceText = hotel.min_price ? `From $${hotel.min_price}/night` : 'Price not available';
        const ratingBadge = hotel.average_rating ? `<span class="badge bg-success fs-6 mb-1">${hotel.average_rating}</span><br><small class="text-muted">(${hotel.total_reviews || 0} reviews)</small>` : '';
        const amenitiesHtml = hotel.amenities && Array.isArray(hotel.amenities) && hotel.amenities.length > 0 
            ? hotel.amenities.slice(0, 6).map(amenity => `<span class="badge bg-light text-dark me-1 mb-1">${amenity}</span>`).join('') + 
              (hotel.amenities.length > 6 ? `<span class="badge bg-light text-primary">+${hotel.amenities.length - 6} more</span>` : '')
            : '';
        
        const isGridView = listContainer.classList.contains('grid-view');
        
        if (isGridView) {
            // Grid view layout
            return `
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 hotel-result-card">
                        <div class="position-relative">
                            <img src="${hotel.main_image}" 
                                 class="card-img-top" 
                                 alt="${hotel.name}"
                                 style="height: 200px; object-fit: cover;">
                            
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary mb-1">${hotel.star_rating_text}</span>
                                ${hotel.is_featured ? '<br><span class="badge bg-warning">Featured</span>' : ''}
                            </div>
                            
                            ${hotel.images && hotel.images.length > 1 ? 
                                `<div class="position-absolute bottom-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="bi bi-images"></i> ${hotel.images.length}
                                    </span>
                                </div>` : ''
                            }
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-1">
                                        <a href="/hotels/${hotel.id}" class="text-decoration-none">
                                            ${hotel.name}
                                        </a>
                                    </h5>
                                    ${ratingBadge ? `<div class="text-end">${ratingBadge}</div>` : ''}
                                </div>
                                
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt"></i> 
                                    ${hotel.city}, ${hotel.country}
                                </p>
                                
                                <p class="card-text text-muted small mb-3 hotel-description">
                                    ${hotel.description ? hotel.description.substring(0, 100) + '...' : ''}
                                </p>
                                
                                ${amenitiesHtml ? `<div class="mb-3 amenities-preview">${amenitiesHtml}</div>` : ''}
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="price-info">
                                    <small class="text-muted">From</small>
                                    <span class="h6 text-primary fw-bold">$${hotel.min_price || 'N/A'}</span>
                                    <small class="text-muted">/night</small>
                                </div>
                                
                                <a href="/hotels/${hotel.id}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // List view layout (original)
            return `
                <div class="col-12">
                    <div class="card h-100 shadow-sm border-0 hotel-result-card">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="position-relative h-100">
                                    <img src="${hotel.main_image}" 
                                         class="card-img hotel-result-image" 
                                         alt="${hotel.name}"
                                         style="height: 250px; object-fit: cover;">
                                    
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-primary mb-1">${hotel.star_rating_text}</span>
                                        ${hotel.is_featured ? '<br><span class="badge bg-warning">Featured</span>' : ''}
                                    </div>
                                    
                                    ${hotel.images && hotel.images.length > 1 ? 
                                        `<div class="position-absolute bottom-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">
                                                <i class="bi bi-images"></i> ${hotel.images.length}
                                            </span>
                                        </div>` : ''
                                    }
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    <a href="/hotels/${hotel.id}" class="text-decoration-none">
                                                        ${hotel.name}
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    <i class="bi bi-geo-alt"></i> 
                                                    ${hotel.city}, ${hotel.country}
                                                </p>
                                            </div>
                                            
                                            ${ratingBadge ? `<div class="text-end">${ratingBadge}</div>` : ''}
                                        </div>
                                        
                                        <p class="card-text text-muted small mb-3">
                                            ${hotel.description ? hotel.description.substring(0, 150) + '...' : ''}
                                        </p>
                                        
                                        ${amenitiesHtml ? `<div class="mb-3">${amenitiesHtml}</div>` : ''}
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div>
                                            <div class="price-info">
                                                <small class="text-muted">From</small>
                                                <span class="h5 text-primary fw-bold">$${hotel.min_price || 'N/A'}</span>
                                                <small class="text-muted">/night</small>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <a href="/hotels/${hotel.id}" class="btn btn-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    function updatePagination(pagination) {
        const paginationContainer = document.querySelector('.d-flex.justify-content-center.mt-5');
        if (paginationContainer && pagination.links) {
            paginationContainer.innerHTML = pagination.links;
        }
    }
    
    function updateResultsCount(total) {
        const resultsText = document.querySelector('.text-muted.mb-0');
        if (resultsText) {
            const hotelText = total === 1 ? 'hotel' : 'hotels';
            const destinationText = document.querySelector('[name="destination"]')?.value || '';
            const inDestination = destinationText ? ` in <strong>${destinationText}</strong>` : '';
            resultsText.innerHTML = `${total} ${hotelText} found${inDestination}`;
        }
    }
    
    function updateMapMarkers(hotels) {
        if (map && markersGroup) {
            // Update the hotels array and refresh markers
            window.hotels = hotels;
            addHotelMarkers();
        }
    }
    
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