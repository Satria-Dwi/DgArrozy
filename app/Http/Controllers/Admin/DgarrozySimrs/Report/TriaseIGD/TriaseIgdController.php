<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\TriaseIGD;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TriaseIgdController extends Controller
{

    public function getTriaseIGD($no_rawat)
    {

        // ==========================
        // 1. DETEKSI SKALA
        // ==========================
        $skala = 0;
        $tabelDetail = '';
        $tipeTriase = '';

        if (DB::table('data_triase_igddetail_skala1')->where('no_rawat', $no_rawat)->exists()) {
            $skala = 1;
            $tabelDetail = 'data_triase_igddetail_skala1';
            $tipeTriase = 'PRIMER';
        } elseif (DB::table('data_triase_igddetail_skala2')->where('no_rawat', $no_rawat)->exists()) {
            $skala = 2;
            $tabelDetail = 'data_triase_igddetail_skala2';
            $tipeTriase = 'PRIMER';
        } elseif (DB::table('data_triase_igddetail_skala3')->where('no_rawat', $no_rawat)->exists()) {
            $skala = 3;
            $tabelDetail = 'data_triase_igddetail_skala3';
            $tipeTriase = 'SEKUNDER';
        } elseif (DB::table('data_triase_igddetail_skala4')->where('no_rawat', $no_rawat)->exists()) {
            $skala = 4;
            $tabelDetail = 'data_triase_igddetail_skala4';
            $tipeTriase = 'SEKUNDER';
        } elseif (DB::table('data_triase_igddetail_skala5')->where('no_rawat', $no_rawat)->exists()) {
            $skala = 5;
            $tabelDetail = 'data_triase_igddetail_skala5';
            $tipeTriase = 'SEKUNDER';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data triase belum diinput'
            ], 404);
        }

        // ==========================
        // 2. CONFIG
        // ==========================
        $config = [
            'sub_judul' => '',
            'kode_berkas' => '001',
            'warna_bg' => '#FFFFFF',
            'warna_txt' => '#000000'
        ];

        switch ($skala) {
            case 1:
                $config['sub_judul'] = 'TRIASE PRIMER Skala 1 (Resusitasi)';
                $config['warna_bg'] = '#FF0000';
                $config['warna_txt'] = '#FFFFFF';
                break;

            case 2:
                $config['sub_judul'] = 'TRIASE PRIMER Skala 2 (Emergency)';
                $config['warna_bg'] = '#FF0000';
                $config['warna_txt'] = '#FFFFFF';
                break;

            case 3:
                $config['sub_judul'] = 'TRIASE SEKUNDER Skala 3 (Urgent)';
                $config['warna_bg'] = '#FFFF00';
                break;

            case 4:
                $config['sub_judul'] = 'TRIASE SEKUNDER Skala 4 (Semi Urgent)';
                $config['warna_bg'] = '#00FF00';
                break;

            case 5:
                $config['sub_judul'] = 'TRIASE SEKUNDER Skala 5 (Non Urgent)';
                break;
        }

        // ==========================
        // 3. DATA UMUM
        // ==========================
        $umum = (array) DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->leftJoin('data_triase_igd as tri', 'rp.no_rawat', '=', 'tri.no_rawat')
            ->leftJoin('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('master_triase_macam_kasus as mk', 'tri.kode_kasus', '=', 'mk.kode_kasus')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                'p.tgl_lahir',
                'p.jk',
                'p.alamat',

                'rp.tgl_registrasi',
                'rp.jam_reg',

                'd.nm_dokter',

                'mk.macam_kasus',

                'tri.no_rawat as tri_no_rawat',
                'tri.tgl_kunjungan',
                'tri.cara_masuk',
                'tri.alat_transportasi',
                'tri.alasan_kedatangan',
                'tri.keterangan_kedatangan',
                'tri.kode_kasus',
                'tri.tekanan_darah',
                'tri.nadi',
                'tri.pernapasan',
                'tri.suhu',
                'tri.saturasi_o2',
                'tri.nyeri'
            )
            ->where('rp.no_rawat', $no_rawat)
            ->first();

        if (!$umum) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan.'
            ], 404);
        }

        // ==========================
        // 4. FALLBACK TTV
        // ==========================
        if (
            empty($umum['suhu']) ||
            empty($umum['tensi'])
        ) {

            $ttv = DB::table('pemeriksaan_ralan')
                ->where('no_rawat', $no_rawat)
                ->orderBy('tgl_perawatan')
                ->orderBy('jam_rawat')
                ->first();

            if ($ttv) {

                if (empty($umum['tekanan_darah'])) {
                    $umum['tekanan_darah'] = $ttv->tensi;
                }

                if (empty($umum['suhu'])) {
                    $umum['suhu'] = $ttv->suhu_tubuh;
                }

                if (empty($umum['nadi'])) {
                    $umum['nadi'] = $ttv->nadi;
                }

                if (empty($umum['pernapasan'])) {
                    $umum['pernapasan'] = $ttv->respirasi;
                }

                // keluhan hanya kalau memang nanti dipakai
                $umum['keluhan'] = $ttv->keluhan;
            }
        }

        // ==========================
        // 5. TANGGAL TRIASE
        // ==========================
        $tglTriase = $umum['tgl_kunjungan'] ?? null;

        if (empty($tglTriase) || $tglTriase == '0000-00-00 00:00:00') {
            $tglTriase = $umum['tgl_registrasi'] . ' ' . $umum['jam_reg'];
        }

        // ==========================
        // 6. DATA KHUSUS
        // ==========================
        if ($tipeTriase == 'PRIMER') {
            $khusus = DB::table('data_triase_igdprimer')
                ->where('no_rawat', $no_rawat)
                ->first();
        } else {
            $khusus = DB::table('data_triase_igdsekunder')
                ->where('no_rawat', $no_rawat)
                ->first();
        }

        $nik = $khusus->nik ?? null;

        // ==========================
        // 7. PERAWAT
        // ==========================
        $namaPerawat = '-';

        if ($nik) {
            $pegawai = DB::table('pegawai')
                ->where('nik', $nik)
                ->first();

            if ($pegawai) {
                $namaPerawat = $pegawai->nama;
            }
        }

        // ==========================
        // 8. CHECKLIST
        // ==========================
        $master = "master_triase_skala{$skala}";
        $kode = "kode_skala{$skala}";
        $pengkajian = "pengkajian_skala{$skala}";
        $setting = DB::table('setting')->first();

        $checklist = DB::table("$tabelDetail as d")
            ->join("$master as m", "d.$kode", '=', "m.$kode")
            ->join('master_triase_pemeriksaan as p', 'm.kode_pemeriksaan', '=', 'p.kode_pemeriksaan')
            ->select(
                'p.nama_pemeriksaan',
                DB::raw("m.$pengkajian as hasil")
            )
            ->where('d.no_rawat', $no_rawat)
            ->orderBy('p.kode_pemeriksaan')
            ->get();

        // ==========================
        // SIDIK JARI
        // ==========================
        $fingerCode = $nik;

        if ($nik) {

            $pegawai = DB::table('pegawai')
                ->where('nik', $nik)
                ->first();

            if ($pegawai) {

                $sidikJari = DB::table('sidikjari')
                    ->where('id', $pegawai->id)
                    ->value('sidikjari');

                if (!empty($sidikJari)) {
                    $fingerCode = sha1($sidikJari);
                }
            }
        }

        $tglTte = $khusus->tanggaltriase ?? now()->format('Y-m-d H:i:s');

        $qrContent =
            "Dikeluarkan di {$setting->nama_instansi}, Kabupaten/Kota {$setting->kabupaten}\n" .
            "Ditandatangani secara elektronik oleh {$namaPerawat}\n" .
            "ID {$fingerCode}\n" .
            $tglTte;

        $qrCode = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' .
            urlencode($qrContent);

        // ==========================
        // RETURN JSON
        // ==========================
        $pdf = Pdf::loadView('simrs.report.triaseigd.index', [
            'success' => true,
            'setting' => $setting,
            'skala' => $skala,
            'tipe_triase' => $tipeTriase,
            'config' => $config,
            'tanggal_triase' => $tglTriase,
            'pasien' => $umum,
            'triase' => $khusus,
            'perawat' => [
                'nik' => $nik,
                'nama' => $namaPerawat
            ],
            'checklist' => $checklist,
            'qrCode' => $qrCode,
            'fingerCode' => $fingerCode,
        ])->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("Triase_IGD_{$no_rawat}.pdf");
    }
}
