<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\ResepObat;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepObatController extends Controller
{
    // public function resepPasien(Request $request, $no_rawat)
    // {
    //     $query = DB::table('resep_obat as ro')
    //         ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
    //         ->join('pasien as ps', 'ps.no_rkm_medis', '=', 'rp.no_rkm_medis')
    //         ->join('dokter as dk', 'dk.kd_dokter', '=', 'ro.kd_dokter')
    //         ->where('ro.no_rawat', $no_rawat);

    //     $kamarInap = DB::table('kamar_inap')
    //         ->where('no_rawat', $no_rawat)
    //         ->orderBy('tgl_masuk')
    //         ->first();

    //     if ($request->filled('tanggal')) {

    //         if ($kamarInap) {

    //             // Pasien Rawat Inap
    //             $tglKeluar = $kamarInap->tgl_keluar ?: now()->toDateString();

    //             $query->whereBetween('ro.tgl_perawatan', [
    //                 $kamarInap->tgl_masuk,
    //                 $tglKeluar
    //             ]);
    //         } else {

    //             // Pasien Rawat Jalan
    //             $query->whereDate('ro.tgl_perawatan', $request->tanggal);

    //             // atau jika ingin memastikan sama dengan tanggal registrasi:
    //             $query->whereDate('ro.tgl_perawatan', 'rp.tgl_registrasi');
    //         }
    //     }

    //     $resep = $query
    //         ->orderBy('ro.tgl_perawatan')
    //         ->orderBy('ro.jam')
    //         ->select(
    //             'ro.no_resep',
    //             'ro.no_rawat',
    //             'ro.tgl_perawatan',
    //             'ro.jam',
    //             'ps.no_rkm_medis',
    //             'ps.nm_pasien',
    //             'dk.nm_dokter'
    //         )
    //         ->get();

    //     $rawat = [
    //         'jenis' => $kamarInap ? 'Ranap' : 'Ralan',
    //         'tgl_masuk' => $kamarInap->tgl_masuk ?? null,
    //         'tgl_keluar' => $kamarInap->tgl_keluar ?? null,
    //     ];

    //     $data = [];

    //     foreach ($resep as $item) {

    //         //=========================
    //         // Obat Non Racikan
    //         //=========================
    //         $obat = DB::table('resep_dokter as rd')
    //             ->join('databarang as db', 'db.kode_brng', '=', 'rd.kode_brng')
    //             ->where('rd.no_resep', $item->no_resep)
    //             ->select(
    //                 DB::raw("'non_racikan' as jenis"),
    //                 'db.nama_brng',
    //                 'rd.jml',
    //                 'rd.aturan_pakai'
    //             )
    //             ->get();

    //         //=========================
    //         // Obat Racikan
    //         //=========================
    //         $racikan = DB::table('resep_dokter_racikan as rr')
    //             ->where('rr.no_resep', $item->no_resep)
    //             ->get();

    //         $listRacikan = [];

    //         foreach ($racikan as $racik) {

    //             $detail = DB::table('resep_dokter_racikan_detail as d')
    //                 ->join('databarang as b', 'b.kode_brng', '=', 'd.kode_brng')
    //                 ->where('d.no_resep', $item->no_resep)
    //                 ->where('d.no_racik', $racik->no_racik)
    //                 ->select(
    //                     'b.nama_brng',
    //                     'd.jml',
    //                     'd.kandungan'
    //                 )
    //                 ->get();

    //             $listRacikan[] = [
    //                 'jenis'         => 'racikan',
    //                 'no_racik'      => $racik->no_racik,
    //                 'nama_racik'    => $racik->nama_racik,
    //                 'jumlah'        => $racik->jml_dr,
    //                 'aturan_pakai'  => $racik->aturan_pakai,
    //                 'keterangan'    => $racik->keterangan,
    //                 'detail'        => $detail
    //             ];
    //         }

    //         $data[] = [
    //             'no_resep' => $item->no_resep,
    //             'tanggal' => $item->tgl_perawatan,
    //             'jam' => $item->jam,
    //             'pasien' => [
    //                 'no_rawat' => $item->no_rawat,
    //                 'no_rm' => $item->no_rkm_medis,
    //                 'nama' => $item->nm_pasien,
    //             ],
    //             'rawat' => $rawat,
    //             'dokter' => $item->nm_dokter,
    //             'obat' => $obat,
    //             'racikan' => $listRacikan
    //         ];
    //     }

    //     $pdf = Pdf::loadView('simrs.report.resepobat.index', compact('data'))
    //         ->setPaper([0, 0, 595.28, 935.43], 'portrait');

    //     return $pdf->stream("resep-obat_{$no_rawat}.pdf");
    // }

    public function resepPasien(Request $request, $no_rawat)
    {
        $query = DB::table('resep_obat as ro')
            ->join('reg_periksa as rp', 'rp.no_rawat', '=', 'ro.no_rawat')
            ->join('pasien as ps', 'ps.no_rkm_medis', '=', 'rp.no_rkm_medis')
            ->join('dokter as dk', 'dk.kd_dokter', '=', 'ro.kd_dokter')
            ->where('ro.no_rawat', $no_rawat);

        $kamarInap = DB::table('kamar_inap')
            ->where('no_rawat', $no_rawat)
            ->orderBy('tgl_masuk')
            ->first();

        if ($request->filled('tanggal')) {

            if ($kamarInap) {

                $tglKeluar = $kamarInap->tgl_keluar ?: now()->toDateString();

                $query->whereBetween('ro.tgl_perawatan', [
                    $kamarInap->tgl_masuk,
                    $tglKeluar
                ]);
            } else {

                // Sama seperti Java:
                // hanya filter tanggal resep
                $query->whereDate('ro.tgl_perawatan', $request->tanggal);
            }
        }

        $resep = $query
            ->select(
                'ro.no_resep',
                'ro.no_rawat',
                'ro.tgl_perawatan',
                'ro.jam',
                'ro.kd_dokter',
                'ps.no_rkm_medis',
                'ps.nm_pasien',
                'dk.nm_dokter'
            )
            ->orderBy('ro.tgl_perawatan')
            ->orderBy('ro.jam')
            ->get();

        $rawat = [
            'jenis'      => $kamarInap ? 'Ranap' : 'Ralan',
            'tgl_masuk'  => $kamarInap->tgl_masuk ?? null,
            'tgl_keluar' => $kamarInap->tgl_keluar ?? null,
        ];

        $data = [];
        $grandTotal = 0;

        foreach ($resep as $item) {

            $totalResep = 0;

            // =========================
            // NON RACIKAN
            // =========================
            $obat = DB::table('detail_pemberian_obat as dpo')
                ->join('databarang as db', 'db.kode_brng', '=', 'dpo.kode_brng')
                ->where('dpo.tgl_perawatan', $item->tgl_perawatan)
                ->where('dpo.jam', $item->jam)
                ->where('dpo.no_rawat', $item->no_rawat)
                ->whereNotIn('dpo.kode_brng', function ($q) use ($item) {
                    $q->select('kode_brng')
                        ->from('detail_obat_racikan')
                        ->where('tgl_perawatan', $item->tgl_perawatan)
                        ->where('jam', $item->jam)
                        ->where('no_rawat', $item->no_rawat);
                })
                ->orderBy('db.kode_brng')
                ->select(
                    'dpo.kode_brng',
                    'db.nama_brng',
                    'dpo.jml',
                    'dpo.biaya_obat',
                    'dpo.embalase',
                    'dpo.tuslah',
                    'dpo.total'
                )
                ->get();

            $listObat = [];

            foreach ($obat as $o) {

                $aturan = DB::table('aturan_pakai')
                    ->where('tgl_perawatan', $item->tgl_perawatan)
                    ->where('jam', $item->jam)
                    ->where('no_rawat', $item->no_rawat)
                    ->where('kode_brng', $o->kode_brng)
                    ->value('aturan');

                $listObat[] = [
                    'jenis' => 'non_racikan',
                    'kode_brng' => $o->kode_brng,
                    'nama_brng' => $o->nama_brng,
                    'jumlah' => $o->jml,
                    'biaya_obat' => $o->biaya_obat,
                    'embalase' => $o->embalase,
                    'tuslah' => $o->tuslah,
                    'total' => $o->total,
                    'aturan_pakai' => $aturan
                ];

                $totalResep += $o->total;
                $grandTotal += $o->total;
            }

            // =========================
            // RACIKAN (HARUS DI DALAM LOOP)
            // =========================
            $racikan = DB::table('obat_racikan as obr')
                ->join('metode_racik as mr', 'mr.kd_racik', '=', 'obr.kd_racik')
                ->where('obr.tgl_perawatan', $item->tgl_perawatan)
                ->where('obr.jam', $item->jam)
                ->where('obr.no_rawat', $item->no_rawat)
                ->select(
                    'obr.no_racik',
                    'obr.nama_racik',
                    'obr.kd_racik',
                    'mr.nm_racik as metode',
                    'obr.jml_dr',
                    'obr.aturan_pakai',
                    'obr.keterangan'
                )
                ->get();

            $listRacikan = [];

            foreach ($racikan as $racik) {

                $detail = DB::table('detail_obat_racikan as dor')
                    ->join('detail_pemberian_obat as dpo', function ($join) {
                        $join->on('dpo.kode_brng', '=', 'dor.kode_brng')
                            ->on('dpo.tgl_perawatan', '=', 'dor.tgl_perawatan')
                            ->on('dpo.jam', '=', 'dor.jam')
                            ->on('dpo.no_rawat', '=', 'dor.no_rawat');
                    })
                    ->join('databarang as db', 'db.kode_brng', '=', 'dpo.kode_brng')
                    ->where('dor.tgl_perawatan', $item->tgl_perawatan)
                    ->where('dor.jam', $item->jam)
                    ->where('dor.no_rawat', $item->no_rawat)
                    ->where('dor.no_racik', $racik->no_racik)
                    ->select(
                        'db.kode_brng',
                        'db.nama_brng',
                        'dpo.jml',
                        'dpo.biaya_obat',
                        'dpo.embalase',
                        'dpo.tuslah',
                        'dpo.total'
                    )
                    ->get();

                $detailRacikan = [];

                foreach ($detail as $d) {

                    $detailRacikan[] = [
                        'kode_brng' => $d->kode_brng,
                        'nama_brng' => $d->nama_brng,
                        'jumlah' => $d->jml,
                        'biaya_obat' => $d->biaya_obat,
                        'embalase' => $d->embalase,
                        'tuslah' => $d->tuslah,
                        'total' => $d->total,
                    ];

                    $totalResep += $d->total;
                    $grandTotal += $d->total;
                }

                $listRacikan[] = [
                    'jenis' => 'racikan',
                    'no_racik' => $racik->no_racik,
                    'nama_racik' => $racik->nama_racik,
                    'metode' => $racik->metode,
                    'jumlah' => $racik->jml_dr,
                    'aturan_pakai' => $racik->aturan_pakai,
                    'keterangan' => $racik->keterangan,
                    'detail' => $detailRacikan
                ];
            }

            $data[] = [
                'no_resep' => $item->no_resep,
                'tanggal' => $item->tgl_perawatan,
                'jam' => $item->jam,
                'pasien' => [
                    'no_rawat' => $item->no_rawat,
                    'no_rm' => $item->no_rkm_medis,
                    'nama' => $item->nm_pasien,
                ],
                'rawat' => $rawat,
                'dokter' => [
                    'kode' => $item->kd_dokter,
                    'nama' => $item->nm_dokter,
                ],
                'obat' => $listObat,
                'racikan' => $listRacikan,
                'total_resep' => $totalResep,
            ];
        }

        $dataCetak = [
            'resep' => $data,
            'grand_total' => $grandTotal,
        ];
        
        $pdf = Pdf::loadView(
            'simrs.report.resepobat.index',
            [
                'data' => $dataCetak['resep'],
                'grand_total' => $dataCetak['grand_total']
            ]
        )->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("resep-obat_{$no_rawat}.pdf");
    }
}
