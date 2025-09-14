<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Welcome back, {{ auth()->user()->name }}!
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Booking Stats -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg">
                            <div class="text-blue-600 dark:text-blue-400 text-sm font-semibold">Total Bookings</div>
                            <div class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $bookingStats['total_bookings'] }}</div>
                        </div>

                        <div class="bg-green-100 dark:bg-green-900 p-6 rounded-lg">
                            <div class="text-green-600 dark:text-green-400 text-sm font-semibold">Upcoming Bookings</div>
                            <div class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $bookingStats['upcoming_bookings'] }}</div>
                        </div>

                        <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-lg">
                            <div class="text-yellow-600 dark:text-yellow-400 text-sm font-semibold">Completed</div>
                            <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $bookingStats['completed_bookings'] }}</div>
                        </div>

                        <div class="bg-red-100 dark:bg-red-900 p-6 rounded-lg">
                            <div class="text-red-600 dark:text-red-400 text-sm font-semibold">Cancelled</div>
                            <div class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $bookingStats['cancelled_bookings'] }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Browse Hotels -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">Browse Hotels</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Discover and book amazing hotels.</p>
                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Browse Hotels
                            </a>
                        </div>

                        <!-- My Bookings -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">My Bookings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">View and manage your bookings.</p>
                            <a href="{{ route('customer.bookings') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                My Bookings
                            </a>
                        </div>

                        <!-- Profile -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">My Profile</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Update your profile information.</p>
                            <a href="{{ route('customer.profile') }}" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>