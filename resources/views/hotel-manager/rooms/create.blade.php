@extends('hotel-manager.layouts.app')

@section('title', 'Create Room')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Room</h5>
                    <a href="{{ route('hotel-manager.rooms') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Rooms
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.rooms.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="room_number" class="form-label">Room Number *</label>
                                <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                       id="room_number" name="room_number" value="{{ old('room_number') }}" required>
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="room_type" class="form-label">Room Type *</label>
                                <select class="form-select @error('room_type') is-invalid @enderror" 
                                        id="room_type" name="room_type" required>
                                    <option value="">Select Room Type</option>
                                    <option value="single" {{ old('room_type') == 'single' ? 'selected' : '' }}>Single Room</option>
                                    <option value="double" {{ old('room_type') == 'double' ? 'selected' : '' }}>Double Room</option>
                                    <option value="twin" {{ old('room_type') == 'twin' ? 'selected' : '' }}>Twin Room</option>
                                    <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe Room</option>
                                    <option value="family" {{ old('room_type') == 'family' ? 'selected' : '' }}>Family Room</option>
                                </select>
                                @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="base_price" class="form-label">Base Price (per night) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                                           id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                </div>
                                @error('base_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_occupancy" class="form-label">Maximum Occupancy *</label>
                                <input type="number" min="1" class="form-control @error('max_occupancy') is-invalid @enderror" 
                                       id="max_occupancy" name="max_occupancy" value="{{ old('max_occupancy') }}" required>
                                @error('max_occupancy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Room Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Room Amenities</label>
                                <div class="row">
                                    @php
                                    $amenities = [
                                        'wifi' => 'Free WiFi',
                                        'tv' => 'TV',
                                        'ac' => 'Air Conditioning',
                                        'minibar' => 'Mini Bar',
                                        'safe' => 'Safe',
                                        'balcony' => 'Balcony',
                                        'bathtub' => 'Bathtub',
                                        'shower' => 'Shower',
                                        'hairdryer' => 'Hair Dryer',
                                        'phone' => 'Phone',
                                        'coffee_maker' => 'Coffee Maker',
                                        'iron' => 'Iron & Board'
                                    ];
                                    @endphp
                                    @foreach($amenities as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="amenities[]" 
                                                       value="{{ $key }}" id="amenity_{{ $key }}"
                                                       {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="amenity_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="images" class="form-label">Room Images</label>
                                <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                       id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text">Select multiple images for this room (max 5 images)</div>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Room
                                </button>
                                <a href="{{ route('hotel-manager.rooms') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection