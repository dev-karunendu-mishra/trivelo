<div class="sidebar" id="sidebar">
    <div class="p-4">
        <!-- Logo -->
        <div class="text-center mb-4">
            <h4 class="text-white fw-bold mb-0">
                <i class="bi bi-building me-2"></i>Trivelo Manager
            </h4>
            <small class="text-white-50">Hotel Management Portal</small>
        </div>
        
        <!-- User Info -->
        <div class="bg-white bg-opacity-10 rounded-3 p-3 mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-white rounded-circle p-2 me-3">
                    <i class="bi bi-person-fill text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-white fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-white-50">Hotel Manager</small>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="nav flex-column">
            <a href="{{ route('hotel-manager.dashboard') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
            
            <a href="{{ route('hotel-manager.hotel') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.hotel*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                Hotel Profile
            </a>
            
            <a href="{{ route('hotel-manager.rooms') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.rooms*') ? 'active' : '' }}">
                <i class="bi bi-door-open"></i>
                Room Management
            </a>
            
            <a href="{{ route('hotel-manager.bookings') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.bookings*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                Bookings
                @if(isset($pendingBookings) && $pendingBookings > 0)
                    <span class="badge bg-warning text-dark ms-2">{{ $pendingBookings }}</span>
                @endif
            </a>
            
            <a href="{{ route('hotel-manager.analytics.dashboard') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.analytics*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i>
                Analytics & Reports
            </a>
            
            <a href="{{ route('hotel-manager.guests') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.guests*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                Guest Management
            </a>
            
            <a href="{{ route('hotel-manager.reviews') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.reviews*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                Reviews & Ratings
            </a>
            
            <a href="{{ route('hotel-manager.calendar') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.calendar*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                Availability Calendar
            </a>
            
            <a href="{{ route('hotel-manager.communications') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.communications*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i>
                Guest Communications
            </a>
            
            <!-- Divider -->
            <hr class="border-white-50 my-3">
            
            <a href="{{ route('hotel-manager.settings') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                Settings
            </a>
            
            <a href="{{ route('hotel-manager.help') }}" 
               class="nav-link {{ request()->routeIs('hotel-manager.help*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i>
                Help & Support
            </a>
            
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </nav>
    </div>
</div>