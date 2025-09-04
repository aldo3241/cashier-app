<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin can access everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Handle multiple roles (comma-separated)
        $allowedRoles = explode(',', $role);
        $userRole = $user->getRoleName();
        
        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $allowedRole) {
            $allowedRole = trim($allowedRole);
            
            switch ($allowedRole) {
                case 'admin':
                    if ($user->isAdmin()) {
                        return $next($request);
                    }
                    break;
                case 'cashier':
                    if ($user->isCashier()) {
                        return $next($request);
                    }
                    break;
                default:
                    // Check if user's role matches the allowed role
                    if ($userRole === $allowedRole) {
                        return $next($request);
                    }
            }
        }

        // If we get here, user doesn't have any of the required roles
        abort(403, 'Access denied. Required roles: ' . implode(', ', $allowedRoles));
    }
}