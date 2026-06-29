<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\AsesmenIGD;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AsesmenIgdController extends Controller
{
    public function getAsesmenIgd($no_rawat)
    {
        // =========================
        // 1. DATA UTAMA (FULL MATCH PHP ASLI)
        // =========================
        $data = DB::table('penilaian_medis_igd as pg')
            ->join('reg_periksa as rp', 'pg.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'pg.kd_dokter', '=', 'd.kd_dokter')
            ->select([

                // identitas
                'rp.no_rawat',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'p.tgl_lahir',
                'p.jk',

                // dokter & waktu
                'pg.tanggal',
                'pg.kd_dokter',
                'd.nm_dokter',
                'pg.anamnesis',
                'pg.hubungan',

                // anamnesis
                'pg.keluhan_utama',
                'pg.rps',
                'pg.rpd',
                'pg.rpk',
                'pg.rpo',
                'pg.alergi',

                // vital
                'pg.keadaan',
                'pg.gcs',
                'pg.kesadaran',
                'pg.td',
                'pg.nadi',
                'pg.rr',
                'pg.suhu',
                'pg.spo',
                'pg.bb',
                'pg.tb',

                // fisik
                'pg.kepala',
                'pg.mata',
                'pg.gigi',
                'pg.leher',
                'pg.thoraks',
                'pg.abdomen',
                'pg.genital',
                'pg.ekstremitas',

                'pg.ket_fisik',
                'pg.ket_lokalis',

                // penunjang
                'pg.ekg',
                'pg.rad',
                'pg.lab',

                // diagnosis & tindakan (WAJIB FULL PHP ASLI)
                'pg.diagnosis',
                'pg.tata',

                // =========================
                // TAMBAHAN UNTUK VIEW BARU BIAR TIDAK ERROR
                // =========================
                DB::raw("'' as status_observasi"),
                DB::raw("'' as lama_observasi"),
                DB::raw("'' as rencana_lanjutan"),
                DB::raw("'' as kondisi_pulang"),
                DB::raw("'' as triage"),
                DB::raw("'' as ket_triage"),
                DB::raw("'' as evaluasi"),
            ])
            ->where('pg.no_rawat', $no_rawat)
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data Asesmen IGD tidak ditemukan'
            ], 404);
        }

        // =========================
        // 2. SETTING
        // =========================
        $setting = DB::table('setting')->first();

        // =========================
        // 3. FINGERPRINT (SAMA PHP ASLI)
        // =========================
        $finger = DB::table('sidikjari')
            ->join('pegawai', 'pegawai.id', '=', 'sidikjari.id')
            ->where('pegawai.nik', $data->kd_dokter)
            ->select(DB::raw('SHA1(sidikjari.sidikjari) as finger'))
            ->first();

        $fingerCode = $finger->finger ?? $data->kd_dokter;

        // =========================
        // 4. QR (SAMA PHP ASLI)
        // =========================
        $qrContent =
            "Dikeluarkan di {$setting->nama_instansi}, Kabupaten/Kota {$setting->kabupaten}\n" .
            "Ditandatangani secara elektronik oleh {$data->nm_dokter}\n" .
            "ID {$fingerCode}\n" .
            $data->tanggal;

        $qr = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data="
            . urlencode($qrContent);

        $gambar_lokalis = public_path('img/anatomi/semua.png');

        if (file_exists($gambar_lokalis)) {
            $type = pathinfo($gambar_lokalis, PATHINFO_EXTENSION);
            $imgData = file_get_contents($gambar_lokalis);
            $gambar_lokalis = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
        }

        // =========================
        // 5. RENDER PDF
        // =========================
        return Pdf::loadView('simrs.report.asesmen.index', [
            'data'    => $data,
            'qr'      => $qr,
            // 'setting' => $setting,
            'gambar_lokalis' => $gambar_lokalis
        ])->setPaper([0, 0, 595.28, 935.43], 'portrait')
            ->stream("Asesmen_IGD_{$no_rawat}.pdf");
    }
}
