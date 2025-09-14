<!-- Classic Theme Navigation -->
<nav class="navbar navbar-expand-lg classic-nav fixed-top">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand classic-logo fs-2" href="{{ route('home') }}">
            <i class="bi bi-gem me-2"></i>
            Trivelo
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#classicNavbar" 
                aria-controls="classicNavbar" aria-expanded="false" aria-label="Toggle navigation"
                style="color: var(--classic-gold);">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="classicNavbar">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Accommodations
                    </a>
                    <ul class="dropdown-menu classic-card border-0 shadow">
                        <li><a class="dropdown-item" href="#">Luxury Suites</a></li>
                        <li><a class="dropdown-item" href="#">Premium Rooms</a></li>
                        <li><a class="dropdown-item" href="#">Executive Floors</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Services
                    </a>
                    <ul class="dropdown-menu classic-card border-0 shadow">
                        <li><a class="dropdown-item" href="#">Fine Dining</a></li>
                        <li><a class="dropdown-item" href="#">Spa & Wellness</a></li>
                        <li><a class="dropdown-item" href="#">Event Spaces</a></li>
                        <li><a class="dropdown-item" href="#">Concierge</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>

            <!-- Right Side Menu -->
            <div class="navbar-nav">
                <!-- Theme Selector -->
                <div class="nav-item dropdown me-3">
                    <select class="form-select form-select-sm classic-nav-select" 
                            onchange="switchTheme(this.value)"
                            style="background: rgba(255,255,255,0.1); border: 1px solid var(--classic-gold); color: white; font-weight: 600;">
                        <option value="modern" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'modern' ? 'selected' : '' }} style="color: black;">
                            🎨 Modern
                        </option>
                        <option value="classic" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'classic' ? 'selected' : '' }} style="color: black;">
                            🏛️ Classic
                        </option>
                        <option value="minimal" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'minimal' ? 'selected' : '' }} style="color: black;">
                            ⚪ Minimal
                        </option>
                    </select>
                </div>

                @auth
                    <!-- User Dropdown -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle d-flex align-items-center justify-center me-2" 
                                 style="width: 32px; height: 32px; background: var(--classic-gold); color: white; font-weight: bold;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end classic-card border-0 shadow">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: var(--classic-beige);"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Login/Register -->
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-classic-primary btn-sm">
                            <i class="bi bi-person-plus me-1"></i>
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Add spacing for fixed navbar -->
<div style="height: 80px;"></div>