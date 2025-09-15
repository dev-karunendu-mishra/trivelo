@extends('hotel-manager.layouts.app')

@section('title', 'Room Management')
@section('page-title', 'Room Management')
@section('page-subtitle', 'Manage hotel rooms and availability')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="mb-4 text-end">
                <a href="{{ route('hotel-manager.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Room
                </a>
            </div>            <!-- Room Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Rooms</h6>
                                    <h3 class="mb-0">{{ $roomStats['total_rooms'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Available</h6>
                                    <h3 class="mb-0">{{ $roomStats['available_rooms'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Occupied</h6>
                                    <h3 class="mb-0">{{ $roomStats['occupied_rooms'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Maintenance</h6>
                                    <h3 class="mb-0">{{ $roomStats['maintenance_rooms'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tools fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('hotel-manager.rooms') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="room_type" class="form-label">Room Type</label>
                            <select class="form-select" id="room_type" name="room_type">
                                <option value="">All Types</option>
                                <option value="single" {{ request('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ request('room_type') == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="suite" {{ request('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                                <option value="deluxe" {{ request('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Room number, guest name...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-outline-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rooms Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rooms ({{ $rooms->total() ?? count($rooms ?? []) }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Current Guest</th>
                                    <th>Rate</th>
                                    <th>Amenities</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms ?? [] as $room)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="room-status-indicator me-2">
                                                <span class="badge bg-{{ 
                                                    $room->status == 'available' ? 'success' : 
                                                    ($room->status == 'occupied' ? 'warning' : 
                                                    ($room->status == 'maintenance' ? 'danger' : 'secondary'))
                                                }}">
                                                    {{ $room->room_number }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>Room {{ $room->room_number }}</strong>
                                                <br>
                                                <small class="text-muted">Floor {{ floor(($room->room_number - 1) / 10) + 1 }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($room->type) }}</span>
                                        <br>
                                        <small class="text-muted">{{ $room->capacity }} guests</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $room->status == 'available' ? 'success' : 
                                            ($room->status == 'occupied' ? 'warning' : 
                                            ($room->status == 'maintenance' ? 'danger' : 'secondary'))
                                        }}">
                                            {{ ucfirst($room->status ?? 'available') }}
                                        </span>
                                        @if($room->status == 'cleaning')
                                            <br><small class="text-muted">Ready in 30min</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($room->current_booking))
                                            <strong>{{ $room->current_booking->guest_name ?? 'Guest' }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                Until {{ $room->current_booking->check_out_date ? 
                                                    Carbon\Carbon::parse($room->current_booking->check_out_date)->format('M d') : 'Unknown' }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>${{ number_format($room->price ?? 0, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">per night</small>
                                    </td>
                                    <td>
                                        @if($room->amenities)
                                            @php
                                                $amenities = is_array($room->amenities) ? $room->amenities : json_decode($room->amenities, true);
                                                $displayAmenities = array_slice($amenities ?? [], 0, 3);
                                            @endphp
                                            @foreach($displayAmenities as $amenity)
                                                <span class="badge bg-light text-dark me-1">{{ $amenity }}</span>
                                            @endforeach
                                            @if(count($amenities ?? []) > 3)
                                                <small class="text-muted">+{{ count($amenities) - 3 }} more</small>
                                            @endif
                                        @else
                                            <span class="text-muted">No amenities</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Room actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewRoom({{ $room->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="editRoom({{ $room->id }})" title="Edit Room">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($room->status == 'available')
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="setMaintenance({{ $room->id }})" title="Set Maintenance">
                                                    <i class="fas fa-tools"></i>
                                                </button>
                                            @elseif($room->status == 'maintenance')
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="setAvailable({{ $room->id }})" title="Mark Available">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" aria-expanded="false" title="More actions">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="viewBookingHistory({{ $room->id }})">
                                                        <i class="fas fa-history"></i> Booking History
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="addToMaintenance({{ $room->id }})">
                                                        <i class="fas fa-wrench"></i> Report Issue
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteRoom({{ $room->id }})">
                                                        <i class="fas fa-trash"></i> Delete Room
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No rooms found</p>
                                        <a href="{{ route('hotel-manager.rooms.create') }}" class="btn btn-primary">
                                            Add Your First Room
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($rooms ?? [], 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $rooms->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1" aria-labelledby="roomDetailsModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomDetailsModalLabel">Room Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="roomDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
.room-status-indicator {
    position: relative;
}
.table td {
    vertical-align: middle;
}
.btn-group .dropdown-toggle::after {
    margin-left: 0;
}
</style>
@endpush

@push('scripts')
<script>
function viewRoom(roomId) {
    // Load room details and show modal
    fetch(`/hotel-manager/rooms/${roomId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('roomDetailsContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('roomDetailsModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function editRoom(roomId) {
    window.location.href = `/hotel-manager/rooms/${roomId}/edit`;
}

function setMaintenance(roomId) {
    if (confirm('Set this room as under maintenance?')) {
        updateRoomStatus(roomId, 'maintenance');
    }
}

function setAvailable(roomId) {
    if (confirm('Mark this room as available?')) {
        updateRoomStatus(roomId, 'available');
    }
}

function updateRoomStatus(roomId, status) {
    fetch(`/hotel-manager/rooms/${roomId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update room status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating room status');
    });
}

function viewBookingHistory(roomId) {
    window.location.href = `/hotel-manager/rooms/${roomId}/bookings`;
}

function addToMaintenance(roomId) {
    window.location.href = `/hotel-manager/maintenance/report?room_id=${roomId}`;
}

function deleteRoom(roomId) {
    if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
        fetch(`/hotel-manager/rooms/${roomId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete room');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the room');
        });
    }
}
</script>
@endpush
@endsection