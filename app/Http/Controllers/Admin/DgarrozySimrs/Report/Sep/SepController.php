<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\Sep;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SepController extends Controller
{
    public function getSep($no_rawat)
    {
        $data = DB::table('reg_periksa as rp')
            ->join('bridging_sep as bs', 'rp.no_rawat', '=', 'bs.no_rawat')
            ->where('bs.no_rawat', $no_rawat)
            ->select(
                'rp.no_rawat',
                'rp.no_reg',
                'bs.nama_pasien',
                'bs.peserta',
                DB::raw("
                    CASE
                        WHEN bs.jnspelayanan = '1' THEN 'Rawat Inap'
                        ELSE 'Rawat Jalan'
                    END AS pelayanan
                "),
                DB::raw("
                    CASE
                        WHEN bs.tujuankunjungan = '0' THEN '- Konsultasi dokter (pertama)'
                        ELSE '- Kunjungan Kontrol (ulangan)'
                    END AS tujuan_kunjungan
                "),
                DB::raw("
                    CASE
                        WHEN bs.flagprosedur = '0' THEN '- Prosedur Tidak Berkelanjutan'
                        WHEN bs.flagprosedur = '1' THEN '- Prosedur dan Terapi Berkelanjutan'
                        ELSE '-'
                    END AS flag_prosedur
                "),
                DB::raw("
                    CASE
                        WHEN bs.klsrawat = '1' THEN 'Kelas 1'
                        WHEN bs.klsrawat = '2' THEN 'Kelas 2'
                        ELSE 'Kelas 3'
                    END AS kelas_rawat
                "),
                DB::raw("
                    CASE
                        WHEN bs.klsnaik = '1' THEN 'VVIP'
                        WHEN bs.klsnaik = '2' THEN 'VIP'
                        WHEN bs.klsnaik = '3' THEN 'Kelas I'
                        WHEN bs.klsnaik = '4' THEN 'Kelas II'
                        WHEN bs.klsnaik = '5' THEN 'Kelas III'
                        WHEN bs.klsnaik = '6' THEN 'ICCU'
                        WHEN bs.klsnaik = '7' THEN 'ICU'
                        WHEN bs.klsnaik = '8' THEN 'Diatas Kelas 1'
                        ELSE '-'
                    END AS kelas_naik
                "),
                DB::raw("
                    CASE
                        WHEN bs.lakalantas = '0' THEN 'BPJS Kesehatan'
                        WHEN bs.lakalantas = '1' THEN 'Jasa Raharja'
                        WHEN bs.lakalantas = '2' THEN 'Jasa Raharja & BPJS Ketenagakerjaan/Taspen'
                        WHEN bs.lakalantas = '3' THEN 'BPJS Ketenagakerjaan, Taspen, dll'
                        ELSE '-'
                    END AS penjamin_lakalantas
                "),
                'bs.no_sep',
                'bs.tglsep',
                DB::raw("
                    CONCAT(bs.no_kartu, ' ( MR : ', bs.nomr, ' )') AS kartu_mr
                "),
                'bs.no_kartu',
                'bs.tanggal_lahir',
                'bs.notelep',
                'bs.nmpolitujuan',
                'bs.nmdpdjp',
                'bs.nmppkrujukan',
                'bs.nmdiagnosaawal',
                'bs.catatan'
            )
            ->first();
        $barcode = 'https://barcode.tec-it.com/barcode.ashx'
            . '?data=' . urlencode($data->no_sep)
            . '&code=Code128'
            . '&dpi=96'
            . '&hidehrt=yes';


        $setting = DB::table('setting')->first();

        $qr_text = "Dikeluarkan di {$setting->nama_instansi}, Kabupaten/Kota {$setting->kabupaten}\n"
            . "Ditandatangani secara elektronik oleh\n"
            . "{$data->nama_pasien}\n"
            . "ID {$data->no_kartu}\n"
            . "{$data->tglsep}";
        $qr_api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data='
            . urlencode($qr_text);
            
        $logo = public_path('img/bpjs/logo-bpjs.png');
        $pdf = Pdf::loadView(
            'simrs.report.sep.index',
            compact('data', 'logo', 'barcode', 'qr_api')
        )->setPaper('A5', 'landscape')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream("SEP_{$no_rawat}.pdf");
    }
}
