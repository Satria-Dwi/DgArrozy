<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\Resume;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    public function resumeMedisRalan($norawat)
    {
        $data = DB::table('resume_pasien as r')
            ->join('reg_periksa as rp', 'r.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'r.kd_dokter', '=', 'd.kd_dokter')

            // POLI UNTUK RALAN
            ->leftJoin('poliklinik as pl', 'rp.kd_poli', '=', 'pl.kd_poli')

            ->where('r.no_rawat', $norawat)

            ->select(
                'rp.no_rawat',
                'rp.tgl_registrasi',
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

                // ambil poli jika ralan
                'rp.kd_poli',
                'pl.nm_poli',

                // tanggal masuk & keluar
                DB::raw("
                        CASE
                            WHEN r.no_rawat IS NULL THEN rp.tgl_registrasi
                            ELSE rp.tgl_registrasi
                        END as tgl_masuk
                    "),

                DB::raw("
                        CASE
                            WHEN r.no_rawat IS NULL THEN rp.tgl_registrasi
                            ELSE rp.tgl_registrasi
                        END as tgl_keluar
                    "),

                DB::raw("
                            DATE_FORMAT(rp.tgl_registrasi, '%d %M %Y') as tgl_masuk
                        "),

                DB::raw("
                            DATE_FORMAT(rp.tgl_registrasi, '%d %M %Y') as tgl_keluar
                        "),

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
                'r.obat_pulang',
                'd.nm_dokter'
            )
            ->first();

        // $fields = [
        //     'pemeriksaan_penunjang' => 'penunjang',
        //     'hasil_laborat' => 'laborat',
        //     'tindakan_dan_operasi' => 'tindakan',
        //     'obat_di_rs' => 'obat',
        // ];

        // $batas = 10000;

        // foreach ($fields as $field => $prefix) {

        //     $text = trim($data->$field ?? '');

        //     if (mb_strlen($text) > $batas) {

        //         $potong = mb_strrpos(
        //             mb_substr($text, 0, $batas),
        //             ' '
        //         );

        //         // Jika tidak ada spasi sebelum batas
        //         if ($potong === false) {
        //             $potong = $batas;
        //         }

        //         $data->{$prefix . '1'} = mb_substr($text, 0, $potong);
        //         $data->{$prefix . '2'} = mb_substr($text, $potong + 1);
        //     } else {

        //         $data->{$prefix . '1'} = $text;
        //         $data->{$prefix . '2'} = null;
        //     }
        // }

        $pdf = Pdf::loadView('simrs.report.resume.ralan.index', compact('data'))
            ->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("Resume-medis_{$norawat}.pdf");
    }

    public function resumeMedisRanap($norawat)
    {
        $data = DB::table('resume_pasien_ranap as r')
            ->join('reg_periksa as rp', 'r.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'r.kd_dokter', '=', 'd.kd_dokter')

            ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')

            ->leftJoin('kamar_inap as ki', 'r.no_rawat', '=', 'ki.no_rawat')
            ->leftJoin('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->leftJoin('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')

            ->where('r.no_rawat', $norawat)

            ->orderByDesc('ki.tgl_keluar')

            ->select(
                'rp.no_rawat',
                'rp.tgl_registrasi',
                'rp.no_rkm_medis',

                'p.nm_pasien',
                'p.jk',
                'p.tgl_lahir',
                DB::raw("
                        CONCAT(
                            COALESCE(p.alamat, ''),
                            ', Kel. ', COALESCE(p.kelurahanpj, ''),
                            ', Kec. ', COALESCE(p.kecamatanpj, ''),
                            ', ', COALESCE(p.kabupatenpj, '')
                        ) as alamat_lengkap
                    "),
                'p.pekerjaan',

                'rp.umurdaftar',
                'rp.sttsumur',

                'pj.png_jawab',

                'ki.tgl_masuk',
                'ki.tgl_keluar',

                'k.kd_kamar',
                'b.nm_bangsal',

                'd.kd_dokter',
                'd.nm_dokter',

                // seluruh field resume
                'r.diagnosa_awal',
                'r.alasan',
                'r.keluhan_utama',
                'r.pemeriksaan_fisik',
                'r.jalannya_penyakit',
                'r.pemeriksaan_penunjang',
                'r.hasil_laborat',
                'r.tindakan_dan_operasi',
                'r.obat_di_rs',

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

                'r.alergi',
                'r.diet',
                'r.lab_belum',
                'r.edukasi',
                'r.cara_keluar',
                'r.keadaan',
                'r.dilanjutkan',
                'r.kontrol',
                'r.obat_pulang',
            )
            ->first();

        $fields = [
            'pemeriksaan_penunjang' => 'penunjang',
            'hasil_laborat' => 'laborat',
            'tindakan_dan_operasi' => 'tindakan',
            'obat_di_rs' => 'obat',
        ];

        $batas = 10000;

        foreach ($fields as $field => $prefix) {

            $text = trim($data->$field ?? '');

            if (mb_strlen($text) > $batas) {

                $potong = mb_strrpos(
                    mb_substr($text, 0, $batas),
                    ' '
                );

                // Jika tidak ada spasi sebelum batas
                if ($potong === false) {
                    $potong = $batas;
                }

                $data->{$prefix . '1'} = mb_substr($text, 0, $potong);
                $data->{$prefix . '2'} = mb_substr($text, $potong + 1);
            } else {

                $data->{$prefix . '1'} = $text;
                $data->{$prefix . '2'} = null;
            }
        }

        $dokterPendamping = DB::table('dpjp_ranap as dr')
            ->join('dokter as d', 'd.kd_dokter', '=', 'dr.kd_dokter')
            ->where('dr.no_rawat', $norawat)
            ->where('dr.kd_dokter', '<>', $data->kd_dokter)
            ->select(
                'd.kd_dokter',
                'd.nm_dokter'
            )
            ->distinct()
            ->orderBy('d.nm_dokter')
            ->get()
            ->values();

        $data->dokterPendamping = $dokterPendamping;

        $data->pj2 = $dokterPendamping[0] ?? null;
        $data->pj3 = $dokterPendamping[1] ?? null;
        $data->pj4 = $dokterPendamping[2] ?? null;
        $pdf = Pdf::loadView(
            'simrs.report.resume.ranap.index',
            compact('data')
        )->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("resume-medis_{$norawat}.pdf");
    }
}
