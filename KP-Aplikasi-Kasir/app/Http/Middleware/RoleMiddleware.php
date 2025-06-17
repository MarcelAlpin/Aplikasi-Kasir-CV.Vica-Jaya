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
        // Check if user has the required role
        if ($request->user() && $request->user()->role !== $role) {
            // Return 403 Forbidden response for users without proper role
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
