@extends('hotel-manager.layouts.app')

@section('title', 'Calendar')
@section('page-title', 'Calendar & Availability')
@section('page-subtitle', 'View and manage room availability')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Calendar & Availability</h5>
                    <div>
                        <button class="btn btn-primary btn-sm" id="todayBtn">Today</button>
                        <button class="btn btn-outline-secondary btn-sm" id="monthView">Month</button>
                        <button class="btn btn-outline-secondary btn-sm" id="weekView">Week</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar" style="height: 600px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="bookingDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editBookingBtn">Edit Booking</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<style>
.fc-event {
    cursor: pointer;
}
.availability-high { background-color: #28a745; }
.availability-medium { background-color: #ffc107; }
.availability-low { background-color: #dc3545; }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: @json($events ?? []),
        eventClick: function(info) {
            showBookingDetails(info.event);
        },
        dateClick: function(info) {
            // Handle date click for availability management
        }
    });
    
    calendar.render();
    
    // View toggles
    document.getElementById('monthView').addEventListener('click', function() {
        calendar.changeView('dayGridMonth');
    });
    
    document.getElementById('weekView').addEventListener('click', function() {
        calendar.changeView('timeGridWeek');
    });
    
    document.getElementById('todayBtn').addEventListener('click', function() {
        calendar.today();
    });
    
    function showBookingDetails(event) {
        // Show booking details in modal
        document.getElementById('bookingDetails').innerHTML = `
            <p><strong>Room:</strong> ${event.extendedProps.room_number}</p>
            <p><strong>Guest:</strong> ${event.extendedProps.guest_name}</p>
            <p><strong>Check-in:</strong> ${event.start.toLocaleDateString()}</p>
            <p><strong>Check-out:</strong> ${event.end.toLocaleDateString()}</p>
            <p><strong>Status:</strong> ${event.extendedProps.status}</p>
        `;
        
        var modal = new bootstrap.Modal(document.getElementById('bookingModal'));
        modal.show();
    }
});
</script>
@endpush
@endsection