<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonsultasiPerawatController extends Controller
{
    public function KonsultasiPerawat(Request $request)
    {
        try {
            if (session('simrs_tipe') !== 'dokter') {
                return response()->json([
                    'error' => true,
                    'message'   => 'User bukan dokter'
                ], 403);
            }

            $kdDokter   = session('simrs_nik');
            $search = trim($request->get('search', ''));
            $tanggal = $request->get('tanggal');
            $query      = DB::table('konsultasi_perawat as kp')
                ->join(
                    'reg_periksa as rp',
                    'kp.no_rawat',
                    '=',
                    'rp.no_rawat'
                )
                ->join(
                    'pasien as p',
                    'rp.no_rkm_medis',
                    '=',
                    'p.no_rkm_medis'
                )
                ->join(
                    'petugas as pg',
                    'kp.nip',
                    '=',
                    'pg.nip'
                )
                ->join(
                    'dokter as dk',
                    'kp.kd_dokter_dikonsuli',
                    '=',
                    'dk.kd_dokter'
                )
                ->select(
                    DB::raw("
                                    DATE_FORMAT(
                                        kp.tanggal,
                                        '%d/%m/%Y %H:%i:%s'
                                    ) as tanggalperiksa
                                "),
                    'kp.no_permintaan',
                    'rp.no_rkm_medis',
                    'p.nm_pasien',
                    'pg.nip',
                    'kp.kd_dokter_dikonsuli',
                    'dk.nm_dokter as dokterkonsul'
                )
                ->where('kp.kd_dokter_dikonsuli', $kdDokter)
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_perawat as jkp')
                        ->whereColumn(
                            'jkp.no_permintaan',
                            'kp.no_permintaan'
                        );
                });

            if ($search !== '') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'p.nm_pasien',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'rp.no_rkm_medis',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'kp.no_permintaan',
                            'like',
                            "%{$search}%"
                        );
                });
            }

            if (!empty($tanggal)) {

                $query->whereDate(
                    'kp.tanggal',
                    $tanggal
                );
            }

            $perPage    = $request->get('per_page', 10);

            $data   = $query
                ->orderBy('kp.tanggal', 'desc')
                ->paginate($perPage);

            return response()->json([
                'error' =>  false,
                'data'  =>  $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' =>  true,
                'msg'   =>  $e->getMessage()
            ], 500);
        }
    }

    public function DetilKonsultasiPerawat($nopermintaan)
    {
        $data = DB::table('konsultasi_perawat as kp')

            ->join(
                'reg_periksa as rp',
                'kp.no_rawat',
                '=',
                'rp.no_rawat'
            )

            ->join(
                'pasien as p',
                'rp.no_rkm_medis',
                '=',
                'p.no_rkm_medis'
            )

            // perawat pengirim konsultasi
            ->join(
                'petugas as p_pengirim',
                'kp.nip',
                '=',
                'p_pengirim.nip'
            )

            // dokter tujuan konsultasi
            ->join(
                'dokter as dk_tujuan',
                'kp.kd_dokter_dikonsuli',
                '=',
                'dk_tujuan.kd_dokter'
            )


            ->select(
                DB::raw("
                    DATE_FORMAT(
                        kp.tanggal,
                        '%Y-%m-%d %H:%i:%s'
                    ) as tanggalkonsultasi
                "),
                'kp.no_permintaan',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'kp.situation',
                'kp.background',
                'kp.assessment',
                'kp.recomendation',


                'kp.nip',
                'kp.kd_dokter_dikonsuli',

                'p_pengirim.nama as nm_perawat_pengirim',
                'dk_tujuan.nm_dokter as nm_dokter_tujuan'
            )

            ->where(
                'kp.no_permintaan',
                $nopermintaan
            )

            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data konsultasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function KonsultasiPerawatSelesai(Request $request)
    {
        try {

            if (session('simrs_tipe') !== 'dokter') {
                return response()->json([
                    'error'   => true,
                    'message' => 'User bukan dokter'
                ], 403);
            }

            $kdDokter = session('simrs_nik');
            $search = trim($request->get('search', ''));
            $tanggal = $request->get('tanggal');
            $query = DB::table('konsultasi_perawat as kp')
                ->join(
                    'reg_periksa as rp',
                    'kp.no_rawat',
                    '=',
                    'rp.no_rawat'
                )
                ->join(
                    'pasien as p',
                    'rp.no_rkm_medis',
                    '=',
                    'p.no_rkm_medis'
                )
                // perawat pengirim konsultasi
                ->join(
                    'petugas as pg',
                    'kp.nip',
                    '=',
                    'pg.nip'
                )

                // dokter tujuan konsultasi
                ->join(
                    'dokter as dk',
                    'kp.kd_dokter_dikonsuli',
                    '=',
                    'dk.kd_dokter'
                )

                ->where('kp.kd_dokter_dikonsuli', $kdDokter)

                ->select(
                    DB::raw("
                                    DATE_FORMAT(
                                        kp.tanggal,
                                        '%d/%m/%Y %H:%i:%s'
                                    ) as tanggalperiksa
                                "),
                    'kp.no_permintaan',
                    'rp.no_rkm_medis',
                    'p.nm_pasien',
                    'pg.nip',
                    'kp.kd_dokter_dikonsuli',
                    'dk.nm_dokter as dokterkonsul'
                )

                ->whereExists(function ($q) {

                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_perawat as jkp')
                        ->whereColumn(
                            'jkp.no_permintaan',
                            'kp.no_permintaan'
                        );
                });

            if ($search !== '') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'p.nm_pasien',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'rp.no_rkm_medis',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'kp.no_permintaan',
                            'like',
                            "%{$search}%"
                        );
                });
            }

            if (!empty($tanggal)) {

                $query->whereDate(
                    'kp.tanggal',
                    $tanggal
                );
            }

            $perPage = $request->get('per_page', 10);

            $data = $query
                ->orderBy('kp.tanggal', 'desc')
                ->paginate($perPage);

            return response()->json([
                'error' => false,
                'data'  => $data
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'msg'   => $e->getMessage()
            ], 500);
        }
    }

    public function DetilHistoryKonsultasiPerawat($nopermintaan)
    {
        $data = DB::table('konsultasi_perawat as kp')

            ->join(
                'reg_periksa as rp',
                'kp.no_rawat',
                '=',
                'rp.no_rawat'
            )

            ->join(
                'pasien as p',
                'rp.no_rkm_medis',
                '=',
                'p.no_rkm_medis'
            )

            // perawat pengirim konsultasi
            ->join(
                'petugas as p_pengirim',
                'kp.nip',
                '=',
                'p_pengirim.nip'
            )

            // dokter tujuan konsultasi
            ->join(
                'dokter as dk_tujuan',
                'kp.kd_dokter_dikonsuli',
                '=',
                'dk_tujuan.kd_dokter'
            )


            ->select(
                DB::raw("
                    DATE_FORMAT(
                        kp.tanggal,
                        '%Y-%m-%d %H:%i:%s'
                    ) as tanggalkonsultasi
                "),
                'kp.no_permintaan',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'kp.situation',
                'kp.background',
                'kp.assessment',
                'kp.recomendation',


                'kp.nip',
                'kp.kd_dokter_dikonsuli',

                'p_pengirim.nama as nm_perawat_pengirim',
                'dk_tujuan.nm_dokter as nm_dokter_tujuan'
            )

            ->where(
                'kp.no_permintaan',
                $nopermintaan
            )

            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data konsultasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function DetilHistoryJawabanKonsultasiPerawat($nopermintaan)
    {
        try {

            $data = DB::table('konsultasi_perawat as kp')

                // WAJIB: hanya yang punya jawaban
                ->join('jawaban_konsultasi_perawat as jkp', function ($join) {
                    $join->on('kp.no_permintaan', '=', 'jkp.no_permintaan');
                })

                ->join('reg_periksa as rp', 'kp.no_rawat', '=', 'rp.no_rawat')

                ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')

                ->join(
                    'petugas as p_pengirim',
                    'kp.nip',
                    '=',
                    'p_pengirim.nip'
                )

                ->join('dokter as dk_tujuan', 'kp.kd_dokter_dikonsuli', '=', 'dk_tujuan.kd_dokter')

                ->select(

                    // ========================
                    // IDENTITAS KONSULTASI
                    // ========================
                    'kp.no_permintaan',
                    'kp.no_rawat',

                    DB::raw("DATE_FORMAT(kp.tanggal,'%Y-%m-%d %H:%i:%s') as tanggal_konsultasi"),

                    // 'kp.diagnosa_kerja as diagnosa_konsultasi',
                    // 'km.uraian_konsultasi',

                    // ========================
                    // PASIEN
                    // ========================
                    'rp.no_rkm_medis',
                    'p.nm_pasien',

                    // ========================
                    // Kronologi
                    // ========================
                    'kp.situation',
                    'kp.background',
                    'kp.assessment',
                    'kp.recomendation',

                    // ========================
                    // perawat
                    // ========================
                    'kp.nip',
                    'p_pengirim.nama as nm_perawat_penerima',

                    // ========================
                    // DOKTER
                    // ========================
                    'kp.kd_dokter_dikonsuli',
                    'dk_tujuan.nm_dokter as dokter_penjawab',

                    // ========================
                    // JAWABAN KONSULTASI
                    // ========================
                    DB::raw("DATE_FORMAT(jkp.tanggal,'%Y-%m-%d %H:%i:%s') as tanggal_jawaban"),

                    'jkp.respon',
                    'jkp.instruksi',
                    'jkp.rencana',
                )

                ->where('kp.no_permintaan', $nopermintaan)

                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data konsultasi tidak ditemukan'
                ], 404);
            }


            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function NotifKonsultasiBelumDijawab()
    {
        try {

            if (session('simrs_tipe') !== 'dokter') {
                return response()->json([
                    'error' => true,
                    'message' => 'User bukan dokter'
                ], 403);
            }

            $kdDokter = session('simrs_nik');

            $masuk = DB::table('konsultasi_medik as km')

                ->where('km.kd_dokter_dikonsuli', $kdDokter)

                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_medik as jkm')
                        ->whereColumn(
                            'jkm.no_permintaan',
                            'km.no_permintaan'
                        );
                })

                ->count();


            $keluar = DB::table('konsultasi_medik as km')

                ->where('km.kd_dokter', $kdDokter)

                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_medik as jkm')
                        ->whereColumn(
                            'jkm.no_permintaan',
                            'km.no_permintaan'
                        );
                })

                ->count();

            return response()->json([
                'error' => false,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $masuk + $keluar
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
