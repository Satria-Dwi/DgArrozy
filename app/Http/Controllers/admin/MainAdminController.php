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
            'title' => 'MArRozzy | Manajemen',
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

                'igd' => DB::table('reg_periksa')
                    ->whereBetween('tgl_registrasi', ["$from", "$to"])
                    ->where('kd_poli', 'IGDK')
                    ->count(),

                'operasi' => DB::table('booking_operasi')
                    ->where('tanggal', ["$from", "$to"])
                    ->count(),

                'lahir' => DB::table('pasien')
                    ->where('tgl_lahir', ["$from", "$to"])
                    ->count()
            ]
        ]);
    }

    public function tempatTidurPerBangsal()
    {
        // Ambil semua bangsal aktif
        $bangsal = DB::table('bangsal')
            ->where('status', '1')
            ->select('kd_bangsal', 'nm_bangsal')
            ->get();

        // Ambil data kamar (1 QUERY)
        $kamar = DB::table('kamar')
            ->where('statusdata', '1')
            ->select(
                'kd_bangsal',
                DB::raw('COUNT(*) as jumlah_bed'),
                DB::raw("SUM(CASE WHEN status = 'ISI' THEN 1 ELSE 0 END) as bed_terisi")
            )
            ->groupBy('kd_bangsal')
            ->get()
            ->keyBy('kd_bangsal');

        // Mapping & hitung BOR
        $result = $bangsal->map(function ($b) use ($kamar) {
            $jumlah = $kamar[$b->kd_bangsal]->jumlah_bed ?? 0;
            $terisi = $kamar[$b->kd_bangsal]->bed_terisi ?? 0;
            $kosong = $jumlah - $terisi;
            $bor = $jumlah > 0 ? ($terisi / $jumlah) * 100 : 0;

            return [
                "nm_bangsal"     => $b->nm_bangsal,
                "jumlah_bed"     => (int) $jumlah,
                "bed_terisi"     => (int) $terisi,
                "bed_kosong"     => (int) $kosong,
                "persentase_bor" => round($bor, 2),
            ];
        });

        // Format untuk chart
        return response()->json([
            "labels"      => $result->pluck("nm_bangsal")->values(),
            "data_terisi" => $result->pluck("bed_terisi")->values(),
            "data_kosong" => $result->pluck("bed_kosong")->values(),
            "bor"         => $result->pluck("persentase_bor")->values(),
        ]);
    }

    public function topPenyakitBulanIni()
    {
        $data = DB::table('reg_periksa')
            ->join('diagnosa_pasien', 'diagnosa_pasien.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('penyakit', 'penyakit.kd_penyakit', '=', 'diagnosa_pasien.kd_penyakit')
            ->selectRaw('penyakit.nm_penyakit, COUNT(*) as cnt')
            ->whereMonth('reg_periksa.tgl_registrasi', date('m'))
            ->whereYear('reg_periksa.tgl_registrasi', date('Y'))
            ->groupBy('penyakit.nm_penyakit')
            ->orderBy('cnt', 'desc')
            ->limit(10) // Ambil Top 10
            ->get();

        return response()->json([
            'labels' => $data->pluck('nm_penyakit'),
            'data'   => $data->pluck('cnt')
        ]);
    }

    public function updatepoli()
    {
        $today = Carbon::today()->toDateString();

        $data = DB::table('poliklinik as p')
            ->leftJoin('reg_periksa as r', function ($join) use ($today) {
                $join->on('p.kd_poli', '=', 'r.kd_poli')
                    ->whereBetween('r.tgl_registrasi', [
                        "$today 00:00:00",
                        "$today 23:59:59"
                    ]);
            })
            ->select(
                'p.nm_poli',
                DB::raw('COUNT(r.no_rawat) as total')
            )
            ->groupBy('p.nm_poli')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'labels' => $data->pluck('nm_poli'),
            'data'   => $data->pluck('total'),
        ]);
    }
}
