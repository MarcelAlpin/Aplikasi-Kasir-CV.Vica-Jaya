<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

class AktivitasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && !$request->has('_log_aktivitas_tercatat')) {
            Aktivitas::create([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'halaman'    => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'keterangan' => null,
            ]);
        }

        return $response;
    }
}
