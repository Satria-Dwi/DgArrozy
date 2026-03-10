<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Manajemen\DetailTindakan;

use App\Exports\DetailTindakanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DetailTindakanController extends Controller
{
    // View Blade
    public function index()
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.manajemen.detailtindakan.index', [
            'title' => 'Manajemen',
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

    // Filter data via AJAX
    public function detailtindakan(Request $request, $jenis)
    {
        $start = $request->start;
        $end   = $request->end;

        if ($jenis === 'ranap') {

            $query = DB::table('rawat_inap_dr')
                ->join('reg_periksa', 'rawat_inap_dr.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_inap', 'rawat_inap_dr.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
                ->join('dokter', 'rawat_inap_dr.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->join('kamar_inap', 'rawat_inap_dr.no_rawat', '=', 'kamar_inap.no_rawat')
                ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'rawat_inap_dr.kd_jenis_prw',
                    'jns_perawatan_inap.nm_perawatan',
                    'rawat_inap_dr.kd_dokter',
                    'dokter.nm_dokter',
                    'rawat_inap_dr.tgl_perawatan',
                    'rawat_inap_dr.jam_rawat',
                    'penjab.png_jawab',
                    'bangsal.nm_bangsal'
                );

            if ($start && $end) {
                $query->whereBetween('rawat_inap_dr.tgl_perawatan', [$start, $end]);
            }
        } elseif ($jenis === 'ralan') {

            $query = DB::table('rawat_jl_dr')
                ->join('reg_periksa', 'rawat_jl_dr.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan', 'rawat_jl_dr.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
                ->join('dokter', 'rawat_jl_dr.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'rawat_jl_dr.kd_jenis_prw',
                    'jns_perawatan.nm_perawatan',
                    'rawat_jl_dr.kd_dokter',
                    'dokter.nm_dokter',
                    'rawat_jl_dr.tgl_perawatan',
                    'rawat_jl_dr.jam_rawat',
                    'penjab.png_jawab',
                    'poliklinik.nm_poli'
                );

            if ($start && $end) {
                $query->whereBetween('rawat_jl_dr.tgl_perawatan', [$start, $end]);
            }
        } elseif ($jenis === 'operasi') {

            $query = DB::table('reg_periksa')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('laporan_operasi', 'reg_periksa.no_rawat', '=', 'laporan_operasi.no_rawat')
                ->leftJoin('booking_operasi', 'reg_periksa.no_rawat', '=', 'booking_operasi.no_rawat')
                ->leftJoin('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->leftJoin('operasi', 'reg_periksa.no_rawat', '=', 'operasi.no_rawat')
                ->leftJoin('paket_operasi', 'operasi.kode_paket', '=', 'paket_operasi.kode_paket')
                ->leftJoin('dokter as d1', 'operasi.operator1', '=', 'd1.kd_dokter')
                ->leftJoin('dokter as d2', 'operasi.dokter_anestesi', '=', 'd2.kd_dokter')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'operasi.kode_paket',
                    'paket_operasi.nm_perawatan',
                    DB::raw('DATE(laporan_operasi.tanggal) as tgl_operasi'),
                    DB::raw('TIME(laporan_operasi.tanggal) as jam_operasi'),
                    'penjab.png_jawab',
                    'operasi.operator1 as kd_operator1',
                    'd1.nm_dokter as operator1',
                    'd2.nm_dokter as dokter_anestesi'
                );

            if ($start && $end) {
                $query->whereBetween(DB::raw('DATE(laporan_operasi.tanggal)'), [$start, $end]);
            }
        } elseif ($jenis === 'radiologi') {
            $query = DB::table('periksa_radiologi')
                ->join('reg_periksa', 'periksa_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
                ->join('dokter', 'periksa_radiologi.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')

                ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')

                ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
                ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')

                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'periksa_radiologi.kd_jenis_prw',
                    'jns_perawatan_radiologi.nm_perawatan',
                    'dokter.kd_dokter',
                    'dokter.nm_dokter',
                    'periksa_radiologi.tgl_periksa',
                    'periksa_radiologi.jam',
                    'penjab.png_jawab',
                    'reg_periksa.status_lanjut',

                    DB::raw("
                            CASE
                                WHEN reg_periksa.status_lanjut = 'Ralan' THEN poliklinik.nm_poli
                                WHEN reg_periksa.status_lanjut = 'Ranap' THEN bangsal.nm_bangsal
                            END AS ruangan
                        ")
                );

            if ($start && $end) {
                $query->whereBetween('periksa_radiologi.tgl_periksa', [$start, $end]);
            }
        } elseif ($jenis === 'laboratorium') {
            $query = DB::table('periksa_lab')
                ->join('reg_periksa', 'periksa_lab.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
                ->join('dokter', 'periksa_lab.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')

                ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')

                ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
                ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')

                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'periksa_lab.kd_jenis_prw',
                    'jns_perawatan_lab.nm_perawatan',
                    'dokter.kd_dokter',
                    'dokter.nm_dokter',
                    'periksa_lab.tgl_periksa',
                    'periksa_lab.jam',
                    'penjab.png_jawab',
                    'reg_periksa.status_lanjut',

                    DB::raw("
                                CASE
                                    WHEN reg_periksa.status_lanjut = 'Ralan' THEN poliklinik.nm_poli
                                    WHEN reg_periksa.status_lanjut = 'Ranap' THEN bangsal.nm_bangsal
                                END AS ruangan
                            ")
                );

            if ($start && $end) {
                $query->whereBetween('periksa_lab.tgl_periksa', [$start, $end]);
            }
        }

        return response()->json(
            $query->orderBy('reg_periksa.no_rawat', 'desc')->get()
        );
    }

    public function exportDetailTindakan(Request $request, $jenis)
    {
        $start = $request->start;
        $end   = $request->end;

        return Excel::download(
            new DetailTindakanExport($start, $end, $jenis),
            'detail_tindakan_' . $jenis . '.xlsx'
        );
    }

    // public function operasi(Request $request)
    // {
    //     $data = DB::table('reg_periksa as rp')
    //         ->join('pasien as ps', 'rp.no_rkm_medis', '=', 'ps.no_rkm_medis')
    //         ->join('laporan_operasi as lo', 'rp.no_rawat', '=', 'lo.no_rawat') // WAJIB ADA
    //         ->leftjoin('booking_operasi as bo', 'rp.no_rawat', '=', 'bo.no_rawat')
    //         ->leftjoin('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')

    //         ->leftjoin('operasi as opr',  'rp.no_rawat', '=', 'opr.no_rawat')
    //         ->leftjoin('paket_operasi as po', 'opr.kode_paket', '=', 'po.kode_paket')

    //         ->leftJoin('dokter as d1', 'opr.operator1', '=', 'd1.kd_dokter')
    //         ->leftJoin('dokter as d2', 'opr.dokter_anestesi', '=', 'd2.kd_dokter')

    //         ->select(
    //             'rp.no_rawat',
    //             'ps.no_rkm_medis',
    //             'ps.nm_pasien',
    //             'po.nm_perawatan',
    //             'lo.tanggal as tanggal_operasi',
    //             'pj.png_jawab',
    //             'd1.nm_dokter as operator1',
    //             'd2.nm_dokter as dokter_anestesi'
    //         )
    //         ->orderBy('lo.tanggal', 'desc')
    //         ->limit(50)
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'total' => $data->count(),
    //         'data' => $data
    //     ]);
    // }

    // public function radiologi()
    // {
    //     $data = DB::table('periksa_radiologi')
    //         ->join('reg_periksa', 'periksa_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
    //         ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
    //         ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
    //         ->join('dokter', 'periksa_radiologi.kd_dokter', '=', 'dokter.kd_dokter')
    //         ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')

    //         ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')

    //         ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
    //         ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
    //         ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')

    //         ->select(
    //             'reg_periksa.no_rawat',
    //             'pasien.no_rkm_medis',
    //             'pasien.nm_pasien',
    //             'periksa_radiologi.kd_jenis_prw',
    //             'jns_perawatan_radiologi.nm_perawatan',
    //             'dokter.kd_dokter',
    //             'dokter.nm_dokter',
    //             'periksa_radiologi.tgl_periksa',
    //             'periksa_radiologi.jam',
    //             'penjab.png_jawab',
    //             'reg_periksa.status_lanjut',

    //             DB::raw("
    //         CASE
    //             WHEN reg_periksa.status_lanjut = 'Ralan' THEN poliklinik.nm_poli
    //             WHEN reg_periksa.status_lanjut = 'Ranap' THEN bangsal.nm_bangsal
    //         END AS unit
    //     ")
    //         )
    //         ->orderBy('periksa_radiologi.tgl_periksa', 'desc')
    //         ->orderBy('reg_periksa.no_rawat', 'ASC')
    //         ->get();

    //     return response()->json($data);
    // }

    // public function lab()
    // {
    //     $data = DB::table('periksa_lab')
    //         ->join('reg_periksa', 'periksa_lab.no_rawat', '=', 'reg_periksa.no_rawat')
    //         ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
    //         ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
    //         ->join('dokter', 'periksa_lab.kd_dokter', '=', 'dokter.kd_dokter')
    //         ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')

    //         ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')

    //         ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
    //         ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
    //         ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')

    //         ->select(
    //             'reg_periksa.no_rawat',
    //             'pasien.no_rkm_medis',
    //             'pasien.nm_pasien',
    //             'periksa_lab.kd_jenis_prw',
    //             'jns_perawatan_lab.nm_perawatan',
    //             'dokter.kd_dokter',
    //             'dokter.nm_dokter',
    //             'periksa_lab.tgl_periksa',
    //             'periksa_lab.jam',
    //             'penjab.png_jawab',
    //             'reg_periksa.status_lanjut',

    //             DB::raw("
    //         CASE
    //             WHEN reg_periksa.status_lanjut = 'Ralan' THEN poliklinik.nm_poli
    //             WHEN reg_periksa.status_lanjut = 'Ranap' THEN bangsal.nm_bangsal
    //         END AS unit
    //     ")
    //         )
    //         ->orderBy('periksa_lab.tgl_periksa', 'desc')
    //         ->orderBy('reg_periksa.no_rawat', 'ASC')
    //         ->get();

    //     return response()->json($data);
    // }
}
