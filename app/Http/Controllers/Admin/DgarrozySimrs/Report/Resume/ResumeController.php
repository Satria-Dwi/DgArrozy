<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\Resume;

use Barryvdh\DomPDF\Facade\pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    public function resumeMedis($norawat)
    {
        $data = DB::table('resume_pasien as r')
            ->join('reg_periksa as rp', 'r.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'r.kd_dokter', '=', 'd.kd_dokter')

            // CEK RAWAT INAP
            ->leftJoin('kamar_inap as ki', 'r.no_rawat', '=', 'ki.no_rawat')

            // POLI UNTUK RALAN
            ->leftJoin('poliklinik as pl', 'rp.kd_poli', '=', 'pl.kd_poli')

            ->where('r.no_rawat', $norawat)

            ->select(
                'rp.no_rawat',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.jk',
                'p.tgl_lahir',
                'rp.umurdaftar',
                'rp.sttsumur',

                DB::raw("
                        CONCAT(
                            COALESCE(p.alamat, ''),
                            ', Kel. ', COALESCE(p.kelurahanpj, ''),
                            ', Kec. ', COALESCE(p.kecamatanpj, ''),
                            ', ', COALESCE(p.kabupatenpj, '')
                        ) as alamat_lengkap
                    "),


                'p.pekerjaan',

                // penanda jenis rawat
                DB::raw("CASE 
                        WHEN ki.no_rawat IS NULL THEN 'RALAN'
                        ELSE 'RANAP'
                     END as jenis_rawat"),

                // ambil poli jika ralan
                'rp.kd_poli',
                'pl.nm_poli',

                // tanggal masuk & keluar
                DB::raw("CASE 
                        WHEN ki.no_rawat IS NULL THEN rp.tgl_registrasi
                        ELSE ki.tgl_masuk
                     END as tgl_masuk"),

                DB::raw("CASE 
                        WHEN ki.no_rawat IS NULL THEN rp.tgl_registrasi
                        ELSE ki.tgl_keluar
                     END as tgl_keluar"),

                'r.keluhan_utama',
                'r.jalannya_penyakit',
                'r.pemeriksaan_penunjang',
                'r.hasil_laborat',

                'r.kd_diagnosa_utama',
                'r.diagnosa_utama',

                'r.diagnosa_sekunder',
                'r.kd_diagnosa_sekunder',
                'r.diagnosa_sekunder2',
                'r.kd_diagnosa_sekunder2',
                'r.diagnosa_sekunder3',
                'r.kd_diagnosa_sekunder3',
                'r.diagnosa_sekunder4',
                'r.kd_diagnosa_sekunder4',

                'r.prosedur_utama',
                'r.kd_prosedur_utama',
                'r.prosedur_sekunder',
                'r.kd_prosedur_sekunder',
                'r.prosedur_sekunder2',
                'r.kd_prosedur_sekunder2',
                'r.prosedur_sekunder3',
                'r.kd_prosedur_sekunder3',

                'r.kondisi_pulang',
                'd.nm_dokter'
            )
            ->first();

        $pdf = pdf::loadView('simrs.report.resume.index', compact('data'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream('resume-medis.pdf');
    }
}
