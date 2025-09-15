<div class="header d-flex align-items-center justify-content-between px-4">
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle -->
        <button class="btn btn-link text-muted d-md-none me-3" onclick="toggleMobileSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <button class="btn btn-link text-muted d-none d-md-block me-3" onclick="toggleSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <!-- Page Title -->
        <div>
            <h5 class="mb-0 text-dark">@yield('page-title', 'Dashboard')</h5>
            @hasSection('page-subtitle')
                <small class="text-muted">@yield('page-subtitle')</small>
            @endif
        </div>
    </div>
    
    <!-- Header Actions -->
    <div class="d-flex align-items-center gap-3">
        <!-- Quick Stats -->
        <div class="d-none d-lg-flex align-items-center gap-4 me-3">
            <div class="text-center">
                <div class="text-success fw-bold fs-6">{{ $headerStats['occupancy'] ?? '78%' }}</div>
                <small class="text-muted">Occupancy</small>
            </div>
            <div class="text-center">
                <div class="text-info fw-bold fs-6">{{ $headerStats['available_rooms'] ?? '12' }}</div>
                <small class="text-muted">Available</small>
            </div>
            <div class="text-center">
                <div class="text-warning fw-bold fs-6">{{ $headerStats['pending_checkins'] ?? '5' }}</div>
                <small class="text-muted">Check-ins</small>
            </div>
        </div>
        
        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn btn-link text-muted position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                    </span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notifications</span>
                    <small class="text-muted">{{ $unreadNotifications ?? 0 }} unread</small>
                </div>
                <div class="dropdown-divider"></div>
                
                <!-- Sample notifications -->
                <a class="dropdown-item d-flex align-items-start py-3" href="#">
                    <div class="bg-success rounded-circle p-2 me-3">
                        <i class="bi bi-calendar-check text-white small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">New Booking</div>
                        <small class="text-muted">Room 205 booked for tonight</small>
                        <div class="text-muted small">2 min ago</div>
                    </div>
                </a>
                
                <a class="dropdown-item d-flex align-items-start py-3" href="#">
                    <div class="bg-info rounded-circle p-2 me-3">
                        <i class="bi bi-chat-dots text-white small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Guest Message</div>
                        <small class="text-muted">John Doe sent a message</small>
                        <div class="text-muted small">5 min ago</div>
                    </div>
                </a>
                
                <a class="dropdown-item d-flex align-items-start py-3" href="#">
                    <div class="bg-warning rounded-circle p-2 me-3">
                        <i class="bi bi-exclamation-triangle text-white small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">Maintenance Alert</div>
                        <small class="text-muted">Room 301 needs attention</small>
                        <div class="text-muted small">15 min ago</div>
                    </div>
                </a>
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="#">View All Notifications</a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="dropdown">
            <button class="btn btn-gradient btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-plus-lg me-1"></i> Quick Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('hotel-manager.rooms.create') }}">
                    <i class="bi bi-door-open me-2"></i>Add New Room
                </a></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.bookings.manual') }}">
                    <i class="bi bi-calendar-plus me-2"></i>Manual Booking
                </a></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.guests.checkin') }}">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Check-in Guest
                </a></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.guests.checkout') }}">
                    <i class="bi bi-box-arrow-right me-2"></i>Check-out Guest
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.maintenance.report') }}">
                    <i class="bi bi-tools me-2"></i>Report Maintenance
                </a></li>
            </ul>
        </div>
        
        <!-- User Menu -->
        <div class="dropdown">
            <button class="btn btn-link text-muted d-flex align-items-center" data-bs-toggle="dropdown">
                <img src="https://via.placeholder.com/32x32/6c757d/ffffff?text={{ substr(auth()->user()->name, 0, 1) }}" 
                     class="rounded-circle me-2" width="32" height="32" alt="User">
                <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down ms-1"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('hotel-manager.profile') }}">
                    <i class="bi bi-person me-2"></i>My Profile
                </a></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.settings') }}">
                    <i class="bi bi-gear me-2"></i>Settings
                </a></li>
                <li><a class="dropdown-item" href="{{ route('hotel-manager.hotel.edit') }}">
                    <i class="bi bi-building me-2"></i>Hotel Settings
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>