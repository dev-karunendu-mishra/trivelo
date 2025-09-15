<!-- Admin Sidebar -->
<nav class="admin-sidebar">
    <div class="sidebar-content">
        <!-- User Info -->
        <div class="user-info p-3 border-bottom border-secondary">
            <div class="d-flex align-items-center">
                <img src="https://via.placeholder.com/40x40/28a745/ffffff?text={{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}" 
                     class="rounded-circle me-3" width="40" height="40" alt="Profile">
                <div class="text-white">
                    <div class="fw-semibold">{{ auth()->user()->name ?? 'Admin User' }}</div>
                    <small class="text-muted">Super Administrator</small>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column p-3">
            <!-- Dashboard -->
            <li class="nav-item mb-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <!-- Analytics -->
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-graph-up me-2"></i>
                    Analytics
                    <span class="badge bg-success ms-auto">New</span>
                </a>
            </li>
            
            <!-- Hotels Management -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#hotelsMenu" 
                   role="button" aria-expanded="false" aria-controls="hotelsMenu">
                    <i class="bi bi-building me-2"></i>
                    Hotels
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="hotelsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-list me-2"></i>All Hotels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-plus-circle me-2"></i>Add Hotel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-clock me-2"></i>Pending Approval
                                <span class="badge bg-warning ms-auto">5</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-geo-alt me-2"></i>Locations
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Rooms Management -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#roomsMenu" 
                   role="button" aria-expanded="false" aria-controls="roomsMenu">
                    <i class="bi bi-door-open me-2"></i>
                    Rooms
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="roomsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-list me-2"></i>All Rooms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-plus-circle me-2"></i>Add Room
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-tools me-2"></i>Room Types
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-star me-2"></i>Amenities
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Bookings Management -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#bookingsMenu" 
                   role="button" aria-expanded="false" aria-controls="bookingsMenu">
                    <i class="bi bi-calendar-check me-2"></i>
                    Bookings
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="bookingsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-list me-2"></i>All Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-clock me-2"></i>Pending
                                <span class="badge bg-warning ms-auto">12</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-check-circle me-2"></i>Confirmed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-x-circle me-2"></i>Cancelled
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Payments -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#paymentsMenu" 
                   role="button" aria-expanded="false" aria-controls="paymentsMenu">
                    <i class="bi bi-credit-card me-2"></i>
                    Payments
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="paymentsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-list me-2"></i>All Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Refunds
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-exclamation-triangle me-2"></i>Failed
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Users Management -->
            <li class="nav-item mb-1">
                <a href="{{ route('admin.users') }}" 
                   class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'active bg-primary' : '' }}">
                    <i class="bi bi-people me-2"></i>
                    Users
                </a>
            </li>
            
            <!-- Reviews -->
            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-chat-dots me-2"></i>
                    Reviews
                    <span class="badge bg-info ms-auto">24</span>
                </a>
            </li>
            
            <!-- Reports -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#reportsMenu" 
                   role="button" aria-expanded="false" aria-controls="reportsMenu">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>
                    Reports
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="reportsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-graph-up me-2"></i>Revenue
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-calendar3 me-2"></i>Occupancy
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-people me-2"></i>Customer
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <hr class="border-secondary">
            
            <!-- System Settings -->
            <li class="nav-item mb-1">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#settingsMenu" 
                   role="button" aria-expanded="false" aria-controls="settingsMenu">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="settingsMenu">
                    <ul class="nav nav-pills flex-column ms-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-sliders me-2"></i>General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-palette me-2"></i>Themes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-envelope me-2"></i>Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-light small">
                                <i class="bi bi-shield-check me-2"></i>Security
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <!-- Roles & Permissions -->
            <li class="nav-item mb-1">
                <a href="{{ route('admin.roles-permissions') }}" 
                   class="nav-link text-white {{ request()->routeIs('admin.roles-permissions') ? 'active bg-primary' : '' }}">
                    <i class="bi bi-shield-lock me-2"></i>
                    Roles & Permissions
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
    .admin-sidebar {
        background: linear-gradient(180deg, #343a40 0%, #2c3034 100%);
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .admin-sidebar .nav-link {
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .admin-sidebar .nav-link:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateX(2px);
    }
    
    .admin-sidebar .nav-link.active {
        background: linear-gradient(45deg, #0d6efd, #0056b3);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }
    
    .admin-sidebar .badge {
        font-size: 0.7rem;
    }
    
    .admin-sidebar .collapse .nav-link {
        padding: 0.5rem 1rem;
        margin: 0.1rem 0;
    }
    
    .admin-sidebar .collapse .nav-link:hover {
        background-color: rgba(255,255,255,0.05);
        padding-left: 1.2rem;
    }
</style>