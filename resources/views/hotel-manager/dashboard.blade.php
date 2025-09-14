<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hotel Manager Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Welcome, {{ auth()->user()->name }}! (Hotel Manager)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Hotel Stats -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg">
                            <div class="text-blue-600 dark:text-blue-400 text-sm font-semibold">Total Rooms</div>
                            <div class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $hotelStats['total_rooms'] }}</div>
                        </div>

                        <div class="bg-green-100 dark:bg-green-900 p-6 rounded-lg">
                            <div class="text-green-600 dark:text-green-400 text-sm font-semibold">Available Rooms</div>
                            <div class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $hotelStats['available_rooms'] }}</div>
                        </div>

                        <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-lg">
                            <div class="text-yellow-600 dark:text-yellow-400 text-sm font-semibold">Bookings Today</div>
                            <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $hotelStats['bookings_today'] }}</div>
                        </div>

                        <div class="bg-purple-100 dark:bg-purple-900 p-6 rounded-lg">
                            <div class="text-purple-600 dark:text-purple-400 text-sm font-semibold">Total Revenue</div>
                            <div class="text-2xl font-bold text-purple-800 dark:text-purple-200">${{ number_format($hotelStats['total_revenue'], 2) }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Hotel Management -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">Hotel Profile</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage your hotel information and settings.</p>
                            <a href="{{ route('hotel-manager.hotel') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Manage Hotel
                            </a>
                        </div>

                        <!-- Room Management -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">Room Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Add, edit, and manage your hotel rooms.</p>
                            <a href="{{ route('hotel-manager.rooms') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Manage Rooms
                            </a>
                        </div>

                        <!-- Booking Management -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">Bookings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">View and manage hotel bookings.</p>
                            <a href="{{ route('hotel-manager.bookings') }}" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                View Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>