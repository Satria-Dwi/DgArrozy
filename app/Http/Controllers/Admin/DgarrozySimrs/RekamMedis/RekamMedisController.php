<?php

namespace App\Http\Controllers\admin\DgarrozySimrs\RekamMedis;

use App\Exports\PasienExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RekamMedisController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.rekam_medis.index', [
            'title' => 'Rekam Medis',
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

    public function getDataPasienRalan(Request $request)
    {
        $query = DB::table('resume_pasien as res')
            ->join('reg_periksa as rp', 'res.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')
            ->leftJoin('poliklinik as poli', 'rp.kd_poli', '=', 'poli.kd_poli')
            ->join('penyakit as p', function ($join) {
                $join->on('p.kd_penyakit', '=', DB::raw("
                COALESCE(NULLIF(res.kd_diagnosa_sekunder, ''), res.kd_diagnosa_utama)
            "));
            })
            ->where('rp.status_lanjut', 'Ralan')
            ->select(
                'rp.tgl_registrasi as tanggal_rawat',
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'ps.nm_pasien',
                DB::raw("COALESCE(NULLIF(res.kd_diagnosa_sekunder, ''), res.kd_diagnosa_utama) as kode_penyakit"),
                'p.nm_penyakit as nama_penyakit',
                'rp.status_lanjut as status',
                'rp.stts_daftar as kasus',
                'ps.jk',
                'ps.no_ktp as nik',
                'poli.nm_poli',
                DB::raw("
                CONCAT(
                    TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()), ' th ',
                    TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12, ' bl ',
                    DATEDIFF(
                        CURDATE(),
                        DATE_ADD(
                            DATE_ADD(ps.tgl_lahir,
                                INTERVAL TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) YEAR
                            ),
                            INTERVAL TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12 MONTH
                        )
                    ), ' hr'
                ) as umur
            ")
            );

        // FILTER TANGGAL
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('rp.tgl_registrasi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        // FILTER UMUR TAHUN
        if ($request->filled('umur_operator') && $request->filled('umur_tahun')) {
            // Contoh: umur_operator = '<', umur_tahun = 10
            $operator = $request->umur_operator; // <, <=, >, >=, =, !=
            $tahun = (int) $request->umur_tahun;

            $query->whereRaw("TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) $operator ?", [$tahun]);
        }

        // FILTER JENIS KELAMIN
        if ($request->filled('jk')) {
            $query->where('ps.jk', $request->jk);
        }

        // FILTER KODE PENYAKIT / NAMA PENYAKIT (free text)
        if ($request->filled('kode_penyakit')) {
            $kode = $request->kode_penyakit;
            $query->where(function ($q) use ($kode) {
                $q->where('res.kd_diagnosa_utama', 'like', "%{$kode}%")
                    ->orWhere('res.kd_diagnosa_sekunder', 'like', "%{$kode}%")
                    ->orWhere('p.nm_penyakit', 'like', "%{$kode}%");
            });
        }

        $query->orderBy('rp.tgl_registrasi', 'desc');

        return response()->json(
            $query->paginate($request->get('per_page', 20))
        );
    }

    public function getDataPasienRanap(Request $request)
    {
        $query = DB::table('reg_periksa as rp')

            ->leftJoin('resume_pasien_ranap as res', 'res.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftJoin('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->leftJoin('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->leftJoin('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')

            ->leftJoin('penyakit as p', function ($join) {
                $join->on('p.kd_penyakit', '=', DB::raw("
                COALESCE(
                    NULLIF(TRIM(res.kd_diagnosa_utama), ''),
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                )
            "));
            })

            // ✅ TAMPIL JIKA SALAH SATU ADA
            ->whereRaw("
            (
                (res.kd_diagnosa_utama IS NOT NULL AND TRIM(res.kd_diagnosa_utama) != '' AND TRIM(res.kd_diagnosa_utama) != '-')
                OR
                (ki.diagnosa_akhir IS NOT NULL AND TRIM(ki.diagnosa_akhir) != '' AND TRIM(ki.diagnosa_akhir) != '-')
            )
        ")

            ->select(
                'rp.tgl_registrasi as tanggal_rawat',
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'ps.nm_pasien',

                DB::raw("
                COALESCE(
                    NULLIF(TRIM(res.kd_diagnosa_utama), ''),
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                ) as kode_penyakit
            "),

                'p.nm_penyakit',

                DB::raw("
                COALESCE(
                    p.nm_penyakit,
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                ) as diagnosa_final
            "),

                DB::raw("'Ranap' as status"),
                'rp.stts_daftar as kasus',
                'ps.jk',
                'ps.no_ktp as nik',
                'k.kd_kamar',
                'b.nm_bangsal as nm_kamar',

                DB::raw("
                CONCAT(
                    TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()), ' th ',
                    TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12, ' bl ',
                    DATEDIFF(
                        CURDATE(),
                        DATE_ADD(
                            DATE_ADD(ps.tgl_lahir,
                                INTERVAL TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) YEAR
                            ),
                            INTERVAL TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12 MONTH
                        )
                    ), ' hr'
                ) as umur
            ")
            );

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('rp.tgl_registrasi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('rp.tgl_registrasi', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('rp.tgl_registrasi', '<=', $request->tanggal_akhir);
        }

        // FILTER UMUR TAHUN
        if ($request->filled('umur_operator') && $request->filled('umur_tahun')) {
            // Contoh: umur_operator = '<', umur_tahun = 10
            $operator = $request->umur_operator; // <, <=, >, >=, =, !=
            $tahun = (int) $request->umur_tahun;

            $query->whereRaw("TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) $operator ?", [$tahun]);
        }

        // FILTER JENIS KELAMIN
        if ($request->filled('jk')) {
            $query->where('ps.jk', $request->jk);
        }

        // FILTER KODE PENYAKIT / NAMA PENYAKIT (free text)
        if ($request->filled('kode_penyakit')) {
            $kode = $request->kode_penyakit;
            $query->where(function ($q) use ($kode) {
                $q->where('res.kd_diagnosa_utama', 'like', "%{$kode}%")
                    ->orWhere('res.kd_diagnosa_sekunder', 'like', "%{$kode}%")
                    ->orWhere('p.nm_penyakit', 'like', "%{$kode}%");
            });
        }

        // Filter Diagnosa Akhir (kamar_inap)
        if ($request->filled('diagnosa_final')) {
            $keyword = $request->diagnosa_final;
            $query->where('ki.diagnosa_akhir', 'like', "%{$keyword}%");
        }

        $query->orderBy('rp.tgl_registrasi', 'desc');

        return response()->json(
            $query->paginate($request->get('per_page', 20))
        );
    }

    private function queryRalan(Request $request)
    {
        $query = DB::table('resume_pasien as res')
            ->join('reg_periksa as rp', 'res.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')
            ->leftJoin('poliklinik as poli', 'rp.kd_poli', '=', 'poli.kd_poli')
            ->join('penyakit as p', function ($join) {
                $join->on('p.kd_penyakit', '=', DB::raw("
                COALESCE(NULLIF(res.kd_diagnosa_sekunder, ''), res.kd_diagnosa_utama)
            "));
            })
            ->where('rp.status_lanjut', 'Ralan')

            ->select(
                'rp.tgl_registrasi as tanggal_rawat',
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'ps.nm_pasien',
                'ps.jk',
                'ps.no_ktp as nik',

                DB::raw("COALESCE(NULLIF(res.kd_diagnosa_sekunder, ''), res.kd_diagnosa_utama) as kode_penyakit"),

                'p.nm_penyakit as nama_penyakit',
                'rp.status_lanjut as status',
                'rp.stts_daftar as kasus',
                'poli.nm_poli',

                DB::raw("
                CONCAT(
                    TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()), ' th ',
                    TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12, ' bl ',
                    DATEDIFF(
                        CURDATE(),
                        DATE_ADD(
                            DATE_ADD(ps.tgl_lahir,
                                INTERVAL TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) YEAR
                            ),
                            INTERVAL TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12 MONTH
                        )
                    ), ' hr'
                ) as umur
            ")
            );

        // FILTER TANGGAL
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('rp.tgl_registrasi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        // FILTER UMUR
        if ($request->filled('umur_operator') && $request->filled('umur_tahun')) {
            $operator = $request->umur_operator;
            $tahun = (int) $request->umur_tahun;

            $query->whereRaw(
                "TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) $operator ?",
                [$tahun]
            );
        }

        // FILTER JK
        if ($request->filled('jk')) {
            $query->where('ps.jk', $request->jk);
        }

        // FILTER PENYAKIT
        if ($request->filled('kode_penyakit')) {
            $kode = $request->kode_penyakit;
            $query->where(function ($q) use ($kode) {
                $q->where('res.kd_diagnosa_utama', 'like', "%{$kode}%")
                    ->orWhere('res.kd_diagnosa_sekunder', 'like', "%{$kode}%")
                    ->orWhere('p.nm_penyakit', 'like', "%{$kode}%");
            });
        }

        return $query->orderBy('rp.tgl_registrasi', 'desc');
    }

    private function queryRanap(Request $request)
    {
        $query = DB::table('reg_periksa as rp')

            ->leftJoin('resume_pasien_ranap as res', 'res.no_rawat', '=', 'rp.no_rawat')
            ->leftJoin('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->leftJoin('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->leftJoin('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->leftJoin('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')

            ->leftJoin('penyakit as p', function ($join) {
                $join->on('p.kd_penyakit', '=', DB::raw("
                COALESCE(
                    NULLIF(TRIM(res.kd_diagnosa_utama), ''),
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                )
            "));
            })

            ->where('rp.status_lanjut', 'Ranap')

            ->whereRaw("
            (
                (res.kd_diagnosa_utama IS NOT NULL AND TRIM(res.kd_diagnosa_utama) != '' AND TRIM(res.kd_diagnosa_utama) != '-')
                OR
                (ki.diagnosa_akhir IS NOT NULL AND TRIM(ki.diagnosa_akhir) != '' AND TRIM(ki.diagnosa_akhir) != '-')
            )
        ")

            ->select(
                'rp.tgl_registrasi as tanggal_rawat',
                'rp.no_rawat',
                'rp.no_rkm_medis',
                'ps.nm_pasien',
                'ps.jk',
                'ps.no_ktp as nik',

                DB::raw("
                COALESCE(
                    NULLIF(TRIM(res.kd_diagnosa_utama), ''),
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                ) as kode_penyakit
            "),

                'p.nm_penyakit',

                DB::raw("
                COALESCE(
                    p.nm_penyakit,
                    NULLIF(TRIM(ki.diagnosa_akhir), '')
                ) as diagnosa_final
            "),

                DB::raw("'Ranap' as status"),
                'rp.stts_daftar as kasus',
                'b.nm_bangsal as nm_kamar',

                DB::raw("
                CONCAT(
                    TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()), ' th ',
                    TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12, ' bl ',
                    DATEDIFF(
                        CURDATE(),
                        DATE_ADD(
                            DATE_ADD(ps.tgl_lahir,
                                INTERVAL TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) YEAR
                            ),
                            INTERVAL TIMESTAMPDIFF(MONTH, ps.tgl_lahir, CURDATE()) % 12 MONTH
                        )
                    ), ' hr'
                ) as umur
            ")
            );

        // FILTER TANGGAL
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('rp.tgl_registrasi', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        // FILTER UMUR
        if ($request->filled('umur_operator') && $request->filled('umur_tahun')) {
            $operator = $request->umur_operator;
            $tahun = (int) $request->umur_tahun;

            $query->whereRaw(
                "TIMESTAMPDIFF(YEAR, ps.tgl_lahir, CURDATE()) $operator ?",
                [$tahun]
            );
        }

        // FILTER JK
        if ($request->filled('jk')) {
            $query->where('ps.jk', $request->jk);
        }

        // FILTER PENYAKIT
        if ($request->filled('kode_penyakit')) {
            $kode = $request->kode_penyakit;
            $query->where(function ($q) use ($kode) {
                $q->where('res.kd_diagnosa_utama', 'like', "%{$kode}%")
                    ->orWhere('ki.diagnosa_akhir', 'like', "%{$kode}%")
                    ->orWhere('p.nm_penyakit', 'like', "%{$kode}%");
            });
        }

        return $query->orderBy('rp.tgl_registrasi', 'desc');
    }

    public function exportRalan(Request $request)
    {
        $data = $this->queryRalan($request)->get();

        return Excel::download(
            new PasienExport($data, 'Ralan'),
            'Data_Pasien_Ralan.xlsx'
        );
    }

    public function exportRanap(Request $request)
    {
        $data = $this->queryRanap($request)->get();

        return Excel::download(
            new PasienExport($data, 'Ranap'),
            'Data_Pasien_Ranap.xlsx'
        );
    }
}
