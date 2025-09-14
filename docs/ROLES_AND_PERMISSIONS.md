# Roles and Permissions System

## Overview

The Trivelo Hotel Booking System implements a comprehensive role-based access control (RBAC) system using Spatie Laravel Permission package. The system supports three main user roles with granular permissions.

## User Roles

### 1. Super Admin (`super_admin`)
**Full system access with all permissions:**

- User Management (view, create, edit, delete users, manage roles)
- Hotel Management (view, create, edit, delete, approve hotels)
- Room Management (view, create, edit, delete rooms)
- Booking Management (view, create, edit, cancel all bookings)
- Payment Management (view, process, refund payments)
- Review Management (view, create, edit, delete, moderate reviews)
- System Management (dashboard, analytics, settings, notifications, reports)

**Default Account:** `admin@trivelo.com` / `password`

### 2. Hotel Manager (`hotel_manager`)
**Hotel-specific management with limited permissions:**

- Hotel Management (view hotels, manage own hotel)
- Room Management (view, create, edit, delete, manage own rooms)
- Booking Management (view bookings, manage hotel bookings)
- Payment Management (view payments, view own payments)
- Review Management (view, moderate reviews)
- Dashboard Access (view dashboard, analytics, reports)

**Default Account:** `manager@trivelo.com` / `password`

### 3. Customer (`customer`)
**Customer-focused permissions for booking and reviews:**

- Hotel & Room Viewing (view hotels, view rooms)
- Booking Management (create bookings, view own bookings, cancel bookings)
- Payment Processing (process payments, view own payments)
- Review System (create reviews, edit own reviews, view reviews)

**Default Account:** `customer@trivelo.com` / `password`

## System Architecture

### Models and Relationships

```php
// User Model with Spatie Permission trait
class User extends Authenticatable
{
    use HasRoles;
    
    // Helper methods
    public function isSuperAdmin(): bool
    public function isHotelManager(): bool  
    public function isCustomer(): bool
}
```

### Middleware

- **RoleMiddleware:** Checks if user has specific role
- **PermissionMiddleware:** Checks if user has specific permission

```php
// Usage in routes
Route::middleware('role:super_admin')->group(function () {
    // Admin routes
});

Route::middleware('permission:manage own hotel')->group(function () {
    // Permission-based routes
});
```

### Controllers

- **AdminController:** Super admin functionality
- **HotelManagerController:** Hotel management functionality  
- **CustomerController:** Customer functionality

### Database Tables

The system uses Spatie Permission's standard tables:
- `roles` - Role definitions
- `permissions` - Permission definitions  
- `model_has_roles` - User-role assignments
- `model_has_permissions` - Direct user-permission assignments
- `role_has_permissions` - Role-permission assignments

## Permissions List

### User Management
- `view users` - View user listings
- `create users` - Create new users
- `edit users` - Edit user information
- `delete users` - Delete users
- `manage user roles` - Assign/remove user roles

### Hotel Management
- `view hotels` - View hotel listings
- `create hotels` - Create new hotels
- `edit hotels` - Edit hotel information
- `delete hotels` - Delete hotels
- `approve hotels` - Approve hotel registrations
- `manage own hotel` - Manage own hotel (for managers)

### Room Management
- `view rooms` - View room listings
- `create rooms` - Create new rooms
- `edit rooms` - Edit room information
- `delete rooms` - Delete rooms
- `manage own rooms` - Manage own hotel rooms

### Booking Management
- `view bookings` - View all bookings
- `create bookings` - Create new bookings
- `edit bookings` - Edit booking information
- `cancel bookings` - Cancel bookings
- `view own bookings` - View own bookings
- `manage hotel bookings` - Manage hotel's bookings

### Payment Management
- `view payments` - View all payments
- `process payments` - Process payment transactions
- `refund payments` - Process refunds
- `view own payments` - View own payment history

### Review Management
- `view reviews` - View reviews
- `create reviews` - Write reviews
- `edit own reviews` - Edit own reviews
- `delete reviews` - Delete any review
- `moderate reviews` - Moderate review content

### System Management
- `view dashboard` - Access dashboard
- `view analytics` - View analytics data
- `manage settings` - Configure system settings
- `manage notifications` - Manage notifications
- `view reports` - Generate and view reports

## Route Protection

### Role-based Route Groups

```php
// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    // ... more admin routes
});

// Hotel Manager Routes  
Route::middleware(['auth', 'role:hotel_manager'])->prefix('hotel-manager')->group(function () {
    Route::get('/dashboard', [HotelManagerController::class, 'dashboard']);
    // ... more manager routes
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard']);
    // ... more customer routes
});
```

### Smart Dashboard Routing

The main `/dashboard` route automatically redirects users to their role-specific dashboard:

```php
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super_admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('hotel_manager')) {
        return redirect()->route('hotel-manager.dashboard');
    } elseif ($user->hasRole('customer')) {
        return redirect()->route('customer.dashboard');
    }
    
    return view('dashboard');
});
```

## Testing

### Role System Verification

Use the built-in test command to verify the system:

```bash
php artisan roles:test
```

This command tests:
- Role existence and permission counts
- User role assignments  
- Permission functionality
- Helper method functionality
- System statistics

## Usage Examples

### In Controllers

```php
// Check role
if (auth()->user()->hasRole('super_admin')) {
    // Admin-only logic
}

// Check permission
if (auth()->user()->can('manage own hotel')) {
    // Hotel management logic
}
```

### In Blade Templates

```blade
@role('super_admin')
    <p>Only super admins can see this</p>
@endrole

@can('create bookings')
    <button>Book Now</button>
@endcan
```

### Role Assignment

```php
// Assign role to user
$user->assignRole('hotel_manager');

// Remove role
$user->removeRole('customer');

// Sync roles (removes all other roles)
$user->syncRoles(['customer']);
```

## Security Features

1. **Middleware Protection:** All sensitive routes protected by role/permission middleware
2. **Database-driven Permissions:** All permissions stored in database, not hardcoded
3. **Role Helper Methods:** Easy role checking with `isSuperAdmin()`, `isHotelManager()`, `isCustomer()`
4. **Automatic Redirects:** Unauthorized access redirects to login or shows 403 error
5. **User Status Control:** Active/inactive user status management

## Future Enhancements

- **Dynamic Permission Assignment:** Allow admins to create custom permissions
- **Role Hierarchies:** Implement role inheritance (e.g., Super Admin inherits Hotel Manager permissions)
- **Permission Caching:** Implement Redis caching for permission checks
- **Audit Logging:** Track role and permission changes
- **Two-Factor Authentication:** Add 2FA for admin roles
- **Session Management:** Advanced session control for different user roles