<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Manajemen;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManajemenController extends Controller
{
    public function index() 
    {
        // Pastikan user sudah login (tambahan safety)
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silakan login dulu');
        }

        return view('simrs.manajemen.dokter.index', [
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
}
