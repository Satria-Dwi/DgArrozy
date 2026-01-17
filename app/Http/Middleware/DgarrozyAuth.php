<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DgarrozyAuth
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!session()->has('dgarrozy_login')) {
            return redirect('/signin');
        }

        if (session('account_is_active') != 1) {
            session()->invalidate();
            return redirect('/signin')->with('signinneror', 'Akun tidak aktif');
        }

        if ($role) {
            $roles = explode('|', $role);
            if (!in_array(session('account_role'), $roles)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
