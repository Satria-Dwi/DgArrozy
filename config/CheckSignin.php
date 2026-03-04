<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('logged_in')) {
            return redirect('/signin')->with('signinneror', 'Silakan login terlebih dahulu');
        }
        return $next($request);
    }
}
