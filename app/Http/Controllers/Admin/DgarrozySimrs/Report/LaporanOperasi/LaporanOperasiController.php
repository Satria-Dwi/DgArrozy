<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\LaporanOperasi;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class LaporanOperasiController extends Controller
{
    public function getLaporanOperasi(Request $request, $no_rawat)
    {
        $data = DB::table('operasi')
            ->join('reg_periksa', 'operasi.no_rawat', '=', 'reg_periksa.no_rawat')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('laporan_operasi', function ($join) {
                $join->on('operasi.no_rawat', '=', 'laporan_operasi.no_rawat')
                    ->on('operasi.tgl_operasi', '=', 'laporan_operasi.tanggal');
            })
            ->select([
                'operasi.no_rawat',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'pasien.jk',
                'pasien.tgl_lahir',
                'reg_periksa.umurdaftar',
                'reg_periksa.sttsumur',
                'operasi.tgl_operasi',
                'operasi.jenis_anasthesi',
                'operasi.kategori',

                'laporan_operasi.diagnosa_preop',
                'laporan_operasi.diagnosa_postop',
                'laporan_operasi.jaringan_dieksekusi',
                'laporan_operasi.selesaioperasi',
                'laporan_operasi.permintaan_pa',
                'laporan_operasi.laporan_operasi',

                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.operator1 LIMIT 1) as operator1"),
                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.operator2 LIMIT 1) as operator2"),
                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.operator3 LIMIT 1) as operator3"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.asisten_operator1 LIMIT 1) as asistenoperator1"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.asisten_operator2 LIMIT 1) as asistenoperator2"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.asisten_operator3 LIMIT 1) as asistenoperator3"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.instrumen LIMIT 1) as instrumen"),

                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.dokter_anak LIMIT 1) as dokteranak"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.perawaat_resusitas LIMIT 1) as perawatresusitas"),
                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.dokter_anestesi LIMIT 1) as anastesi"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.asisten_anestesi LIMIT 1) as asistenanastesi"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.asisten_anestesi2 LIMIT 1) as asistenanastesi2"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.bidan LIMIT 1) as bidan1"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.bidan2 LIMIT 1) as bidan2"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.bidan3 LIMIT 1) as bidan3"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.perawat_luar LIMIT 1) as perawatluar"),

                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.omloop LIMIT 1) as omloop"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.omloop2 LIMIT 1) as omloop2"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.omloop3 LIMIT 1) as omloop3"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.omloop4 LIMIT 1) as omloop4"),
                DB::raw("(SELECT nama FROM petugas WHERE nip=operasi.omloop5 LIMIT 1) as omloop5"),

                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.dokter_pjanak LIMIT 1) as pjanak"),
                DB::raw("(SELECT nm_dokter FROM dokter WHERE kd_dokter=operasi.dokter_umum LIMIT 1) as dokumum"),
            ])
            ->where('operasi.no_rawat', $no_rawat)
            ->orderByDesc('operasi.tgl_operasi')
            ->first();

        abort_if(!$data, 404, 'Data operasi tidak ditemukan');

        $status = DB::table('reg_periksa')
            ->where('no_rawat', $no_rawat)
            ->value('status_lanjut');

        $waktuOperasi = $data->tgl_operasi;

        if ($status == 'Ralan') {

            $asesmen = DB::table('pemeriksaan_ralan')
                ->where('no_rawat', $no_rawat)
                ->whereRaw("CONCAT(tgl_perawatan,' ',jam_rawat) <= ?", [$waktuOperasi])
                ->orderByDesc('tgl_perawatan')
                ->orderByDesc('jam_rawat')
                ->first();
        } else {

            $asesmen = DB::table('pemeriksaan_ranap')
                ->where('no_rawat', $no_rawat)
                ->whereRaw("CONCAT(tgl_perawatan,' ',jam_rawat) <= ?", [$waktuOperasi])
                ->orderByDesc('tgl_perawatan')
                ->orderByDesc('jam_rawat')
                ->first();
        }

        if ($status == 'Ralan') {

            $ruang = DB::table('reg_periksa')
                ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
                ->where('reg_periksa.no_rawat', $no_rawat)
                ->value('poliklinik.nm_poli');
        } else {

            $ruang = DB::table('kamar_inap')
                ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
                ->where('kamar_inap.no_rawat', $no_rawat)
                ->orderByDesc('kamar_inap.tgl_masuk')
                ->value('bangsal.nm_bangsal');
        }

        $pemeriksaanText = trim($asesmen->pemeriksaan);

        $pemeriksaanText .= ", Kes {$asesmen->kesadaran}";
        $pemeriksaanText .= ", GCS {$asesmen->gcs}";
        // $pemeriksaanText .= ", skala nyeri {$asesmen->informasi}";
        $pemeriksaanText .= ", {$asesmen->keluhan}";

        $finger = "Ditandatangani secara elektronik\n";
        $finger .= "dr. {$data->operator1}\n";
        $finger .= "Tanggal : " . date('d-m-Y H:i:s', strtotime($data->tgl_operasi));

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($finger)
            ->size(120)
            ->build();

        $qrcode = base64_encode($result->getString());

        $pdf = Pdf::loadView(
            'simrs.report.laporanoperasi.index',
            compact(
                'data',
                'ruang',
                'asesmen',
                'pemeriksaanText',
                'qrcode'
            )
        )->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("laporan-operasi_{$no_rawat}.pdf");
    }
}
