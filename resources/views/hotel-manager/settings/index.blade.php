@extends('hotel-manager.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Hotel Settings')
@section('page-subtitle', 'Configure hotel preferences and policies')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                                <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab">
                                    <i class="fas fa-cog me-2"></i>General
                                </button>
                                <button class="nav-link" id="v-pills-rooms-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rooms" type="button" role="tab">
                                    <i class="fas fa-bed me-2"></i>Room Settings
                                </button>
                                <button class="nav-link" id="v-pills-policies-tab" data-bs-toggle="pill" data-bs-target="#v-pills-policies" type="button" role="tab">
                                    <i class="fas fa-file-contract me-2"></i>Policies
                                </button>
                                <button class="nav-link" id="v-pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#v-pills-notifications" type="button" role="tab">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </button>
                                <button class="nav-link" id="v-pills-integrations-tab" data-bs-toggle="pill" data-bs-target="#v-pills-integrations" type="button" role="tab">
                                    <i class="fas fa-plug me-2"></i>Integrations
                                </button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <!-- General Settings -->
                                <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                                    <form action="{{ route('hotel-manager.settings.update', 'general') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="hotel_name" class="form-label">Hotel Name</label>
                                                    <input type="text" class="form-control" id="hotel_name" name="hotel_name" value="{{ $settings['hotel_name'] ?? 'Grand Hotel' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="hotel_email" class="form-label">Hotel Email</label>
                                                    <input type="email" class="form-control" id="hotel_email" name="hotel_email" value="{{ $settings['hotel_email'] ?? 'contact@grandhotel.com' }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="hotel_phone" class="form-label">Hotel Phone</label>
                                                    <input type="text" class="form-control" id="hotel_phone" name="hotel_phone" value="{{ $settings['hotel_phone'] ?? '+1-555-0123' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="timezone" class="form-label">Timezone</label>
                                                    <select class="form-select" id="timezone" name="timezone">
                                                        <option value="UTC">UTC</option>
                                                        <option value="America/New_York">Eastern Time</option>
                                                        <option value="America/Chicago">Central Time</option>
                                                        <option value="America/Denver">Mountain Time</option>
                                                        <option value="America/Los_Angeles">Pacific Time</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="hotel_address" class="form-label">Hotel Address</label>
                                            <textarea class="form-control" id="hotel_address" name="hotel_address" rows="3">{{ $settings['hotel_address'] ?? '123 Grand Ave, City, State 12345' }}</textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                                
                                <!-- Room Settings -->
                                <div class="tab-pane fade" id="v-pills-rooms" role="tabpanel">
                                    <form action="{{ route('hotel-manager.settings.update', 'rooms') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="check_in_time" class="form-label">Check-in Time</label>
                                                    <input type="time" class="form-control" id="check_in_time" name="check_in_time" value="{{ $settings['check_in_time'] ?? '15:00' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="check_out_time" class="form-label">Check-out Time</label>
                                                    <input type="time" class="form-control" id="check_out_time" name="check_out_time" value="{{ $settings['check_out_time'] ?? '11:00' }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="cleaning_time" class="form-label">Room Cleaning Time (minutes)</label>
                                                    <input type="number" class="form-control" id="cleaning_time" name="cleaning_time" value="{{ $settings['cleaning_time'] ?? '30' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="maintenance_buffer" class="form-label">Maintenance Buffer (minutes)</label>
                                                    <input type="number" class="form-control" id="maintenance_buffer" name="maintenance_buffer" value="{{ $settings['maintenance_buffer'] ?? '15' }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="auto_assign_rooms" name="auto_assign_rooms" {{ ($settings['auto_assign_rooms'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="auto_assign_rooms">
                                                    Automatically assign available rooms
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                                
                                <!-- Policies -->
                                <div class="tab-pane fade" id="v-pills-policies" role="tabpanel">
                                    <form action="{{ route('hotel-manager.settings.update', 'policies') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="cancellation_policy" class="form-label">Cancellation Policy</label>
                                            <textarea class="form-control" id="cancellation_policy" name="cancellation_policy" rows="4">{{ $settings['cancellation_policy'] ?? 'Free cancellation up to 24 hours before check-in.' }}</textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="pet_policy" class="form-label">Pet Policy</label>
                                            <textarea class="form-control" id="pet_policy" name="pet_policy" rows="3">{{ $settings['pet_policy'] ?? 'Pets are welcome with prior approval and additional fees.' }}</textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="smoking_policy" class="form-label">Smoking Policy</label>
                                            <textarea class="form-control" id="smoking_policy" name="smoking_policy" rows="3">{{ $settings['smoking_policy'] ?? 'No smoking policy in effect for all rooms and common areas.' }}</textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                                
                                <!-- Notifications -->
                                <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel">
                                    <form action="{{ route('hotel-manager.settings.update', 'notifications') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Email Notifications</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_new_bookings" name="email_new_bookings" {{ ($settings['email_new_bookings'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_new_bookings">
                                                    New bookings
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_cancellations" name="email_cancellations" {{ ($settings['email_cancellations'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_cancellations">
                                                    Cancellations
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="email_maintenance" name="email_maintenance" {{ ($settings['email_maintenance'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="email_maintenance">
                                                    Maintenance requests
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                                
                                <!-- Integrations -->
                                <div class="tab-pane fade" id="v-pills-integrations" role="tabpanel">
                                    <form action="{{ route('hotel-manager.settings.update', 'integrations') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="payment_gateway" class="form-label">Payment Gateway</label>
                                            <select class="form-select" id="payment_gateway" name="payment_gateway">
                                                <option value="stripe">Stripe</option>
                                                <option value="paypal">PayPal</option>
                                                <option value="square">Square</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="pms_integration" class="form-label">PMS Integration</label>
                                            <select class="form-select" id="pms_integration" name="pms_integration">
                                                <option value="">None</option>
                                                <option value="opera">Opera PMS</option>
                                                <option value="protel">Protel</option>
                                                <option value="cloudbeds">Cloudbeds</option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection