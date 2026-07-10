<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\ResepPulang;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepPulangController extends Controller
{
    public function resepPulang($no_rawat)
    {
        $obat = DB::table('resep_pulang')
            ->join('reg_periksa', 'reg_periksa.no_rawat', '=', 'resep_pulang.no_rawat')
            ->join('pasien', 'pasien.no_rkm_medis', '=', 'reg_periksa.no_rkm_medis')
            ->join('databarang', 'databarang.kode_brng', '=', 'resep_pulang.kode_brng')
            ->select(
                'resep_pulang.no_rawat',
                'resep_pulang.tanggal',
                'resep_pulang.jam',
                'reg_periksa.no_rkm_medis as no_rm',
                'pasien.nm_pasien as nama',
                DB::raw("CONCAT(resep_pulang.kode_brng,' ',databarang.nama_brng) as obat"),
                'resep_pulang.jml_barang',
                'resep_pulang.harga',
                'resep_pulang.total',
                'resep_pulang.dosis',
                'resep_pulang.no_batch',
                'resep_pulang.no_faktur',
                'resep_pulang.kd_bangsal'
            )
            ->where('resep_pulang.no_rawat', $no_rawat)
            ->orderBy('resep_pulang.tanggal')
            ->get();

        if ($obat->isEmpty()) {
            abort(404, 'Data resep pulang tidak ditemukan.');
        }

        $resep = [
            'pasien' => [
                'no_rawat' => $obat->first()->no_rawat,
                'no_rm'    => $obat->first()->no_rm,
                'nama'     => $obat->first()->nama,
            ],
            'obat' => $obat,
        ];

        $pdf = Pdf::loadView(
            'simrs.report.reseppulang.index',
            compact('resep')
        )->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("resep-pulang_{$no_rawat}.pdf");
    }
}
