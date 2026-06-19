<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardSimrsController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.dashboarduser.index', [
            'title' => 'Beranda',
            'user' => [
                'nik'        => session('simrs_nik'),
                'nama'       => session('simrs_nama'),
                'departemen' => session('simrs_dept'),
                'jabatan'    => session('simrs_jbtn'),
                'tipe'       => session('simrs_tipe'),     // pegawai / petugas / dokter
                'spesialis'  => session('simrs_sps'),      // null jika bukan dokter
            ],
        ]);
    }

    public function pasienHarian()
    {
        try {
            $data = DB::table('reg_periksa')
                ->select(
                    DB::raw("DATE(tgl_registrasi) as tanggal"),
                    DB::raw("SUM(CASE WHEN kd_pj = 'BPJ' THEN 1 ELSE 0 END) as bpjs"),
                    DB::raw("SUM(CASE WHEN kd_pj <> 'BPJ' THEN 1 ELSE 0 END) as umum"),
                    DB::raw("COUNT(*) as total")
                )
                ->whereBetween('tgl_registrasi', [
                    date('Y-m-d', strtotime('-6 days')),
                    date('Y-m-d')
                ])
                ->whereIn('stts', ['Belum', 'Sudah'])
                ->groupBy(DB::raw("DATE(tgl_registrasi)"))
                ->orderBy('tanggal')
                ->get();

            $tanggal = [];
            $bpjs    = [];
            $umum    = [];
            $total   = [];

            foreach ($data as $row) {
                $tanggal[] = date('d M', strtotime($row->tanggal));
                $bpjs[]    = (int) $row->bpjs;
                $umum[]    = (int) $row->umum;
                $total[]   = (int) $row->total;
            }

            return response()->json([
                'tanggal' => $tanggal,
                'bpjs'    => $bpjs,
                'umum'    => $umum,
                'total'   => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg'   => $e->getMessage()
            ], 500);
        }
    }

    public function totalPasienDokterHariIni()
    {
        try {
            // pastikan hanya dokter
            if (session('simrs_tipe') !== 'dokter') {
                return response()->json([
                    'error' => true,
                    'msg'   => 'User bukan dokter'
                ], 403);
            }

            $kdDokter = session('simrs_nik'); // NIK = kd_dokter

            $total = DB::table('reg_periksa')
                ->whereDate('tgl_registrasi', date('Y-m-d'))
                ->where('kd_dokter', $kdDokter)
                ->whereIn('stts', ['Belum', 'Sudah'])
                ->count();

            return response()->json([
                'kd_dokter'    => $kdDokter,
                'total_pasien' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg'   => $e->getMessage()
            ], 500);
        }
    }

    public function chartPasien()
    {
        $kdDokter = session('simrs_nik'); // NIK dokter login

        $data = DB::table('reg_periksa')
            ->select(
                DB::raw('DATE(tgl_registrasi) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('kd_dokter', $kdDokter)
            ->whereBetween('tgl_registrasi', [
                Carbon::now()->subDays(6)->toDateString(),
                Carbon::now()->toDateString()
            ])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 🔹 siapkan 7 hari full
        $labels = [];   // untuk hari + tanggal
        $series = [];   // untuk chart

        for ($i = 6; $i >= 0; $i--) {

            $tgl = Carbon::now()->subDays($i);

            // cari data sesuai tanggal
            $found = $data->firstWhere(
                'tanggal',
                $tgl->toDateString()
            );

            $series[] = $found ? (int) $found->total : 0;

            $labels[] = [
                'hari' => $tgl->translatedFormat('l'), // Senin
                'tgl'  => $tgl->format('d'),            // 02
                'full' => $tgl->format('Y-m-d')
            ];
        }

        return response()->json([
            'data'   => $series,
            'labels' => $labels
        ]);
    }

    public function totalPasienRawatInapDokterHariIni(Request $request)
    {
        if (session('simrs_tipe') !== 'dokter') {
            return response()->json([
                'error' => true,
                'msg'   => 'User bukan dokter'
            ], 403);
        }

        $kdDokter = session('simrs_nik');

        $query = DB::table('dpjp_ranap as dr')
            ->join('kamar_inap as ki', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->where('dr.kd_dokter', $kdDokter);

        // =====================================
        // 🔥 JIKA ADA FILTER RANGE
        // =====================================
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {

            $query->whereBetween('ki.tgl_masuk', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        } else {

            // =====================================
            // 🔥 DEFAULT → PASIEN MASIH AKTIF
            // =====================================
            $query->where(function ($q) {
                $q->whereNull('ki.tgl_keluar')
                    ->orWhere('ki.tgl_keluar', '')
                    ->orWhere('ki.tgl_keluar', '0000-00-00');
            })
                ->where(function ($q) {
                    $q->whereNull('ki.jam_keluar')
                        ->orWhere('ki.jam_keluar', '');
                });
        }

        $jumlah = $query
            ->distinct('dr.no_rawat')
            ->count('dr.no_rawat');

        return response()->json([
            'error' => false,
            'jumlah_pasien_rawat_inap' => $jumlah
        ]);
    }

    public function totalPasienRawatJalanDokterHariIni(Request $request)
    {
        try {
            if (session('simrs_tipe') !== 'dokter') {
                return response()->json([
                    'error' => true,
                    'msg'   => 'User bukan dokter'
                ], 403);
            }

            $kdDokter = session('simrs_nik');

            $query = DB::table('reg_periksa as rp')
                ->where('rp.kd_dokter', $kdDokter)
                ->whereIn('rp.stts', ['Belum', 'Sudah'])
                // ❌ exclude yang sudah masuk rawat inap
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('kamar_inap as ki')
                        ->whereColumn('ki.no_rawat', 'rp.no_rawat');
                });

            // 🔥 FILTER TANGGAL
            if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
                $query->whereBetween('rp.tgl_registrasi', [
                    $request->tgl_awal,
                    $request->tgl_akhir
                ]);
            } else {
                // default hari ini
                $query->whereDate('rp.tgl_registrasi', now()->toDateString());
            }

            $total = $query->count();

            return response()->json([
                'error' => false,
                'kd_dokter' => $kdDokter,
                'total_pasien_rawat_jalan' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg'   => $e->getMessage()
            ], 500);
        }
    }

    public function totalOperasiDokterHariIni(Request $request)
    {
        $kdDokter = session('simrs_nik');

        if (!$kdDokter) {
            return response()->json([
                'error' => true,
                'total_operasi' => 0
            ]);
        }

        $query = DB::table('booking_operasi as bo')
            ->join('reg_periksa as rp', 'bo.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('paket_operasi as po', 'bo.kode_paket', '=', 'po.kode_paket')
            ->where('bo.kd_dokter', $kdDokter);

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {

            // 🔥 Jika filter aktif → hanya filter tanggal
            $query->whereBetween('bo.tanggal', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        } else {

            // 🔥 Default → hanya Menunggu
            $query->where('bo.status', 'Menunggu');
        }

        return response()->json([
            'error' => false,
            'total_operasi' => $query->count()
        ]);
    }

    public function pasienRawatJalanHariIni(Request $request)
    {
        $kdDokter = session('simrs_nik');

        $query = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->leftJoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->select(
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'pj.png_jawab',
                'pj.nama_perusahaan'
            )
            ->where('rp.kd_dokter', $kdDokter)
            ->where('rp.status_lanjut', 'Ralan');

        // 🔥 FILTER TANGGAL (tidak diubah)
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('rp.tgl_registrasi', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        } else {
            $query->whereDate('rp.tgl_registrasi', now()->toDateString());
        }

        // ✅ Pagination
        $perPage = $request->get('per_page', 10); // default 10 per halaman

        $data = $query
            ->orderBy('rp.no_rawat', 'asc')
            ->paginate($perPage);

        return response()->json($data);
    }

    public function pasienDetailRalan($no_rawat)
    {
        $data = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('poliklinik as po', 'rp.kd_poli', '=', 'po.kd_poli')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->select(
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'p.alamat',
                'p.jk',
                'p.umur',
                'po.nm_poli',
                'd.nm_dokter',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                // Penanggung jawab
                'pj.png_jawab',
                'pj.nama_perusahaan'
            )
            ->where('rp.no_rawat', $no_rawat)
            ->first();

        if (!$data) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($data);
    }

    public function pasienRawatInap(Request $request)
    {
        $kdDokter = session('simrs_nik');

        $query = DB::table('dpjp_ranap as dr')
            ->join('kamar_inap as ki', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->join('reg_periksa as rp', 'dr.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->leftJoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->where('dr.kd_dokter', $kdDokter);

        // =========================================
        // 🔥 JIKA ADA FILTER RANGE
        // =========================================
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {

            $query->whereBetween('ki.tgl_masuk', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        } else {

            // =========================================
            // 🔥 DEFAULT → PASIEN MASIH AKTIF
            // =========================================
            $query->where(function ($q) {
                $q->whereNull('ki.tgl_keluar')
                    ->orWhere('ki.tgl_keluar', '')
                    ->orWhere('ki.tgl_keluar', '0000-00-00');
            })
                ->where(function ($q) {
                    $q->whereNull('ki.jam_keluar')
                        ->orWhere('ki.jam_keluar', '');
                });
        }

        $data = $query->select(
            'dr.no_rawat',
            'rp.no_rkm_medis',
            'p.nm_pasien',
            'ki.tgl_masuk',
            'ki.tgl_keluar',
            'pj.png_jawab'
        )
            ->distinct('dr.no_rawat')
            ->orderBy('ki.tgl_masuk', 'desc')
            ->paginate(10);

        return response()->json($data);
    }

    public function pasienDetailRanap($no_rawat)
    {
        $kdDokter = session('simrs_nik'); // pastikan = kd_dokter

        $data = DB::table('dpjp_ranap as dr')
            ->join('kamar_inap as ki', 'dr.no_rawat', '=', 'ki.no_rawat')
            ->join('reg_periksa as rp', 'dr.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'dr.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->where('dr.kd_dokter', $kdDokter)
            ->where('dr.no_rawat', $no_rawat)
            // ->where(function ($q) {
            //     $q->whereNull('ki.tgl_keluar')
            //         ->orWhere('ki.tgl_keluar', '')
            //         ->orWhere('ki.tgl_keluar', '0000-00-00');
            // })
            // ->where(function ($q) {
            //     $q->whereNull('ki.jam_keluar')
            //         ->orWhere('ki.jam_keluar', '');
            // })
            ->select(
                'dr.no_rawat',

                // pasien
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.jk',
                'p.tgl_lahir',
                'p.alamat',

                // registrasi
                'rp.tgl_registrasi',
                'rp.jam_reg',
                DB::raw("CONCAT(rp.umurdaftar,' ',rp.sttsumur) as umur"),
                'rp.status_bayar',

                // rawat inap
                'ki.kd_kamar',
                'ki.tgl_masuk',
                'ki.jam_masuk',
                'ki.tgl_keluar',
                'ki.jam_keluar',
                'ki.diagnosa_awal',
                'ki.diagnosa_akhir',
                'ki.stts_pulang',

                // penjamin
                'pj.png_jawab',

                // dokter
                'd.nm_dokter'
            )
            ->first(); // 🔥 DETAIL = 1 DATA
        if ($data) {

            if (
                empty($data->tgl_keluar) ||
                $data->tgl_keluar == '0000-00-00'
            ) {
                $data->tgl_keluar = 'Masih dirawat';
                $data->jam_keluar = 'Masih dirawat';
            }
        }

        if (!$data) {
            return response()->json([
                'message' => 'Data pasien tidak ditemukan atau tidak berhak diakses'
            ], 404);
        }

        return response()->json($data);
    }

    public function operasiDokter(Request $request)
    {
        $kdDokter = session('simrs_nik');

        if (!$kdDokter) {
            return response()->json([
                'current_page' => 1,
                'data' => [],
                'total' => 0,
                'last_page' => 1
            ]);
        }

        $query = DB::table('booking_operasi as bo')
            ->join('reg_periksa as rp', 'bo.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('paket_operasi as po', 'bo.kode_paket', '=', 'po.kode_paket')
            ->where('bo.kd_dokter', $kdDokter);

        // =====================================
        // 🔥 JIKA FILTER AKTIF
        // =====================================
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {

            $query->whereBetween('bo.tanggal', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        } else {

            // 🔥 DEFAULT: hanya status Menunggu
            $query->where('bo.status', 'Menunggu');
        }

        $data = $query->select(
            'bo.no_rawat',
            'rp.no_rkm_medis',
            'p.nm_pasien',
            'po.nm_perawatan',
            'bo.tanggal',
            'bo.jam_mulai',
            'bo.jam_selesai',
            'bo.status'
        )
            ->orderBy('bo.tanggal', 'desc')
            ->orderBy('bo.jam_mulai', 'desc')
            ->paginate(10);

        return response()->json($data);
    }

    public function laporanDokterRealtime()
    {
        $today = date('Y-m-d');

        $data = DB::table('dokter as d')
            ->select(
                'd.kd_dokter',
                'd.nm_dokter',

                // ✅ TOTAL RAWAT JALAN
                DB::raw("
                (
                    SELECT COUNT(*) 
                    FROM reg_periksa rp
                    WHERE rp.kd_dokter = d.kd_dokter
                    AND DATE(rp.tgl_registrasi) = '$today'
                    AND rp.stts IN ('Belum','Sudah')
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM kamar_inap ki
                        WHERE ki.no_rawat = rp.no_rawat
                    )
                ) as total_rawat_jalan
            "),

                // ✅ TOTAL RAWAT INAP
                DB::raw("
                (
                    SELECT COUNT(DISTINCT dr.no_rawat)
                    FROM dpjp_ranap dr
                    JOIN kamar_inap ki ON dr.no_rawat = ki.no_rawat
                    WHERE dr.kd_dokter = d.kd_dokter
                    AND (
                        ki.tgl_keluar IS NULL
                        OR ki.tgl_keluar = ''
                        OR ki.tgl_keluar = '0000-00-00'
                    )
                    AND (
                        ki.jam_keluar IS NULL
                        OR ki.jam_keluar = ''
                    )
                ) as total_rawat_inap
            "),

                // ✅ TOTAL PASIEN = RJ + RI
                DB::raw("
                (
                    (
                        SELECT COUNT(*) 
                        FROM reg_periksa rp
                        WHERE rp.kd_dokter = d.kd_dokter
                        AND DATE(rp.tgl_registrasi) = '$today'
                        AND rp.stts IN ('Belum','Sudah')
                        AND NOT EXISTS (
                            SELECT 1 
                            FROM kamar_inap ki
                            WHERE ki.no_rawat = rp.no_rawat
                        )
                    )
                    +
                    (
                        SELECT COUNT(DISTINCT dr.no_rawat)
                        FROM dpjp_ranap dr
                        JOIN kamar_inap ki ON dr.no_rawat = ki.no_rawat
                        WHERE dr.kd_dokter = d.kd_dokter
                        AND (
                            ki.tgl_keluar IS NULL
                            OR ki.tgl_keluar = ''
                            OR ki.tgl_keluar = '0000-00-00'
                        )
                        AND (
                            ki.jam_keluar IS NULL
                            OR ki.jam_keluar = ''
                        )
                    )
                ) as total_pasien
            ")
            )
            ->whereRaw("d.nm_dokter NOT REGEXP '[0-9]'")
            ->orderByDesc('total_pasien')
            ->get();

        return response()->json($data);
    }

    // public function chartKunjunganPoliHariIni()
    // {
    //     $today = Carbon::today()->toDateString();

    //     $data = DB::table('reg_periksa as r')
    //         ->join('poliklinik as p', 'p.kd_poli', '=', 'r.kd_poli')
    //         ->whereDate('r.tgl_registrasi', $today)
    //         ->where('p.status', '1') // sesuaikan: '1' / 'AKTIF'
    //         ->groupBy('r.kd_poli', 'p.nm_poli')
    //         ->orderBy('p.nm_poli')
    //         ->select(
    //             'p.nm_poli',
    //             DB::raw('COUNT(*) as total')
    //         )
    //         ->get();

    //     return response()->json([
    //         'labels' => $data->pluck('nm_poli'),
    //         'series' => $data->pluck('total'),
    //         'tanggal' => $today
    //     ]);
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'judul' => 'required|string|max:255',
    //         'deskripsi' => 'required|string',
    //         'kategori' => 'required|string|max:100',
    //         'prioritas' => 'required|in:rendah,sedang,tinggi',
    //     ]);

    //     DB::table('dgarrozy_ticketserm')->insert([
    //         'user_nik'       => session('simrs_nik'),
    //         'user_nama'      => session('simrs_nama'),
    //         'user_departemen' => session('simrs_dept'),
    //         'kode_ticket'    => 'TKT-' . time(), // auto generate kode unik
    //         'judul'          => $request->judul,
    //         'deskripsi'      => $request->deskripsi,
    //         'kategori'       => $request->kategori,
    //         'prioritas'      => $request->prioritas,
    //         'status'         => 'open',
    //         'created_at'     => now(),
    //         'updated_at'     => now(),
    //     ]);

    //     return back()->with('success', 'Tiket berhasil dibuat!');
    // }
}
