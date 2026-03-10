<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimrsRoleManajemen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $dept_id = session('simrs_dept_id');
        $dept = session('simrs_dept');

        if (
            $dept_id === '06' ||
            $dept === 'MANAJEMEN' ||
            $dept_id === 'IT' ||
            $dept === 'TEKNOLOGI INFORMASI'
        ) {
            return $next($request);
        }

        abort(403, 'Tidak memiliki akses');
    }
}
