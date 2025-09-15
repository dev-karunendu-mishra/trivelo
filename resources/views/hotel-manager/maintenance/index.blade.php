@extends('hotel-manager.layouts.app')

@section('title', 'Maintenance')
@section('page-title', 'Maintenance Requests')
@section('page-subtitle', 'Track and manage maintenance issues')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Maintenance Requests</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                        <i class="fas fa-plus"></i> New Request
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priorityFilter">
                                <option value="">All Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Search by room or description..." id="searchInput">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room</th>
                                    <th>Issue</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Reported Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenanceRequests ?? [] as $request)
                                <tr>
                                    <td>#{{ $request['id'] ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $request['room_number'] ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $request['room_type'] ?? 'Unknown' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $request['title'] ?? 'No Title' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($request['description'] ?? 'No description', 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request['priority'] == 'urgent' ? 'danger' : ($request['priority'] == 'high' ? 'warning' : ($request['priority'] == 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($request['priority'] ?? 'low') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request['status'] == 'completed' ? 'success' : ($request['status'] == 'in_progress' ? 'primary' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $request['status'] ?? 'pending')) }}
                                        </span>
                                    </td>
                                    <td>{{ $request['created_at'] ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewRequest({{ $request['id'] ?? 0 }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="updateStatus({{ $request['id'] ?? 0 }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRequest({{ $request['id'] ?? 0 }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No maintenance requests found</p>
                                    </td>
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

<!-- Add Maintenance Request Modal -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('hotel-manager.maintenance.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Maintenance Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="room_id" class="form-label">Room</label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="">Select Room</option>
                                    @foreach($rooms ?? [] as $room)
                                    <option value="{{ $room['id'] }}">{{ $room['room_number'] }} - {{ $room['type'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Issue Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="electrical">Electrical</option>
                            <option value="hvac">HVAC</option>
                            <option value="furniture">Furniture</option>
                            <option value="cleaning">Cleaning</option>
                            <option value="electronics">Electronics</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewRequest(id) {
    // View maintenance request details
    window.location.href = `/hotel-manager/maintenance/${id}`;
}

function updateStatus(id) {
    // Update maintenance request status
    if (confirm('Update maintenance request status?')) {
        // AJAX call to update status
    }
}

function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this maintenance request?')) {
        // AJAX call to delete request
    }
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('priorityFilter').addEventListener('change', filterTable);
document.getElementById('searchInput').addEventListener('input', filterTable);

function filterTable() {
    // Table filtering logic
}
</script>
@endpush
@endsection