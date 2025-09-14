@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'Welcome to Trivelo - Your Ultimate Hotel Booking Experience')

@section('content')
    <!-- Hero Section -->
    @include('themes.' . App\Services\ThemeService::current() . '.hero')

    <!-- Search Widget -->
    <section class="search-widget py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4 text-primary">Find Your Perfect Stay</h3>
                            <form action="{{ route('search') }}" method="GET" id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Destination</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="destination" 
                                                   id="destination"
                                                   placeholder="Where do you want to go?" 
                                                   autocomplete="off">
                                        </div>
                                        <div id="destinationSuggestions" class="suggestions-dropdown"></div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Check-in</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="check_in" 
                                                   id="checkin"
                                                   min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Check-out</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
                                            <input type="date" 
                                                   class="form-control" 
                                                   name="check_out" 
                                                   id="checkout"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Guests</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                                            <select class="form-select" name="guests" id="guests">
                                                <option value="1">1 Guest</option>
                                                <option value="2" selected>2 Guests</option>
                                                <option value="3">3 Guests</option>
                                                <option value="4">4 Guests</option>
                                                <option value="5">5 Guests</option>
                                                <option value="6">6+ Guests</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Rooms</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                                            <select class="form-select" name="rooms" id="rooms">
                                                <option value="1" selected>1 Room</option>
                                                <option value="2">2 Rooms</option>
                                                <option value="3">3 Rooms</option>
                                                <option value="4">4 Rooms</option>
                                                <option value="5">5+ Rooms</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100 py-2 px-3">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($featuredHotels->count() > 0)
    <!-- Featured Hotels Section -->
    <section class="featured-hotels py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-center mb-3">Featured Hotels</h2>
                    <p class="text-center text-muted mb-4">Discover our handpicked selection of exceptional accommodations</p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($featuredHotels as $hotel)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 hotel-card">
                        <div class="position-relative">
                            <img src="{{ $hotel->main_image }}" 
                                 class="card-img-top hotel-image" 
                                 alt="{{ $hotel->name }}"
                                 style="height: 250px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary">{{ $hotel->star_rating_text }}</span>
                            </div>
                            @if($hotel->is_featured)
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning">Featured</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="card-title mb-1">{{ $hotel->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-geo-alt"></i> 
                                    {{ $hotel->location->city ?? $hotel->city }}, {{ $hotel->location->country ?? $hotel->country }}
                                </p>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($hotel->description, 120) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="rating">
                                    @if($hotel->average_rating)
                                        <span class="badge bg-success">{{ $hotel->formatted_rating }}</span>
                                        <small class="text-muted">({{ $hotel->total_reviews ?? 0 }} reviews)</small>
                                    @else
                                        <small class="text-muted">New hotel</small>
                                    @endif
                                </div>
                                <a href="{{ route('hotel.details', $hotel->id) }}" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('search') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-search"></i> Browse All Hotels
                </a>
            </div>
        </div>
    </section>
    @endif

    @if($popularDestinations->count() > 0)
    <!-- Popular Destinations Section -->
    <section class="destinations py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-center mb-3">Popular Destinations</h2>
                    <p class="text-center text-muted mb-4">Explore the world's most sought-after travel destinations</p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($popularDestinations as $destination)
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 border-0 shadow-sm destination-card">
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $destination->image_url ?: '/images/destination-placeholder.jpg' }}" 
                                 class="card-img-top destination-image" 
                                 alt="{{ $destination->name }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="position-absolute bottom-0 start-0 end-0 bg-gradient-to-top p-3">
                                <h5 class="text-white mb-1">{{ $destination->name }}</h5>
                                <p class="text-white-50 small mb-0">
                                    {{ $destination->activeHotels->count() }} hotels available
                                </p>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <p class="card-text text-muted small">
                                {{ Str::limit($destination->description, 100) }}
                            </p>
                            
                            <a href="{{ route('search', ['destination' => $destination->name]) }}" 
                               class="btn btn-outline-primary btn-sm w-100">
                                Explore Hotels
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Stats Section -->
    <section class="stats py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">{{ number_format($stats['total_hotels']) }}+</h3>
                        <p class="mb-0">Hotels Worldwide</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">{{ number_format($stats['total_destinations']) }}+</h3>
                        <p class="mb-0">Destinations</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">{{ number_format($stats['happy_customers']) }}+</h3>
                        <p class="mb-0">Happy Customers</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-item">
                        <h3 class="display-4 fw-bold mb-2">{{ $stats['years_experience'] }}+</h3>
                        <p class="mb-0">Years Experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="features py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-3">Why Choose Trivelo?</h2>
                    <p class="text-center text-muted">Experience the difference with our premium booking platform</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Secure Booking</h4>
                        <p class="text-muted">Your payment and personal information are protected with industry-leading security measures.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-clock-history text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">24/7 Support</h4>
                        <p class="text-muted">Our dedicated customer service team is available round-the-clock to assist you.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-piggy-bank text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Best Price Guarantee</h4>
                        <p class="text-muted">Find a lower price elsewhere? We'll match it and give you an additional discount.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-geo-alt text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Global Reach</h4>
                        <p class="text-muted">Book accommodations in thousands of destinations worldwide with local expertise.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-star text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Quality Assured</h4>
                        <p class="text-muted">Every hotel in our network is carefully vetted to ensure exceptional standards.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center h-100 p-4">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-phone text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-3">Easy Booking</h4>
                        <p class="text-muted">Simple, intuitive booking process that gets you from search to confirmation in minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .search-widget {
        margin-top: -50px;
        position: relative;
        z-index: 10;
    }
    
    .hotel-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .destination-card {
        transition: transform 0.3s ease;
    }
    
    .destination-card:hover {
        transform: translateY(-3px);
    }
    
    .destination-image {
        transition: transform 0.3s ease;
    }
    
    .destination-card:hover .destination-image {
        transform: scale(1.05);
    }
    
    .feature-card {
        background: white;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }
    
    .suggestion-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .suggestion-item:hover {
        background-color: #f8f9fa;
    }
    
    .bg-gradient-to-top {
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const destinationInput = document.getElementById('destination');
    const suggestionsDiv = document.getElementById('destinationSuggestions');
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    
    // Destination autocomplete
    let debounceTimer;
    destinationInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        
        if (query.length < 2) {
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        debounceTimer = setTimeout(function() {
            fetchDestinations(query);
        }, 300);
    });
    
    function fetchDestinations(query) {
        fetch(`{{ route('api.destinations') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    displaySuggestions(data);
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching destinations:', error);
                suggestionsDiv.style.display = 'none';
            });
    }
    
    function displaySuggestions(suggestions) {
        suggestionsDiv.innerHTML = '';
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = suggestion.label;
            item.addEventListener('click', function() {
                destinationInput.value = suggestion.value;
                suggestionsDiv.style.display = 'none';
            });
            suggestionsDiv.appendChild(item);
        });
        suggestionsDiv.style.display = 'block';
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!destinationInput.contains(event.target) && !suggestionsDiv.contains(event.target)) {
            suggestionsDiv.style.display = 'none';
        }
    });
    
    // Date validation
    checkinInput.addEventListener('change', function() {
        const checkinDate = new Date(this.value);
        const checkoutDate = new Date(checkoutInput.value);
        
        // Set minimum checkout date to day after checkin
        const minCheckout = new Date(checkinDate);
        minCheckout.setDate(minCheckout.getDate() + 1);
        checkoutInput.min = minCheckout.toISOString().split('T')[0];
        
        // If checkout is before or same as checkin, reset it
        if (checkoutDate <= checkinDate) {
            checkoutInput.value = minCheckout.toISOString().split('T')[0];
        }
    });
    
    // Set default dates
    if (!checkinInput.value) {
        const today = new Date();
        today.setDate(today.getDate() + 1);
        checkinInput.value = today.toISOString().split('T')[0];
        
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 2);
        checkoutInput.value = tomorrow.toISOString().split('T')[0];
    }
});
</script>
@endpush