<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SimrsRolePerawat
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
        $dept = strtoupper(trim(session('simrs_dept', '')));
        $dept_id = (string) session('simrs_dept_id', '');

        $jabatan = strtolower(
            preg_replace('/\s+/u', ' ', trim(session('simrs_jbtn', '')))
        );

        $isManajemen = str_contains($dept, 'MANAJEMEN');

        // FIX UTAMA DI SINI
        $isIT = str_contains($dept, 'TEKNOLOGI INFORMASI');

        $isRM = str_contains($dept, 'REKAM MEDIK');

        $isDeptIdAllowed = in_array($dept_id, ['06', '07']);

        $isPerawat = str_contains($jabatan, 'perawat');

        if ($isManajemen || $isIT || $isRM || $isDeptIdAllowed || $isPerawat) {
            return $next($request);
        }

        abort(403, 'Tidak memiliki akses');
    }
}
