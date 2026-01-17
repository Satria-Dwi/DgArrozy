<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MainAdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'title' => 'Main Admin',
            'active' => 'mainadmin'
        ]); // Blade yang kamu kirim
    }

    public function pasienSummary()
    {
        return response()->json([
            'total_pasien' => DB::table('pasien')->count()
        ]);
    }

    public function manajemendata(Request $request)
    {
        $from = $request->input('from', now()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        return response()->json([
            'summary' => [
                'rawat_inap' => DB::table('kamar_inap')
                    ->where('tgl_masuk', '<=', $to)
                    ->where(function ($q) use ($from) {
                        $q->whereNull('tgl_keluar')
                            ->orWhere('tgl_keluar', '0000-00-00')
                            ->orWhere('tgl_keluar', '>=', $from);
                    })->count(),
                'reg_pasien' => DB::table('reg_periksa')
                    ->selectRaw('DATE(tgl_registrasi) AS tgl, COUNT(*) AS total')
                    ->whereBetween(
                        DB::raw('DATE(tgl_registrasi)'),
                        [$from, $to]
                    )
                    ->groupBy('tgl')
                    ->orderBy('tgl')
                    ->get(),
                'poli' => DB::table('reg_periksa')
                    ->whereBetween('tgl_registrasi', [$from, $to])
                    ->where('kd_poli', '!=', 'IGDK')
                    ->count(),

            ]
        ]);
    }
}
