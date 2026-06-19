<?php
namespace App\Http\Controllers\Admin\DgarrozySimrs\MenuManajemenDokter\Konsultasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarKonsultasiPerawatController extends Controller
{
    public function index()
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.MenuManajemenDokter.konsultasi.konsultasi_perawat.index', [
            'title' => 'Konsultasi Perawat',
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

    public function KonsultasiPerawat(Request $request)
    {
        try {
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
                );

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
                            'dk.nm_dokter',
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
}