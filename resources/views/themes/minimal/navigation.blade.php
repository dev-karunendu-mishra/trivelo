<!-- Minimal Theme Navigation -->
<nav class="minimal-nav fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="minimal-logo">
                    Trivelo
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="minimal-nav-link">Home</a>
                <a href="#hotels" class="minimal-nav-link">Hotels</a>
                <a href="#rooms" class="minimal-nav-link">Rooms</a>
                <a href="#about" class="minimal-nav-link">About</a>
                <a href="#contact" class="minimal-nav-link">Contact</a>
            </div>

            <!-- Right side -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Theme Selector -->
                <select onchange="switchTheme(this.value)" 
                        class="bg-transparent border border-gray-200 px-3 py-1 text-sm focus:outline-none focus:border-black">
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

                @auth
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-black transition-colors">
                            <div class="w-6 h-6 rounded-full bg-black text-white text-xs flex items-center justify-center">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                        </button>

                        <div x-show="open" @click.outside="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 minimal-card shadow-lg py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-black hover:bg-gray-50 transition-colors">
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-black hover:bg-gray-50 transition-colors">
                                Profile
                            </a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-600 hover:text-black hover:bg-gray-50 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register -->
                    <a href="{{ route('login') }}" class="minimal-nav-link">Login</a>
                    <a href="{{ route('register') }}" class="btn-minimal-primary">Register</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 text-gray-600 hover:text-black transition-colors">
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
                     class="absolute top-16 left-0 right-0 minimal-card mx-4 shadow-lg py-4 z-50">
                    <div class="px-4 space-y-2">
                        <a href="{{ route('home') }}" class="block py-2 text-gray-600 hover:text-black transition-colors">Home</a>
                        <a href="#hotels" class="block py-2 text-gray-600 hover:text-black transition-colors">Hotels</a>
                        <a href="#rooms" class="block py-2 text-gray-600 hover:text-black transition-colors">Rooms</a>
                        <a href="#about" class="block py-2 text-gray-600 hover:text-black transition-colors">About</a>
                        <a href="#contact" class="block py-2 text-gray-600 hover:text-black transition-colors">Contact</a>
                        
                        <!-- Theme Selector Mobile -->
                        <div class="pt-4 border-t border-gray-200">
                            <select onchange="switchTheme(this.value)" 
                                    class="w-full bg-transparent border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-black">
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
                            <div class="pt-4 border-t border-gray-200">
                                <a href="{{ route('dashboard') }}" class="block py-2 text-gray-600 hover:text-black transition-colors">Dashboard</a>
                                <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-600 hover:text-black transition-colors">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left py-2 text-gray-600 hover:text-black transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="pt-4 border-t border-gray-200 space-y-2">
                                <a href="{{ route('login') }}" class="block py-2 text-gray-600 hover:text-black transition-colors">Login</a>
                                <a href="{{ route('register') }}" class="block btn-minimal-primary text-center">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Add spacing for fixed navigation -->
<div class="h-16"></div>