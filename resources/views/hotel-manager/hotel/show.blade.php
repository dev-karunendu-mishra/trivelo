@extends('hotel-manager.layouts.app')

@section('title', 'Hotel Details')
@section('page-title', 'Hotel Information')
@section('page-subtitle', 'View and manage your hotel details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- Hotel Information Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Hotel Details</h5>
                    <a href="{{ route('hotel-manager.hotel.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Hotel
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Hotel Name</label>
                                <p class="fw-bold">{{ $hotel->name ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p>{{ $hotel->email ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone</label>
                                <p>{{ $hotel->phone ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Website</label>
                                <p>
                                    @if($hotel->website ?? false)
                                        <a href="{{ $hotel->website }}" target="_blank">{{ $hotel->website }}</a>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Address</label>
                        <p>{{ $hotel->address ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p>{{ $hotel->description ?? 'No description available' }}</p>
                    </div>

                    @if($hotel->amenities)
                        <div class="mb-3">
                            <label class="form-label text-muted">Amenities</label>
                            <div>
                                @php
                                    $amenities = is_array($hotel->amenities) ? $hotel->amenities : json_decode($hotel->amenities, true);
                                @endphp
                                @foreach($amenities ?? [] as $amenity)
                                    <span class="badge bg-primary me-1 mb-1">{{ $amenity }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hotel Policies Card -->
            @if($hotel->policies ?? false)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Policies</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Check-in Time</label>
                                <p>{{ $hotel->check_in_time ?? '3:00 PM' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Check-out Time</label>
                                <p>{{ $hotel->check_out_time ?? '11:00 AM' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($hotel->cancellation_policy ?? false)
                    <div class="mb-3">
                        <label class="form-label text-muted">Cancellation Policy</label>
                        <p>{{ $hotel->cancellation_policy }}</p>
                    </div>
                    @endif

                    @if($hotel->pet_policy ?? false)
                    <div class="mb-3">
                        <label class="form-label text-muted">Pet Policy</label>
                        <p>{{ $hotel->pet_policy }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Hotel Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Rooms</span>
                            <strong>{{ $hotel->rooms()->count() ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Available Rooms</span>
                            <strong class="text-success">{{ $hotel->rooms()->where('is_available', true)->count() ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Occupied Rooms</span>
                            <strong class="text-warning">{{ $hotel->rooms()->occupied()->count() ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Maintenance</span>
                            <strong class="text-danger">{{ $hotel->rooms()->where('status', 'maintenance')->count() ?? 0 }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Bookings</span>
                            <strong>{{ $hotel->bookings()->count() ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Bookings</span>
                            <strong class="text-primary">{{ $hotel->bookings()->where('status', 'confirmed')->count() ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotel Status -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            <span class="badge bg-{{ ($hotel->status ?? 'active') === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($hotel->status ?? 'active') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Registration Date</label>
                        <p>{{ $hotel->created_at ? $hotel->created_at->format('M d, Y') : 'Unknown' }}</p>
                    </div>

                    @if($hotel->approved_at ?? false)
                    <div class="mb-3">
                        <label class="form-label text-muted">Approved Date</label>
                        <p>{{ Carbon\Carbon::parse($hotel->approved_at)->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hotel-manager.rooms') }}" class="btn btn-outline-primary">
                            <i class="fas fa-bed me-2"></i>Manage Rooms
                        </a>
                        <a href="{{ route('hotel-manager.bookings') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar-check me-2"></i>View Bookings
                        </a>
                        <a href="{{ route('hotel-manager.settings') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Manager:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    @if($user->phone ?? false)
                    <div class="mb-2">
                        <strong>Phone:</strong> {{ $user->phone }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($hotel->photos ?? false)
    <!-- Hotel Photos -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Photos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $photos = is_array($hotel->photos) ? $hotel->photos : json_decode($hotel->photos, true);
                        @endphp
                        @foreach($photos ?? [] as $photo)
                        <div class="col-md-3 mb-3">
                            <img src="{{ $photo }}" alt="Hotel Photo" class="img-fluid rounded">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label.text-muted {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.badge {
    font-size: 0.75em;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>
@endpush