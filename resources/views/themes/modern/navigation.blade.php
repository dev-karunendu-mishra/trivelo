<!-- Modern Theme Navigation -->
<nav class="modern-nav fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold gradient-text">Trivelo</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="{{ route('home') }}" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-all duration-300 hover:bg-blue-50 rounded-lg">
                        Home
                    </a>
                    <a href="#hotels" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-all duration-300 hover:bg-blue-50 rounded-lg">
                        Hotels
                    </a>
                    <a href="#rooms" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-all duration-300 hover:bg-blue-50 rounded-lg">
                        Rooms
                    </a>
                    <a href="#about" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-all duration-300 hover:bg-blue-50 rounded-lg">
                        About
                    </a>
                    <a href="#contact" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-all duration-300 hover:bg-blue-50 rounded-lg">
                        Contact
                    </a>
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Theme Selector -->
                <div class="relative">
                    <select onchange="switchTheme(this.value)" 
                            class="glass-effect rounded-lg px-3 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border-0">
                        <option value="modern" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'modern' ? 'selected' : '' }}>
                            🎨 Modern
                        </option>
                        <option value="classic" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'classic' ? 'selected' : '' }}>
                            🏛️ Classic
                        </option>
                        <option value="minimal" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'minimal' ? 'selected' : '' }}>
                            ⚪ Minimal
                        </option>
                    </select>
                </div>

                @auth
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 glass-effect rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-white hover:bg-opacity-20 transition-all duration-300">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 glass-card rounded-xl shadow-lg py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-10 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h8"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-10 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <hr class="my-1 border-white border-opacity-20">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-white hover:bg-opacity-10 transition-colors text-left">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register buttons -->
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-modern text-sm">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="glass-effect rounded-lg p-2 text-gray-700 hover:bg-white hover:bg-opacity-20 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Mobile menu -->
                <div x-show="open" @click.outside="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute top-20 left-0 right-0 glass-card mx-4 rounded-xl shadow-lg py-4 z-50">
                    <div class="px-4 space-y-3">
                        <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                            Home
                        </a>
                        <a href="#hotels" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                            Hotels
                        </a>
                        <a href="#rooms" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                            Rooms
                        </a>
                        <a href="#about" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                            About
                        </a>
                        <a href="#contact" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                            Contact
                        </a>
                        
                        <!-- Theme Selector Mobile -->
                        <div class="pt-4 border-t border-white border-opacity-20">
                            <select onchange="switchTheme(this.value)" 
                                    class="w-full glass-effect rounded-lg px-3 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border-0">
                                <option value="modern" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'modern' ? 'selected' : '' }}>
                                    🎨 Modern
                                </option>
                                <option value="classic" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'classic' ? 'selected' : '' }}>
                                    🏛️ Classic
                                </option>
                                <option value="minimal" {{ app('App\Services\ThemeService')->getCurrentTheme() === 'minimal' ? 'selected' : '' }}>
                                    ⚪ Minimal
                                </option>
                            </select>
                        </div>
                        
                        @auth
                            <div class="pt-4 border-t border-white border-opacity-20">
                                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                                    Dashboard
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="pt-4 border-t border-white border-opacity-20 space-y-2">
                                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-10 rounded-lg transition-colors">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="block btn-modern text-center">
                                    Get Started
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Add some spacing for fixed navigation -->
<div class="h-20"></div>