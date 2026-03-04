<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DgarrozyAuth
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        // 1. Cek login admin via session
        if (!session()->has('admin_login')) {
            return redirect('/signin');
        }

        // 2. Cek akun aktif
        if (session('admin_account_is_active') != 1) {
            session()->forget([
                'admin_login',
                'admin_account_id',
                'admin_account_email',
                'admin_role_id',
                'admin_role_code',
                'admin_role_name',
                'admin_account_is_active',
            ]);

            return redirect('/signin')
                ->with('signinneror', 'Akun tidak aktif');
        }

        // 3. Cek role
        if ($role) {
            $allowedRoles = explode('|', $role);
            $userRoleCode = session('admin_role_code');

            if (!in_array($userRoleCode, $allowedRoles)) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
