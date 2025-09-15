@extends('hotel-manager.layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile Management')
@section('page-subtitle', 'Manage your account information and settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        @if(auth()->user()->profile_photo ?? false)
                            <img src="{{ auth()->user()->profile_photo }}" alt="Profile Photo" class="rounded-circle" width="100" height="100">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge bg-primary">{{ ucfirst(auth()->user()->role ?? 'Hotel Manager') }}</span>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Bookings Managed</span>
                        <strong>{{ $stats['total_bookings'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Active Bookings</span>
                        <strong>{{ $stats['active_bookings'] ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Revenue</span>
                        <strong>${{ number_format($stats['total_revenue'] ?? 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Account Since</span>
                        <strong>{{ auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'Unknown' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control" id="department" name="department" value="{{ auth()->user()->department ?? 'Hotel Management' }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                            <small class="text-muted">Choose a new profile photo (optional)</small>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Tell us about yourself...">{{ auth()->user()->bio ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Account Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Activity</th>
                                    <th>Date</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities ?? [] as $activity)
                                <tr>
                                    <td>{{ $activity['description'] ?? 'Unknown activity' }}</td>
                                    <td>{{ $activity['created_at'] ?? 'Unknown date' }}</td>
                                    <td>{{ $activity['ip_address'] ?? 'Unknown IP' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No recent activity found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection