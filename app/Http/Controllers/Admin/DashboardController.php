<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // =====================
    // VIEW DASHBOARD
    // =====================
    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard RSUD Ar Rozy',
            'active' => 'dashboard',
        ]);
    }

    public function dashboardData()
    {
        $today = Carbon::today()->toDateString();
        $from14 = Carbon::now()->subDays(13)->toDateString();


        return response()->json([
            'summary' => [
                'rawat_inap' => DB::table('kamar_inap')
                    ->where('tgl_masuk', '<=', $today)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('tgl_keluar')
                            ->orWhere('tgl_keluar', '0000-00-00')
                            ->orWhere('tgl_keluar', '>', $today);
                    })->count(),

                'igd' => DB::table('reg_periksa')
                    ->whereBetween('tgl_registrasi', ["$today 00:00:00", "$today 23:59:59"])
                    ->where('kd_poli', 'IGDK')
                    ->count(),

                'poli' => DB::table('reg_periksa')
                    ->whereBetween('tgl_registrasi', ["$today 00:00:00", "$today 23:59:59"])
                    ->where('kd_poli', '!=', 'IGDK')
                    ->count(),

                'operasi' => DB::table('booking_operasi')
                    ->where('tanggal', $today)
                    ->count(),

                'lahir' => DB::table('pasien')
                    ->where('tgl_lahir', $today)
                    ->count(),

                'pasien' => DB::table('pasien')
                    ->count(),
            ],

            'chart_harian' => DB::table('reg_periksa')
                ->selectRaw('DATE(tgl_registrasi) tgl, COUNT(*) total')
                ->where('tgl_registrasi', '>=', $from14)
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->get(),

            'chart_poli_hari_ini' => DB::table('poliklinik as p')
                ->leftJoin('reg_periksa as r', function ($join) use ($today) {
                    $join->on('p.kd_poli', '=', 'r.kd_poli')
                        ->whereBetween('r.tgl_registrasi', ["$today 00:00:00", "$today 23:59:59"]);
                })
                ->select('p.nm_poli', DB::raw('COUNT(r.no_rawat) total'))
                ->groupBy('p.nm_poli')
                ->get(),

            'chart_tahun' => DB::table('pasien')
                ->selectRaw('YEAR(tgl_daftar) tahun, COUNT(*) total')
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->get()
                ->pipe(function ($data) {
                    // format menjadi labels & data
                    return [
                        'labels' => $data->pluck('tahun'),
                        'data' => $data->pluck('total')
                    ];
                }),

            'jenis_kelamin' => DB::table('pasien')
                ->select('jk', DB::raw('COUNT(*) as total'))
                ->groupBy('jk')
                ->get()
                ->pipe(function ($data) {
                    return [
                        'labels' => $data->map(
                            fn($d) =>
                            $d->jk === 'L' ? 'Laki-laki' : 'Perempuan'
                        ),
                        'data' => $data->pluck('total')->map(fn($v) => (int) $v),
                    ];
                }),

            'penjamin' => DB::table('reg_periksa as rp')
                ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
                ->selectRaw('rp.kd_pj, pj.png_jawab, COUNT(*) as cnt')
                ->groupBy('rp.kd_pj', 'pj.png_jawab')
                ->orderBy('cnt', 'desc')
                ->get()
                ->pipe(function ($data) {
                    return [
                        // ⬇️ tampilkan nama, bukan kode
                        'labels' => $data->map(function ($d) {
                            return $d->png_jawab ?: $d->kd_pj;
                        }),
                        'data'   => $data->pluck('cnt')->map(fn($v) => (int) $v),
                    ];
                }),

            'status_kamar' => DB::table('kamar')
                ->selectRaw('status, COUNT(*) as cnt')
                ->groupBy('status')
                ->orderBy('cnt', 'desc')
                ->get()
                ->pipe(function ($data) {
                    return [
                        'labels' => $data->pluck('status'),
                        'data'   => $data->pluck('cnt')
                    ];
                }),

            'domisili_pasien' => DB::table('pasien')
                ->selectRaw('kabupatenpj, COUNT(*) as total')
                ->groupBy('kabupatenpj')
                ->orderByDesc('total')
                ->get()
                ->pipe(function ($data) {
                    return [
                        'labels' => $data->pluck('kabupaten'),
                        'data'   => $data->pluck('total')
                    ];
                }),

            'penyakit_bulan_ini' => DB::table('reg_periksa')
                ->join('diagnosa_pasien', 'diagnosa_pasien.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('penyakit', 'penyakit.kd_penyakit', '=', 'diagnosa_pasien.kd_penyakit')
                ->selectRaw('penyakit.nm_penyakit, COUNT(*) as cnt')
                ->whereMonth('reg_periksa.tgl_registrasi', date('m'))
                ->whereYear('reg_periksa.tgl_registrasi', date('Y'))
                ->groupBy('penyakit.nm_penyakit')
                ->orderBy('cnt', 'desc')
                ->get()
                ->pipe(function ($data) {
                    return [
                        'labels' => $data->pluck('nm_penyakit'),
                        'data'   => $data->pluck('cnt')
                    ];
                }),

            'tempat_tidur_per_bangsal' => DB::table('bangsal as b')
                ->join('kamar as k', 'b.kd_bangsal', '=', 'k.kd_bangsal')
                ->where('b.status', '1')
                ->where('k.statusdata', '1')
                ->select(
                    'b.nm_bangsal',
                    DB::raw('COUNT(k.kd_kamar) as jumlah_bed'),
                    DB::raw("SUM(CASE WHEN k.status = 'ISI' THEN 1 ELSE 0 END) as bed_terisi"),
                    DB::raw("SUM(CASE WHEN k.status = 'KOSONG' THEN 1 ELSE 0 END) as bed_kosong")
                )
                ->groupBy('b.nm_bangsal')
                ->get()
                ->map(function ($item) {
                    $bor = $item->jumlah_bed > 0
                        ? ($item->bed_terisi / $item->jumlah_bed) * 100
                        : 0;

                    return [
                        "nm_bangsal"     => $item->nm_bangsal,
                        "jumlah_bed"     => (int) $item->jumlah_bed,
                        "bed_terisi"     => (int) $item->bed_terisi,
                        "bed_kosong"     => (int) $item->bed_kosong,
                        "persentase_bor" => round($bor, 2),
                    ];
                })
                ->pipe(function ($data) {
                    return [
                        "labels"      => $data->pluck("nm_bangsal")->toArray(),
                        "data_terisi" => $data->pluck("bed_terisi")->toArray(),
                        "data_kosong" => $data->pluck("bed_kosong")->toArray(),
                        "bor"         => $data->pluck("persentase_bor")->toArray(),
                    ];
                }),

        ]);
    }
}
