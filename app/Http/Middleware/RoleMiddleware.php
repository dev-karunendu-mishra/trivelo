<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Handle multiple roles separated by pipe (|) - for backward compatibility
        $allowedRoles = [];
        foreach ($roles as $roleString) {
            if (str_contains($roleString, '|')) {
                $allowedRoles = array_merge($allowedRoles, explode('|', $roleString));
            } else {
                $allowedRoles[] = $roleString;
            }
        }

        // Debug logging
        Log::info('RoleMiddleware Debug', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role_field' => $user->role,
            'allowed_roles' => $allowedRoles,
            'user_spatie_roles' => $user->getRoleNames()->toArray(),
            'request_path' => $request->path()
        ]);

        // Check if user has any of the required roles
        $hasRequiredRole = false;
        $userRoles = $user->getRoleNames()->toArray();
        
        foreach ($allowedRoles as $role) {
            $role = trim($role);
            if (in_array($role, $userRoles) || $user->role === $role) {
                $hasRequiredRole = true;
                break;
            }
        }

        if (!$hasRequiredRole) {
            Log::warning('Role access denied', [
                'user_id' => $user->id,
                'required_roles' => $allowedRoles,
                'user_roles' => $userRoles,
                'user_role_field' => $user->role
            ]);
            abort(403, 'Unauthorized access. You do not have the required role.');
        }

        return $next($request);
    }
}
