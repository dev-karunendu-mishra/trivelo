@extends('hotel-manager.layouts.app')

@section('title', 'Create Hotel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Hotel</h5>
                    <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> You can manage only one hotel per account. Once created, you can edit the details anytime.
                    </div>

                    <form action="{{ route('hotel-manager.hotel.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-building"></i> Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Hotel Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="star_rating" class="form-label">Star Rating</label>
                                <select class="form-select @error('star_rating') is-invalid @enderror" 
                                        id="star_rating" 
                                        name="star_rating">
                                    <option value="">Select Rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('star_rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('star_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4"
                                          placeholder="Describe your hotel, its unique features, and what makes it special...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marker-alt"></i> Location Information
                                </h6>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address') }}" 
                                       placeholder="Street address, building number"
                                       required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}" 
                                       required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" 
                                       class="form-control @error('state') is-invalid @enderror" 
                                       id="state" 
                                       name="state" 
                                       value="{{ old('state') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <input type="text" 
                                       class="form-control @error('country') is-invalid @enderror" 
                                       id="country" 
                                       name="country" 
                                       value="{{ old('country') }}" 
                                       required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Postal/ZIP Code</label>
                                <input type="text" 
                                       class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-phone"></i> Contact Information
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="hotel@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" 
                                       class="form-control @error('website') is-invalid @enderror" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website') }}"
                                       placeholder="https://yourhotel.com">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-concierge-bell"></i> Amenities & Services
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    @php
                                    $amenities = [
                                        'wifi' => 'Free WiFi',
                                        'parking' => 'Free Parking',
                                        'pool' => 'Swimming Pool',
                                        'gym' => 'Fitness Center',
                                        'spa' => 'Spa & Wellness',
                                        'restaurant' => 'Restaurant',
                                        'bar' => 'Bar/Lounge',
                                        'room_service' => '24/7 Room Service',
                                        'concierge' => 'Concierge Service',
                                        'laundry' => 'Laundry Service',
                                        'business_center' => 'Business Center',
                                        'pet_friendly' => 'Pet Friendly',
                                        'airport_shuttle' => 'Airport Shuttle',
                                        'conference_rooms' => 'Conference Rooms',
                                        'air_conditioning' => 'Air Conditioning',
                                        'heating' => 'Heating'
                                    ];
                                    @endphp

                                    @foreach($amenities as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="amenities[]" 
                                                       value="{{ $key }}" 
                                                       id="amenity_{{ $key }}"
                                                       {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="amenity_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-images"></i> Hotel Images
                                </h6>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="images" class="form-label">Upload Hotel Images</label>
                                <input type="file" 
                                       class="form-control @error('images.*') is-invalid @enderror" 
                                       id="images" 
                                       name="images[]" 
                                       multiple 
                                       accept="image/*">
                                <div class="form-text">
                                    Select multiple images to showcase your hotel. Maximum 10 images allowed. 
                                    Recommended: at least 3-5 high-quality images including exterior, lobby, and room views.
                                </div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Hotel
                                    </button>
                                    <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Image preview and validation
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 10) {
        alert('Maximum 10 images allowed');
        this.value = '';
        return;
    }
    
    // Validate file sizes
    let totalSize = 0;
    for (let i = 0; i < files.length; i++) {
        totalSize += files[i].size;
        if (files[i].size > 5 * 1024 * 1024) { // 5MB per file
            alert('Each image must be smaller than 5MB');
            this.value = '';
            return;
        }
    }
    
    if (totalSize > 50 * 1024 * 1024) { // 50MB total
        alert('Total file size must be less than 50MB');
        this.value = '';
        return;
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    const country = document.getElementById('country').value.trim();
    
    if (!name || !address || !city || !country) {
        e.preventDefault();
        alert('Please fill in all required fields (marked with *)');
        return false;
    }
});
</script>
@endpush