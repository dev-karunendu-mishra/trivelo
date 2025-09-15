@extends('themes.' . App\Services\ThemeService::current() . '.layout')

@section('title', 'My Profile - Trivelo')

@section('content')
    <!-- Page Header -->
    <section class="page-header bg-primary text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-1">My Profile</h1>
                    <p class="mb-0 text-white-50">Manage your personal information and preferences</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="profile-content py-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Column - Profile Picture & Basic Info -->
                <div class="col-lg-4">
                    <!-- Profile Picture Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="profile-picture mb-3">
                                @if($user->profile_picture)
                                    <img src="{{ $user->profile_picture }}" 
                                         class="rounded-circle" 
                                         alt="{{ $user->name }}"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="profile-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 120px; height: 120px; font-size: 2rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            <form action="{{ route('customer.profile.picture') }}" method="POST" enctype="multipart/form-data" id="pictureForm">
                                @csrf
                                <input type="file" id="profilePicture" name="profile_picture" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('profilePicture').click()">
                                    <i class="bi bi-camera"></i> Change Picture
                                </button>
                            </form>
                            
                            <div class="profile-stats mt-4">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-value">{{ $user->bookings->count() }}</div>
                                        <div class="stat-label">Bookings</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-value">{{ $user->reviews->count() }}</div>
                                        <div class="stat-label">Reviews</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-value">{{ $user->created_at->format('Y') }}</div>
                                        <div class="stat-label">Member Since</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Status Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-shield-check text-success"></i> Account Status</h6>
                        </div>
                        <div class="card-body">
                            <div class="status-items">
                                <div class="status-item d-flex align-items-center mb-3">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <span>Email Verified</span>
                                </div>
                                <div class="status-item d-flex align-items-center mb-3">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <span>Account Active</span>
                                </div>
                                <div class="status-item d-flex align-items-center">
                                    @if($user->phone)
                                        <i class="bi bi-check-circle text-success me-2"></i>
                                        <span>Phone Verified</span>
                                    @else
                                        <i class="bi bi-x-circle text-warning me-2"></i>
                                        <span>Phone Not Added</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Forms -->
                <div class="col-lg-8">
                    <!-- Personal Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-person text-primary"></i> Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.profile.update') }}" method="POST" id="profileForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Travel Preferences -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-gear text-primary"></i> Travel Preferences</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.preferences.update') }}" method="POST" id="preferencesForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="preferred_currency" class="form-label">Preferred Currency</label>
                                        <select class="form-select @error('preferred_currency') is-invalid @enderror" 
                                                id="preferred_currency" name="preferred_currency">
                                            <option value="USD" {{ old('preferred_currency', $user->preferred_currency ?? 'USD') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                            <option value="EUR" {{ old('preferred_currency', $user->preferred_currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                            <option value="GBP" {{ old('preferred_currency', $user->preferred_currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                            <option value="CAD" {{ old('preferred_currency', $user->preferred_currency) === 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                        </select>
                                        @error('preferred_currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="preferred_language" class="form-label">Preferred Language</label>
                                        <select class="form-select @error('preferred_language') is-invalid @enderror" 
                                                id="preferred_language" name="preferred_language">
                                            <option value="en" {{ old('preferred_language', $user->preferred_language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                            <option value="es" {{ old('preferred_language', $user->preferred_language) === 'es' ? 'selected' : '' }}>Spanish</option>
                                            <option value="fr" {{ old('preferred_language', $user->preferred_language) === 'fr' ? 'selected' : '' }}>French</option>
                                            <option value="de" {{ old('preferred_language', $user->preferred_language) === 'de' ? 'selected' : '' }}>German</option>
                                        </select>
                                        @error('preferred_language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <fieldset>
                                        <legend class="form-label">Notification Preferences</legend>
                                    <div class="preferences-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" 
                                                   name="email_notifications" value="1" 
                                                   {{ old('email_notifications', $user->email_notifications ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_notifications">
                                                Email Notifications
                                                <small class="text-muted d-block">Receive booking confirmations and updates via email</small>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sms_notifications" 
                                                   name="sms_notifications" value="1" 
                                                   {{ old('sms_notifications', $user->sms_notifications ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sms_notifications">
                                                SMS Notifications
                                                <small class="text-muted d-block">Receive important updates via SMS</small>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="marketing_emails" 
                                                   name="marketing_emails" value="1" 
                                                   {{ old('marketing_emails', $user->marketing_emails ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="marketing_emails">
                                                Marketing Emails
                                                <small class="text-muted d-block">Receive special offers and travel deals</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg"></i> Update Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-lock text-primary"></i> Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('customer.password.update') }}" method="POST" id="passwordForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                                
                                <div class="password-requirements mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Password must be at least 8 characters long and contain uppercase, lowercase, numbers, and special characters.
                                    </small>
                                </div>
                                
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-shield-check"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
    }

    .profile-avatar {
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-item {
        font-size: 0.9rem;
    }

    .preferences-group .form-check {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .preferences-group .form-check:last-child {
        border-bottom: none;
    }

    .form-check-label small {
        margin-top: 0.25rem;
    }

    .card {
        transition: box-shadow 0.15s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .password-requirements {
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        border-left: 4px solid #6c757d;
    }

    @media (max-width: 768px) {
        .profile-picture {
            margin-bottom: 2rem !important;
        }
        
        .profile-stats .row > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle profile picture upload
    document.getElementById('profilePicture').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('pictureForm').submit();
        }
    });

    // Form validation and loading states
    const forms = ['profileForm', 'preferencesForm', 'passwordForm'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
                submitBtn.disabled = true;
            });
        }
    });

    // Password strength indicator
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            // Could add password strength indicator here
            console.log('Password strength check');
        });
    }

    // Animate cards on load
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush