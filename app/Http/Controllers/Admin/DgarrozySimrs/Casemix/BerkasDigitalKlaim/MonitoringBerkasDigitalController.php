<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Casemix\BerkasDigitalKlaim;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MonitoringBerkasDigitalController extends Controller
{
    public function index()
    {
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silahkan login dulu');
        }

        return view('simrs.casemix.cekberkasdigital.index', [
            'title' =>  'Cek Berkas Digital',
            'user'  =>  [
                'nik'           =>  session('simrs_nik'),
                'nama'          =>  session('simrs_nama'),
                'departemen'    =>  session('simrs_dept'),
                'jabatan'       =>  session('simrs_jbtn'),
                'tipe'          =>  session('simrs_tipe'),
                'spesialis'     =>  session('simrs_sps'),
            ],
        ]);
    }

    public function getMonitoringBerkas(Request $request)
    {
        $query = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('nota_jalan as nj', 'rp.no_rawat', '=', 'nj.no_rawat')
            ->leftJoin('nota_inap as ni', 'rp.no_rawat', '=', 'ni.no_rawat')
            ->leftJoin('bridging_sep as bs', function ($join) {
                $join->on('rp.no_rawat', '=', 'bs.no_rawat')
                    ->whereRaw('bs.no_sep = (SELECT MAX(no_sep) FROM bridging_sep WHERE no_rawat = rp.no_rawat)');
            })

            ->select([
                'rp.no_rawat',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.status_lanjut',
                'rp.kd_poli',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'd.nm_dokter',

                DB::raw("COALESCE(bs.no_sep, '-') as no_sep"),
                DB::raw("COALESCE(ni.tanggal, nj.tanggal) as tgl_closing"),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM resume_pasien
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM resume_pasien_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_resume
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM billing
                        WHERE no_rawat = rp.no_rawat
                        LIMIT 1
                    ) as ada_billing
                "),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM pemeriksaan_ralan
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM pemeriksaan_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_cppt
                "),

                DB::raw("
                            (
                                COALESCE(
                                    (
                                        SELECT GROUP_CONCAT(
                                            CONCAT(
                                                DATE_FORMAT(pr.tgl_perawatan,'%d-%m-%Y'),
                                                ' ',
                                                pr.jam_rawat,
                                                ' - ',
                                                pr.nip
                                            )
                                            SEPARATOR '||'
                                        )
                                        FROM pemeriksaan_ralan pr
                                        WHERE pr.no_rawat = rp.no_rawat
                                    ),
                                    (
                                        SELECT GROUP_CONCAT(
                                            CONCAT(
                                                DATE_FORMAT(pr.tgl_perawatan,'%d-%m-%Y'),
                                                ' ',
                                                pr.jam_rawat,
                                                ' - ',
                                                pr.nip
                                            )
                                            SEPARATOR '||'
                                        )
                                        FROM pemeriksaan_ranap pr
                                        WHERE pr.no_rawat = rp.no_rawat
                                    )
                                )
                            ) as cppt_isi
                            "),

                DB::raw("
                        (
                            COALESCE(
                                (
                                    SELECT GROUP_CONCAT(
                                        DISTINCT d.nm_dokter
                                        SEPARATOR ', '
                                    )
                                    FROM pemeriksaan_ralan pr
                                    JOIN dokter d
                                        ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                ),
                                (
                                    SELECT GROUP_CONCAT(
                                        DISTINCT d.nm_dokter
                                        SEPARATOR ', '
                                    )
                                    FROM pemeriksaan_ranap pr
                                    JOIN dokter d
                                        ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                )
                            )
                        ) as dokter_pengisi_cppt
                        "),

                DB::raw("
                        (
                            CASE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM pemeriksaan_ralan pr
                                    WHERE pr.no_rawat = rp.no_rawat
                                )
                                THEN (
                                    SELECT COUNT(DISTINCT d.kd_dokter)
                                    FROM pemeriksaan_ralan pr
                                    JOIN dokter d
                                        ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                )

                                WHEN EXISTS (
                                    SELECT 1
                                    FROM pemeriksaan_ranap pr
                                    WHERE pr.no_rawat = rp.no_rawat
                                )
                                THEN (
                                    SELECT COUNT(DISTINCT d.kd_dokter)
                                    FROM pemeriksaan_ranap pr
                                    JOIN dokter d
                                        ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                )

                                ELSE 0
                            END
                        ) as jumlah_dokter_cppt
                        "),
                DB::raw("
                        (
                            CASE
                                WHEN EXISTS (
                                    SELECT 1
                                    FROM pemeriksaan_ralan pr
                                    JOIN dokter d ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                )
                                THEN 1

                                WHEN EXISTS (
                                    SELECT 1
                                    FROM pemeriksaan_ranap pr
                                    JOIN dokter d ON d.kd_dokter = pr.nip
                                    WHERE pr.no_rawat = rp.no_rawat
                                )
                                THEN 1

                                ELSE 0
                            END
                        ) as ada_cppt_dokter
                        "),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_igd
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_ralan
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_asmed
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM data_triase_igd
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_triase
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM operasi
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_op
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM periksa_lab
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_lab
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM periksa_radiologi
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_rad
                "),
            ])
            ->where(function ($q) {
                $q->whereNotNull('ni.tanggal')
                    ->orWhereNotNull('nj.tanggal');
            })
            ->when($request->search, function ($q, $search) {
                $q->where(function ($x) use ($search) {
                    $x->where('rp.no_rawat', 'like', "%{$search}%")
                        ->orWhere('p.no_rkm_medis', 'like', "%{$search}%")
                        ->orWhere('p.nm_pasien', 'like', "%{$search}%")
                        ->orWhere('d.nm_dokter', 'like', "%{$search}%")
                        ->orWhere('bs.no_sep', 'like', "%{$search}%");
                });
            })
            ->when($request->tanggal_dari, function ($q, $tgl) {
                $q->whereDate(
                    DB::raw('COALESCE(ni.tanggal, nj.tanggal)'),
                    '>=',
                    $tgl
                );
            })
            ->when($request->tanggal_sampai, function ($q, $tgl) {
                $q->whereDate(
                    DB::raw('COALESCE(ni.tanggal, nj.tanggal)'),
                    '<=',
                    $tgl
                );
            })
            ->whereRaw("COALESCE(ni.tanggal, nj.tanggal) <= ?", [Carbon::today()])
            ->when(
                !$request->filled('search')
                    && !$request->filled('tanggal_dari')
                    && !$request->filled('tanggal_sampai'),
                function ($q) {
                    $q->whereDate(
                        DB::raw('COALESCE(ni.tanggal, nj.tanggal)'),
                        Carbon::today()
                    );
                }
            )
            ->orderByDesc(DB::raw("COALESCE(ni.tanggal, nj.tanggal)"))
            ->orderByDesc('rp.no_rawat');
        return response()->json(
            $query->paginate($request->get('per_page', 20))
        );
    }

    public function getMonitoringBerkasCoba(Request $request)
    {
        $query = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('nota_jalan as nj', 'rp.no_rawat', '=', 'nj.no_rawat')
            ->leftJoin('nota_inap as ni', 'rp.no_rawat', '=', 'ni.no_rawat')
            ->leftJoin('bridging_sep as bs', function ($join) {
                $join->on('rp.no_rawat', '=', 'bs.no_rawat')
                    ->whereRaw('bs.no_sep = (SELECT MAX(no_sep) FROM bridging_sep WHERE no_rawat = rp.no_rawat)');
            })

            ->select([
                'rp.no_rawat',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.status_lanjut',
                'rp.kd_poli',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'd.nm_dokter',


                DB::raw("COALESCE(bs.no_sep, '-') as no_sep"),
                DB::raw("COALESCE(ni.tanggal, nj.tanggal) as tgl_closing"),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM resume_pasien
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM resume_pasien_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_resume
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM billing
                        WHERE no_rawat = rp.no_rawat
                        LIMIT 1
                    ) as ada_billing
                "),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM pemeriksaan_ralan
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM pemeriksaan_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_cppt
                "),

                // DB::raw("
                //     COALESCE(
                //         (
                //             SELECT pr.nip
                //             FROM pemeriksaan_ralan pr
                //             WHERE pr.no_rawat = rp.no_rawat
                //             LIMIT 1
                //         ),
                //         (
                //             SELECT pr.nip
                //             FROM pemeriksaan_ranap pr
                //             WHERE pr.no_rawat = rp.no_rawat
                //             LIMIT 1
                //         )
                //     ) as nip_cppt
                //     "),

                DB::raw("
                    (
                        SELECT GROUP_CONCAT(
                            CONCAT(
                                DATE_FORMAT(pr.tgl_perawatan,'%d-%m-%Y'),
                                ' ',
                                pr.jam_rawat,
                                ' - ',
                                pr.nip
                            )
                            SEPARATOR '||'
                        )
                        FROM pemeriksaan_ralan pr
                        WHERE pr.no_rawat = rp.no_rawat
                    ) as cppt_isi
                    "),

                // DB::raw("
                //         (
                //             SELECT COUNT(*)
                //             FROM (
                //                 SELECT nip
                //                 FROM pemeriksaan_ralan
                //                 WHERE no_rawat = rp.no_rawat

                //                 UNION ALL

                //                 SELECT nip
                //                 FROM pemeriksaan_ranap
                //                 WHERE no_rawat = rp.no_rawat
                //             ) x
                //             WHERE x.nip = rp.kd_dokter
                //         ) as jumlah_cppt_dpjp
                //         "),

                // DB::raw("
                //         (
                //             EXISTS(
                //                 SELECT 1
                //                 FROM pemeriksaan_ralan
                //                 WHERE no_rawat = rp.no_rawat
                //                 AND nip = rp.kd_dokter
                //             )
                //             OR
                //             EXISTS(
                //                 SELECT 1
                //                 FROM pemeriksaan_ranap
                //                 WHERE no_rawat = rp.no_rawat
                //                 AND nip = rp.kd_dokter
                //             )
                //         ) as cppt_dpjp
                //         "),

                DB::raw("
                            (
                                SELECT GROUP_CONCAT(
                                    DISTINCT d.nm_dokter
                                    SEPARATOR ', '
                                )
                                FROM pemeriksaan_ralan pr
                                JOIN dokter d
                                    ON d.kd_dokter = pr.nip
                                WHERE pr.no_rawat = rp.no_rawat
                            ) as dokter_pengisi_cppt
                            "),

                DB::raw("
                        (
                            SELECT COUNT(DISTINCT d.kd_dokter)
                            FROM pemeriksaan_ralan pr
                            JOIN dokter d
                                ON d.kd_dokter = pr.nip
                            WHERE pr.no_rawat = rp.no_rawat
                        ) as jumlah_dokter_cppt
                        "),

                // DB::raw("
                //     (
                //         EXISTS(
                //             SELECT 1
                //             FROM pemeriksaan_ralan
                //             WHERE nip = d.kd_dokter
                //             )
                //             OR
                //             EXISTS(
                //                 SELECT 1
                //                 FROM pemeriksaan_ranap
                //                 WHERE nip = d.kd_dokter
                //         )
                //     ) as ada_cppt_dokter
                // "),

                DB::raw("
                    (
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_igd
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_ralan
                            WHERE no_rawat = rp.no_rawat
                        )
                        OR
                        EXISTS(
                            SELECT 1
                            FROM penilaian_medis_ranap
                            WHERE no_rawat = rp.no_rawat
                        )
                    ) as ada_asmed
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM data_triase_igd
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_triase
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM operasi
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_op
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM periksa_lab
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_lab
                "),

                DB::raw("
                    EXISTS(
                        SELECT 1
                        FROM periksa_radiologi
                        WHERE no_rawat = rp.no_rawat
                    ) as ada_rad
                "),
            ])
            // ->where(function ($q) {
            //     $q->whereNotNull('ni.tanggal')
            //         ->orWhereNotNull('nj.tanggal');
            // })
            // ->when($request->search, function ($q, $search) {
            //     $q->where(function ($x) use ($search) {
            //         $x->where('rp.no_rawat', 'like', "%{$search}%")
            //             ->orWhere('p.no_rkm_medis', 'like', "%{$search}%")
            //             ->orWhere('p.nm_pasien', 'like', "%{$search}%")
            //             ->orWhere('d.nm_dokter', 'like', "%{$search}%")
            //             ->orWhere('bs.no_sep', 'like', "%{$search}%");
            //     });
            // })
            // ->when($request->tanggal_dari, function ($q, $tgl) {
            //     $q->whereDate(
            //         DB::raw('COALESCE(ni.tanggal, nj.tanggal)'),
            //         '>=',
            //         $tgl
            //     );
            // })
            // ->when($request->tanggal_sampai, function ($q, $tgl) {
            //     $q->whereDate(
            //         DB::raw('COALESCE(ni.tanggal, nj.tanggal)'),
            //         '<=',
            //         $tgl
            //     );
            // })
            // ->whereRaw("COALESCE(ni.tanggal, nj.tanggal) <= ?", [Carbon::today()])
            // ->orderByDesc(DB::raw("COALESCE(ni.tanggal, nj.tanggal)"))
            ->orderByDesc('rp.no_rawat');
        return response()->json(
            $query->paginate($request->get('per_page', 1500))
        );
    }
}
