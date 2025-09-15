@extends('hotel-manager.layouts.app')

@section('title', 'Edit Hotel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Hotel Information</h5>
                    <a href="{{ route('hotel-manager.hotel') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Hotel Details
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

                    <form action="{{ route('hotel-manager.hotel.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                       value="{{ old('name', $hotel->name ?? '') }}" 
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
                                        <option value="{{ $i }}" 
                                                {{ old('star_rating', $hotel->star_rating ?? '') == $i ? 'selected' : '' }}>
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
                                          placeholder="Describe your hotel...">{{ old('description', $hotel->description ?? '') }}</textarea>
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
                                       value="{{ old('address', $hotel->location->address ?? '') }}" 
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
                                       value="{{ old('city', $hotel->location->city ?? '') }}" 
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
                                       value="{{ old('state', $hotel->location->state ?? '') }}">
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
                                       value="{{ old('country', $hotel->location->country ?? '') }}" 
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
                                       value="{{ old('postal_code', $hotel->location->postal_code ?? '') }}">
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
                                       value="{{ old('phone', $hotel->phone ?? '') }}">
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
                                       value="{{ old('email', $hotel->email ?? '') }}">
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
                                       value="{{ old('website', $hotel->website ?? '') }}"
                                       placeholder="https://example.com">
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
                                    $hotelAmenities = old('amenities', $hotel->amenities ?? []);
                                    @endphp

                                    @foreach($amenities as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="amenities[]" 
                                                       value="{{ $key }}" 
                                                       id="amenity_{{ $key }}"
                                                       {{ in_array($key, $hotelAmenities) ? 'checked' : '' }}>
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
                                <label for="images" class="form-label">Upload New Images</label>
                                <input type="file" 
                                       class="form-control @error('images.*') is-invalid @enderror" 
                                       id="images" 
                                       name="images[]" 
                                       multiple 
                                       accept="image/*">
                                <div class="form-text">You can select multiple images. Maximum 10 images allowed.</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @isset($hotel)
                            @if($hotel->images && $hotel->images->count() > 0)
                                <div class="col-12">
                                    <label class="form-label">Current Images</label>
                                    <div class="row">
                                        @foreach($hotel->images as $image)
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                         class="card-img-top" 
                                                         style="height: 150px; object-fit: cover;"
                                                         alt="Hotel Image">
                                                    <div class="card-body p-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   name="remove_images[]" 
                                                                   value="{{ $image->id }}" 
                                                                   id="remove_{{ $image->id }}">
                                                            <label class="form-check-label small" for="remove_{{ $image->id }}">
                                                                Remove this image
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @endisset
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Hotel
                                    </button>
                                    <a href="{{ route('hotel-manager.hotel') }}" class="btn btn-secondary">
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
// Image preview functionality
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length > 10) {
        alert('Maximum 10 images allowed');
        this.value = '';
        return;
    }
    
    // You can add image preview logic here if needed
});
</script>
@endpush