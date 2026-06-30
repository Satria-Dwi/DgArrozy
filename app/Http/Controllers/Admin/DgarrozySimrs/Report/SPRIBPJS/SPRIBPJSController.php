<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\SPRIBPJS;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SPRIBPJSController extends Controller
{
    public function getSPRIBPJS($no_rawat)
    {
        $data = DB::table('reg_periksa as rp')
            ->join('bridging_surat_pri_bpjs as bspb', 'rp.no_rawat', '=', 'bspb.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->where('bspb.no_rawat', $no_rawat)
            ->select(
                'bspb.kd_dokter_bpjs',
                'bspb.nm_dokter_bpjs',
                'bspb.nm_poli_bpjs',
                'bspb.no_kartu',
                DB::raw("
                    CONCAT(
                        p.nm_pasien,
                        ' ( ',
                        CASE
                            WHEN p.jk = 'L' THEN 'LAKI-LAKI'
                            WHEN p.jk = 'P' THEN 'PEREMPUAN'
                            ELSE '-'
                        END,
                        ' )'
                    ) AS nm_pasien
                "),
                'p.tgl_lahir',
                'bspb.diagnosa',
                'bspb.tgl_surat',
                'bspb.no_surat',
                'bspb.tgl_rencana',
            )->first();

        $barcode = 'https://barcode.tec-it.com/barcode.ashx'
            . '?data=' . urlencode($data->no_surat)
            . '&code=Code128'
            . '&dpi=96'
            . '&hidehrt=yes';

        $setting = DB::table('setting')->first();

        $qr_text = "Dikeluarkan di {$setting->nama_instansi}, Kabupaten/Kota {$setting->kabupaten}\n"
            . "Ditandatangani secara elektronik oleh\n"
            . "{$data->nm_dokter_bpjs}\n"
            . "ID {$data->kd_dokter_bpjs}\n"
            . "{$data->tgl_rencana}";
        $qr_api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data='
            . urlencode($qr_text);

        $logo = public_path('img/bpjs/logo-bpjs.png');
        $pdf = Pdf::loadView(
            'simrs.report.spribpjs.index',
            compact('data', 'logo', 'barcode', 'qr_api')
        )->setPaper('A5', 'landscape')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream("SPRI_{$no_rawat}.pdf");
    }
}
