<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Welcome, Super Admin {{ auth()->user()->name }}!
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <!-- Total Users -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg">
                            <div class="text-blue-600 dark:text-blue-400 text-sm font-semibold">Total Users</div>
                            <div class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $totalUsers }}</div>
                        </div>

                        <!-- Total Admins -->
                        <div class="bg-red-100 dark:bg-red-900 p-6 rounded-lg">
                            <div class="text-red-600 dark:text-red-400 text-sm font-semibold">Super Admins</div>
                            <div class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $totalAdmins }}</div>
                        </div>

                        <!-- Total Managers -->
                        <div class="bg-green-100 dark:bg-green-900 p-6 rounded-lg">
                            <div class="text-green-600 dark:text-green-400 text-sm font-semibold">Hotel Managers</div>
                            <div class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $totalManagers }}</div>
                        </div>

                        <!-- Total Customers -->
                        <div class="bg-purple-100 dark:bg-purple-900 p-6 rounded-lg">
                            <div class="text-purple-600 dark:text-purple-400 text-sm font-semibold">Customers</div>
                            <div class="text-2xl font-bold text-purple-800 dark:text-purple-200">{{ $totalCustomers }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- User Management -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">User Management</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage all users, roles, and permissions.</p>
                            <a href="{{ route('admin.users') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Manage Users
                            </a>
                        </div>

                        <!-- Roles & Permissions -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">Roles & Permissions</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure system roles and permissions.</p>
                            <a href="{{ route('admin.roles-permissions') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Manage Roles
                            </a>
                        </div>

                        <!-- System Settings -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="font-semibold mb-3">System Settings</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure application settings.</p>
                            <button class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>