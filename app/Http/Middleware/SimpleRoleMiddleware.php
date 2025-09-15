<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SimpleRoleMiddleware
{
    /**
     * Handle an incoming request - Simple role checking
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Simple check: either the role field matches or the user has the Spatie role
        $hasRole = ($user->role === $role) || 
                   ($user->roles && $user->roles->pluck('name')->contains($role));

        if (!$hasRole) {
            abort(403, 'Unauthorized access. You do not have the required role.');
        }

        return $next($request);
    }
}