<!-- Admin Header -->
<header class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-shield-check me-2"></i>
            <span class="fw-bold">{{ config('app.name') }} Admin</span>
        </a>
        
        <!-- Mobile Sidebar Toggle -->
        <button class="btn btn-outline-light d-lg-none" type="button" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Right Side Navigation -->
        <div class="navbar-nav ms-auto">
            <!-- Notifications Dropdown -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" 
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                          id="notification-count" style="font-size: 0.6rem;">3</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="width: 300px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        <small><a href="#" class="text-decoration-none">Mark all read</a></small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                <div class="small">
                                    <strong>New booking pending</strong><br>
                                    <span class="text-muted">Grand Hotel - 2 minutes ago</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <div class="d-flex">
                                <i class="bi bi-person-plus text-info me-2"></i>
                                <div class="small">
                                    <strong>New user registered</strong><br>
                                    <span class="text-muted">john.doe@email.com - 5 minutes ago</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <div class="d-flex">
                                <i class="bi bi-chat-dots text-primary me-2"></i>
                                <div class="small">
                                    <strong>New review submitted</strong><br>
                                    <span class="text-muted">5-star review - 10 minutes ago</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                </ul>
            </div>
            
            <!-- Quick Actions Dropdown -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="quickActionsDropdown" 
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickActionsDropdown">
                    <li class="dropdown-header">Quick Actions</li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-building me-2"></i>Add Hotel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-door-open me-2"></i>Add Room</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus me-2"></i>Add User</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-palette me-2"></i>Manage Themes</a></li>
                </ul>
            </div>
            
            <!-- User Profile Dropdown -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/32x32/28a745/ffffff?text={{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}" 
                         class="rounded-circle me-2" width="32" height="32" alt="Profile">
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li class="dropdown-header">
                        <div class="text-center">
                            <img src="https://via.placeholder.com/64x64/28a745/ffffff?text={{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}" 
                                 class="rounded-circle mb-2" width="64" height="64" alt="Profile">
                            <div><strong>{{ auth()->user()->name ?? 'Admin User' }}</strong></div>
                            <small class="text-muted">{{ auth()->user()->email ?? 'admin@example.com' }}</small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Account Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-shield-check me-2"></i>Security</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="bi bi-eye me-2"></i>View Frontend</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>