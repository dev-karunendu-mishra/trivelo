@extends('hotel-manager.layouts.app')

@section('title', 'Help & Support')
@section('page-title', 'Help Center')
@section('page-subtitle', 'Get help and support for managing your hotel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Help Topics</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#getting-started" class="list-group-item list-group-item-action">
                            <i class="fas fa-play-circle me-2"></i>Getting Started
                        </a>
                        <a href="#bookings" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar me-2"></i>Managing Bookings
                        </a>
                        <a href="#rooms" class="list-group-item list-group-item-action">
                            <i class="fas fa-bed me-2"></i>Room Management
                        </a>
                        <a href="#guests" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Guest Services
                        </a>
                        <a href="#analytics" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Analytics & Reports
                        </a>
                        <a href="#maintenance" class="list-group-item list-group-item-action">
                            <i class="fas fa-tools me-2"></i>Maintenance
                        </a>
                        <a href="#settings" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#contactSupportModal">
                            <i class="fas fa-headset"></i> Contact Support
                        </button>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-video"></i> Video Tutorials
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Download Manual
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hotel Management Help Center</h5>
                </div>
                <div class="card-body">
                    <!-- Getting Started Section -->
                    <section id="getting-started" class="mb-5">
                        <h4><i class="fas fa-play-circle text-primary me-2"></i>Getting Started</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h6>
                                        <p>Your dashboard provides a real-time overview of your hotel operations including occupancy rates, revenue, and pending tasks.</p>
                                        <ul>
                                            <li>View current occupancy statistics</li>
                                            <li>Monitor revenue trends</li>
                                            <li>Track pending check-ins and check-outs</li>
                                            <li>Access quick action buttons</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><i class="fas fa-user-cog me-2"></i>Setting Up Your Profile</h6>
                                        <p>Customize your profile to personalize your experience and ensure proper notifications.</p>
                                        <ul>
                                            <li>Upload a profile photo</li>
                                            <li>Update contact information</li>
                                            <li>Set notification preferences</li>
                                            <li>Configure security settings</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bookings Section -->
                    <section id="bookings" class="mb-5">
                        <h4><i class="fas fa-calendar text-primary me-2"></i>Managing Bookings</h4>
                        <div class="accordion" id="bookingsAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bookings-create">
                                        Creating New Bookings
                                    </button>
                                </h2>
                                <div id="bookings-create" class="accordion-collapse collapse show" data-bs-parent="#bookingsAccordion">
                                    <div class="accordion-body">
                                        <p>To create a new booking:</p>
                                        <ol>
                                            <li>Navigate to the Bookings section</li>
                                            <li>Click the "New Booking" button</li>
                                            <li>Fill in guest information</li>
                                            <li>Select room type and dates</li>
                                            <li>Confirm booking details and save</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bookings-modify">
                                        Modifying Existing Bookings
                                    </button>
                                </h2>
                                <div id="bookings-modify" class="accordion-collapse collapse" data-bs-parent="#bookingsAccordion">
                                    <div class="accordion-body">
                                        <p>You can modify bookings by:</p>
                                        <ul>
                                            <li>Changing dates (subject to availability)</li>
                                            <li>Upgrading or downgrading room types</li>
                                            <li>Adding special requests</li>
                                            <li>Processing cancellations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Rooms Section -->
                    <section id="rooms" class="mb-5">
                        <h4><i class="fas fa-bed text-primary me-2"></i>Room Management</h4>
                        <p>Effective room management is crucial for maximizing occupancy and guest satisfaction.</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                                        <h6>Adding Rooms</h6>
                                        <p class="small">Create new room entries with detailed specifications</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-edit fa-2x text-warning mb-2"></i>
                                        <h6>Room Status</h6>
                                        <p class="small">Update room availability and maintenance status</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-dollar-sign fa-2x text-primary mb-2"></i>
                                        <h6>Pricing</h6>
                                        <p class="small">Manage room rates and seasonal pricing</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- FAQ Section -->
                    <section id="faq" class="mb-5">
                        <h4><i class="fas fa-question-circle text-primary me-2"></i>Frequently Asked Questions</h4>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        How do I handle overbookings?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        When overbookings occur, the system will alert you and suggest alternative accommodations. You can also manually reassign guests to available rooms or partner hotels.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        How do I generate reports?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Navigate to the Analytics section and select the type of report you need. You can customize date ranges and export data in various formats.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Support Modal -->
<div class="modal fade" id="contactSupportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('hotel-manager.support.contact') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select class="form-select" id="subject" name="subject" required>
                            <option value="">Select a topic</option>
                            <option value="technical">Technical Issue</option>
                            <option value="billing">Billing Question</option>
                            <option value="feature">Feature Request</option>
                            <option value="general">General Support</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Describe your issue or question..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.list-group-item-action {
    border: none;
    padding: 0.75rem 1rem;
}
.list-group-item-action:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
@endsection