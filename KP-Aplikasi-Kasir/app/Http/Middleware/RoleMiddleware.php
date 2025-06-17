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
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        // Check if user has the required role
        if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
            // If not, abort with a 403 Unauthorized response
            abort(403, 'Tidak ada izin untuk mengakses.');
        }

        return $next($request);
    }
}
