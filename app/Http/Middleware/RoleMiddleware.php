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

        // Check specific role access
        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Access denied. Administrator privileges required.');
                }
                break;
            case 'cashier':
                if (!$user->isCashier()) {
                    abort(403, 'Access denied. Cashier privileges required.');
                }
                break;
            default:
                abort(403, 'Invalid role specified.');
        }

        return $next($request);
    }
}