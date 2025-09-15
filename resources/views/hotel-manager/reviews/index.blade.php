@extends('hotel-manager.layouts.app')

@section('title', 'Reviews Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star"></i> Reviews Management
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Manage guest reviews and ratings for your hotel.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">4.8</h5>
                                    <p class="card-text">Average Rating</p>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-success">156</h5>
                                    <p class="card-text">Total Reviews</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">8</h5>
                                    <p class="card-text">Pending Reviews</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title text-info">12</h5>
                                    <p class="card-text">This Month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="ms-1">5.0</span>
                                        </div>
                                    </td>
                                    <td>
                                        <small>Excellent service and beautiful rooms...</small>
                                    </td>
                                    <td>{{ date('M d, Y') }}</td>
                                    <td><span class="badge bg-success">Published</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Reply</button>
                                    </td>
                                </tr>
                                <!-- More review rows would go here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection