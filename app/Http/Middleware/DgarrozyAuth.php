<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DgarrozyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role  Role code(s), dipisah '|'
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // 1. Cek login
        if (!session()->has('dgarrozy_login')) {
            return redirect('/signin');
        }

        // 2. Cek akun aktif
        if (session('account_is_active') != 1) {
            session()->flush();
            return redirect('/signin')->with('signinneror', 'Akun tidak aktif');
        }

        // 3. Cek role jika diberikan
        if ($role) {
            $allowedRoles = explode('|', $role);
            $userRoleCode = session('account_role_code'); // <-- pakai role code
            if (!in_array($userRoleCode, $allowedRoles)) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
