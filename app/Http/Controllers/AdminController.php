<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin');
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalAdmins = User::role('super_admin')->count();
        $totalManagers = User::role('hotel_manager')->count();
        $totalCustomers = User::role('customer')->count();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAdmins', 
            'totalManagers', 
            'totalCustomers'
        ));
    }

    /**
     * Display users management
     */
    public function users()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load('roles', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Display roles and permissions management
     */
    public function rolesPermissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('admin.roles-permissions', compact('roles', 'permissions'));
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
