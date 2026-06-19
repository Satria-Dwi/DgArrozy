<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\MenuManajemenDokter\Konsultasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarKonsultasiDokterController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.MenuManajemenDokter.konsultasi.konsultasi_dokter.index', [
            'title' => 'Konsultasi Dokter',
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

    public function KonsultasiDokter(Request $request)
    {
        try {

            $search = trim($request->get('search', ''));
            $tanggal = $request->get('tanggal');
            $query = DB::table('konsultasi_medik as km')
                ->join(
                    'reg_periksa as rp',
                    'km.no_rawat',
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
                    'dokter as dk',
                    'km.kd_dokter',
                    '=',
                    'dk.kd_dokter'
                )

                ->select(
                    DB::raw("
                    DATE_FORMAT(
                        km.tanggal,
                        '%d/%m/%Y %H:%i:%s'
                    ) as tanggalperiksa
                "),
                    'km.no_permintaan',
                    'rp.no_rkm_medis',
                    'p.nm_pasien',

                    'km.kd_dokter',
                    'km.kd_dokter_dikonsuli',

                    'dk.nm_dokter as dokterkonsul'
                )

                ->whereNotExists(function ($q) {

                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_medik as jkm')
                        ->whereColumn(
                            'jkm.no_permintaan',
                            'km.no_permintaan'
                        );
                });

            if ($search !== '') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                            'dk.nm_dokter',
                            'like',
                            "%{$search}%"
                        );
                });
            }

            if (!empty($tanggal)) {

                $query->whereDate(
                    'km.tanggal',
                    $tanggal
                );
            }

            $perPage = $request->get('per_page', 10);

            $data = $query
                ->orderBy('km.tanggal', 'desc')
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

    public function DetilKonsultasiDokter($nopermintaan)
    {
        $data = DB::table('konsultasi_medik as km')

            ->join(
                'reg_periksa as rp',
                'km.no_rawat',
                '=',
                'rp.no_rawat'
            )

            ->join(
                'pasien as p',
                'rp.no_rkm_medis',
                '=',
                'p.no_rkm_medis'
            )

            // dokter pengirim konsultasi
            ->join(
                'dokter as dk_pengirim',
                'km.kd_dokter',
                '=',
                'dk_pengirim.kd_dokter'
            )

            // dokter tujuan konsultasi
            ->join(
                'dokter as dk_tujuan',
                'km.kd_dokter_dikonsuli',
                '=',
                'dk_tujuan.kd_dokter'
            )


            ->select(
                DB::raw("
                    DATE_FORMAT(
                        km.tanggal,
                        '%Y-%m-%d %H:%i:%s'
                    ) as tanggalkonsultasi
                "),
                'km.no_permintaan',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'km.jenis_permintaan',
                'km.diagnosa_kerja',
                'km.uraian_konsultasi',

                'km.kd_dokter',
                'km.kd_dokter_dikonsuli',

                'dk_pengirim.nm_dokter as nm_dokter_pengirim',
                'dk_tujuan.nm_dokter as nm_dokter_tujuan'
            )

            ->where(
                'km.no_permintaan',
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

    public function KonsultasiDokterSelesai(Request $request)
    {
        try {

            $search = trim($request->get('search', ''));
            $tanggal = $request->get('tanggal');
            $query = DB::table('konsultasi_medik as km')
                ->join(
                    'reg_periksa as rp',
                    'km.no_rawat',
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
                    'dokter as dk',
                    'km.kd_dokter',
                    '=',
                    'dk.kd_dokter'
                )
                ->select(
                    DB::raw("
                    DATE_FORMAT(
                        km.tanggal,
                        '%d/%m/%Y %H:%i:%s'
                    ) as tanggalperiksa
                "),
                    'km.no_permintaan',
                    'rp.no_rkm_medis',
                    'p.nm_pasien',
                    'km.kd_dokter',
                    'km.kd_dokter_dikonsuli',
                    'dk.nm_dokter as dokterkonsul'
                )

                ->whereExists(function ($q) {

                    $q->select(DB::raw(1))
                        ->from('jawaban_konsultasi_medik as jkm')
                        ->whereColumn(
                            'jkm.no_permintaan',
                            'km.no_permintaan'
                        );
                });

            if ($search !== '') {

                $query->where(function ($q) use ($search) {

                    $q->where(
                            'dk.nm_dokter',
                            'like',
                            "%{$search}%"
                        );
                });
            }

            if (!empty($tanggal)) {

                $query->whereDate(
                    'km.tanggal',
                    $tanggal
                );
            }

            $perPage = $request->get('per_page', 10);

            $data = $query
                ->orderBy('km.tanggal', 'desc')
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

    public function DetilHistoryKonsultasiDokter($nopermintaan)
    {
        $data = DB::table('konsultasi_medik as km')

            ->join(
                'reg_periksa as rp',
                'km.no_rawat',
                '=',
                'rp.no_rawat'
            )

            ->join(
                'pasien as p',
                'rp.no_rkm_medis',
                '=',
                'p.no_rkm_medis'
            )

            // dokter pengirim konsultasi
            ->join(
                'dokter as dk_pengirim',
                'km.kd_dokter',
                '=',
                'dk_pengirim.kd_dokter'
            )

            // dokter tujuan konsultasi
            ->join(
                'dokter as dk_tujuan',
                'km.kd_dokter_dikonsuli',
                '=',
                'dk_tujuan.kd_dokter'
            )


            ->select(
                DB::raw("
                    DATE_FORMAT(
                        km.tanggal,
                        '%Y-%m-%d %H:%i:%s'
                    ) as tanggalkonsultasi
                "),
                'km.no_permintaan',
                'rp.no_rkm_medis',
                'p.nm_pasien',
                'km.jenis_permintaan',
                'km.diagnosa_kerja',
                'km.uraian_konsultasi',

                'km.kd_dokter',
                'km.kd_dokter_dikonsuli',

                'dk_pengirim.nm_dokter as nm_dokter_pengirim',
                'dk_tujuan.nm_dokter as nm_dokter_tujuan'
            )

            ->where(
                'km.no_permintaan',
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

    public function DetilHistoryJawabanKonsultasiDokter($nopermintaan)
    {
        try {

            $data = DB::table('konsultasi_medik as km')

                // WAJIB: hanya yang punya jawaban
                ->join('jawaban_konsultasi_medik as jkm', function ($join) {
                    $join->on('km.no_permintaan', '=', 'jkm.no_permintaan');
                })

                ->join('reg_periksa as rp', 'km.no_rawat', '=', 'rp.no_rawat')

                ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')

                ->join('dokter as dk_pengirim', 'km.kd_dokter', '=', 'dk_pengirim.kd_dokter')

                ->join('dokter as dk_tujuan', 'km.kd_dokter_dikonsuli', '=', 'dk_tujuan.kd_dokter')

                ->select(

                    // ========================
                    // IDENTITAS KONSULTASI
                    // ========================
                    'km.no_permintaan',
                    'km.no_rawat',
                    'km.jenis_permintaan',

                    DB::raw("DATE_FORMAT(km.tanggal,'%Y-%m-%d %H:%i:%s') as tanggal_konsultasi"),

                    'km.diagnosa_kerja as diagnosa_konsultasi',
                    'km.uraian_konsultasi',

                    // ========================
                    // PASIEN
                    // ========================
                    'rp.no_rkm_medis',
                    'p.nm_pasien',

                    // ========================
                    // DOKTER
                    // ========================
                    'km.kd_dokter',
                    'dk_pengirim.nm_dokter as dokter_pengirim',

                    'km.kd_dokter_dikonsuli',
                    'dk_tujuan.nm_dokter as dokter_tujuan',

                    // ========================
                    // JAWABAN KONSULTASI
                    // ========================
                    DB::raw("DATE_FORMAT(jkm.tanggal,'%Y-%m-%d %H:%i:%s') as tanggal_jawaban"),

                    'jkm.diagnosa_kerja as diagnosa_jawaban',
                    'jkm.uraian_jawaban'

                )

                ->where('km.no_permintaan', $nopermintaan)

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
}
