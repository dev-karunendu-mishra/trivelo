<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 0.5rem;
            margin: 0.25rem 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar-custom {
            background: white;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            @auth
            @if(auth()->user()->role === 'hotel_manager' || auth()->user()->getRoleNames()->contains('hotel_manager'))
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">{{ config('app.name') }}</h5>
                        <small class="text-white-50">Hotel Management</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('hotel-manager.dashboard') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.hotel*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.hotel') }}">
                            <i class="fas fa-building me-2"></i> Hotel Details
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.rooms*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.rooms') }}">
                            <i class="fas fa-bed me-2"></i> Rooms
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.bookings*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i> Bookings
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.guests*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.guests') }}">
                            <i class="fas fa-users me-2"></i> Guests
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.calendar*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.calendar') }}">
                            <i class="fas fa-calendar me-2"></i> Calendar
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.maintenance*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.maintenance') }}">
                            <i class="fas fa-tools me-2"></i> Maintenance
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.reviews*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.reviews') }}">
                            <i class="fas fa-star me-2"></i> Reviews
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.communications*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.communications') }}">
                            <i class="fas fa-comments me-2"></i> Communications
                        </a>
                        
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                        
                        <a class="nav-link {{ request()->routeIs('hotel-manager.settings*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.settings') }}">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.profile*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.profile') }}">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                        <a class="nav-link {{ request()->routeIs('hotel-manager.help*') ? 'active' : '' }}" 
                           href="{{ route('hotel-manager.help') }}">
                            <i class="fas fa-question-circle me-2"></i> Help
                        </a>
                    </nav>
                </div>
            </div>
            @endif
            @endauth

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-custom">
                    <div class="container-fluid">
                        <!-- Page Header -->
                        @isset($header)
                            <div class="navbar-brand">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- User Menu -->
                        @auth
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2"></i>
                                    {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->role === 'super_admin' || auth()->user()->getRoleNames()->contains('super_admin'))
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-shield-alt me-2"></i> Admin Panel
                                        </a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Main Dashboard
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('hotel-manager.profile') }}">
                                        <i class="fas fa-user me-2"></i> Profile
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endauth
                    </div>
                </nav>

                <!-- Main Content Area -->
                <div class="container-fluid p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>