@extends('hotel-manager.layouts.app')

@section('title', 'Maintenance Report')
@section('page-title', 'Submit Maintenance Request')
@section('page-subtitle', 'Report maintenance issues and requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Report Maintenance Issue</h5>
                    <a href="{{ route('hotel-manager.maintenance') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Maintenance
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotel-manager.maintenance.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                                    <select class="form-select" id="room_id" name="room_id" required>
                                        <option value="">Select Room</option>
                                        @foreach($rooms ?? [] as $room)
                                        <option value="{{ $room['id'] }}">Room {{ $room['room_number'] }} - {{ $room['type'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Select the room where the issue occurred</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="plumbing">Plumbing</option>
                                        <option value="electrical">Electrical</option>
                                        <option value="hvac">HVAC/Air Conditioning</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="cleaning">Cleaning</option>
                                        <option value="electronics">Electronics/TV</option>
                                        <option value="lighting">Lighting</option>
                                        <option value="safety">Safety/Security</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="low">Low - Can wait</option>
                                        <option value="medium" selected>Medium - Should be addressed soon</option>
                                        <option value="high">High - Needs attention today</option>
                                        <option value="urgent">Urgent - Immediate attention required</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reported_by" class="form-label">Reported By</label>
                                    <input type="text" class="form-control" id="reported_by" name="reported_by" value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Issue Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Brief description of the issue">
                            <div class="form-text">Provide a clear, concise title for the maintenance issue</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Provide a detailed description of the issue, including what happened, when it occurred, and any steps already taken..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="guest_impact" class="form-label">Guest Impact</label>
                                    <select class="form-select" id="guest_impact" name="guest_impact">
                                        <option value="none">No guest impact</option>
                                        <option value="minor">Minor inconvenience</option>
                                        <option value="moderate">Moderate disruption</option>
                                        <option value="major">Major disruption</option>
                                        <option value="room_unusable">Room unusable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_cost" class="form-label">Estimated Cost ($)</label>
                                    <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" step="0.01" placeholder="0.00">
                                    <div class="form-text">Optional: Estimated repair cost if known</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="photos" class="form-label">Photos</label>
                            <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                            <div class="form-text">Upload photos of the issue (optional, but helpful for maintenance staff)</div>
                        </div>

                        <div class="mb-3">
                            <label for="preferred_time" class="form-label">Preferred Repair Time</label>
                            <select class="form-select" id="preferred_time" name="preferred_time">
                                <option value="">No preference</option>
                                <option value="morning">Morning (8 AM - 12 PM)</option>
                                <option value="afternoon">Afternoon (12 PM - 5 PM)</option>
                                <option value="evening">Evening (5 PM - 8 PM)</option>
                                <option value="overnight">Overnight (after guest checkout)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_guest" name="notify_guest" value="1">
                                <label class="form-check-label" for="notify_guest">
                                    Notify guest about maintenance schedule (if room is occupied)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="additional_notes" name="additional_notes" rows="3" placeholder="Any additional information that might be helpful..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Report
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                                <i class="fas fa-save"></i> Save as Draft
                            </button>
                            <a href="{{ route('hotel-manager.maintenance') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function saveDraft() {
    // Save form data as draft
    const formData = new FormData(document.querySelector('form'));
    // Implementation for saving draft
    alert('Draft saved successfully!');
}

// Auto-save functionality
let autoSaveTimer;
document.addEventListener('input', function(e) {
    if (e.target.matches('textarea, input[type="text"]')) {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            // Auto-save logic here
            console.log('Auto-saving...');
        }, 3000);
    }
});

// Priority color coding
document.getElementById('priority').addEventListener('change', function() {
    const priority = this.value;
    this.className = 'form-select ';
    switch(priority) {
        case 'urgent':
            this.className += 'border-danger';
            break;
        case 'high':
            this.className += 'border-warning';
            break;
        case 'medium':
            this.className += 'border-info';
            break;
        default:
            this.className += 'border-secondary';
    }
});
</script>
@endpush
@endsection