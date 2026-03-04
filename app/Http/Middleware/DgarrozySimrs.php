<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DgarrozySimrs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1️⃣ cek login dulu
        if (!session('simrs_login')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Session login habis atau akun tidak aktif'
                ], 401);
            }
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        // 2️⃣ cek role (jika ada parameter role)
        if (!empty($roles) && !in_array(session('simrs_tipe'), $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anda tidak memiliki akses'
                ], 403);
            }
            return redirect('/marrozy')->with('error', 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
