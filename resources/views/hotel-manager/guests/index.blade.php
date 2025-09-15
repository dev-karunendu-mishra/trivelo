@extends('hotel-manager.layouts.app')

@section('title', 'Guest Management')
@section('page-title', 'Guests')
@section('page-subtitle', 'Manage all hotel guests and their information')

@section('content')
    <!-- Guest Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title mb-1">{{ $guests ? $guests->count() : 0 }}</h3>
                            <p class="card-text">Total Guests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            @php
                                $checkedInCount = 0;
                                if($guests) {
                                    foreach($guests as $guest) {
                                        if($guest->bookings && $guest->bookings->where('status', 'checked_in')->count() > 0) {
                                            $checkedInCount++;
                                        }
                                    }
                                }
                            @endphp
                            <h3 class="card-title mb-1">{{ $checkedInCount }}</h3>
                            <p class="card-text">Checked In</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-sign-in-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            @php
                                $pendingCount = 0;
                                if($guests) {
                                    foreach($guests as $guest) {
                                        if($guest->bookings && $guest->bookings->where('status', 'pending')->count() > 0) {
                                            $pendingCount++;
                                        }
                                    }
                                }
                            @endphp
                            <h3 class="card-title mb-1">{{ $pendingCount }}</h3>
                            <p class="card-text">Pending Bookings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            @php
                                $vipCount = 0;
                                if($guests) {
                                    foreach($guests as $guest) {
                                        if($guest->is_vip ?? false) {
                                            $vipCount++;
                                        }
                                    }
                                }
                            @endphp
                            <h3 class="card-title mb-1">{{ $vipCount }}</h3>
                            <p class="card-text">VIP Guests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Guest Management</h5>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal">
                        <i class="fas fa-plus"></i> Add Guest
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="searchGuest" class="form-label">Search Guest</label>
                    <input type="text" class="form-control" id="searchGuest" placeholder="Name, email, or phone">
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Booking Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="checked_in">Checked In</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="checked_out">Checked Out</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vipFilter" class="form-label">Guest Type</label>
                    <select class="form-select" id="vipFilter">
                        <option value="">All Guests</option>
                        <option value="vip">VIP Guests</option>
                        <option value="regular">Regular Guests</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Guests List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Guest List</h5>
        </div>
        <div class="card-body">
            @if($guests && $guests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Guest Info</th>
                                <th>Contact</th>
                                <th>Current Booking</th>
                                <th>Room</th>
                                <th>Total Stays</th>
                                <th>Total Spent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guests as $guest)
                                @php
                                    $currentBooking = null;
                                    $totalBookings = 0;
                                    $totalSpent = 0;
                                    
                                    if($guest->bookings) {
                                        $currentBooking = $guest->bookings->whereIn('status', ['checked_in', 'confirmed'])->first();
                                        $totalBookings = $guest->bookings->count();
                                        $totalSpent = $guest->bookings->sum('total_amount');
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ substr($guest->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $guest->name }}</strong>
                                                @if($guest->is_vip ?? false)
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="fas fa-star"></i> VIP
                                                    </span>
                                                @endif
                                                <br>
                                                <small class="text-muted">ID: #{{ $guest->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $guest->email ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $guest->phone ?? 'No phone' }}</small>
                                    </td>
                                    <td>
                                        @if($currentBooking)
                                            <div>
                                                <strong>Booking #{{ $currentBooking->id }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($currentBooking->check_in_date)->format('M d') }} - 
                                                    {{ \Carbon\Carbon::parse($currentBooking->check_out_date)->format('M d, Y') }}
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">No active booking</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($currentBooking && $currentBooking->room)
                                            <strong>{{ $currentBooking->room->room_number }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $currentBooking->room->room_type }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $totalBookings }}</strong>
                                        <br>
                                        <small class="text-muted">bookings</small>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($totalSpent, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($currentBooking)
                                            @php
                                                $statusClass = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'success',
                                                    'checked_in' => 'info',
                                                    'checked_out' => 'secondary'
                                                ][$currentBooking->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ ucfirst($currentBooking->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">No Booking</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewGuestModal"
                                                    data-guest-id="{{ $guest->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editGuestModal"
                                                    data-guest-id="{{ $guest->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($currentBooking && $currentBooking->status === 'confirmed')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        title="Check In">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </button>
                                            @endif
                                            @if($currentBooking && $currentBooking->status === 'checked_in')
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        title="Check Out">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($guests, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $guests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No guests found</h4>
                    <p class="text-muted">Start welcoming guests to your hotel.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Guest Modal -->
    <div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGuestModalLabel">Add New Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addGuestForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_vip" name="is_vip">
                                <label class="form-check-label" for="is_vip">
                                    VIP Guest
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Guest</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    font-weight: 600;
    border-top: none;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush