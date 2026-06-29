<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\Report\Billing;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{

    public function cetakbilling($no_rawat)
    {
        $pasien = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                DB::raw("
                        CONCAT(
                            COALESCE(p.alamat, ''),
                            ', Kel. ', COALESCE(p.kelurahanpj, ''),
                            ', Kec. ', COALESCE(p.kecamatanpj, ''),
                            ', ', COALESCE(p.kabupatenpj, '')
                        ) as alamat_lengkap
                    "),
                'rp.no_rawat',
                'rp.tgl_registrasi',
                'rp.jam_reg',
                'rp.status_lanjut',
                'd.nm_dokter',
                'pj.png_jawab'
            )
            ->where('rp.no_rawat', $no_rawat)
            ->first();

        $billing = DB::table('billing')
            ->where('no_rawat', $no_rawat)
            ->orderBy('noindex')
            ->get();

        $grandTotal = $billing
            ->filter(function ($item) {
                return !str_starts_with($item->status, 'Ttl') && $item->totalbiaya > 0;
            })
            ->sum('totalbiaya');

        // Setting Rumah Sakit
        $setting = DB::table('setting')->first();

        // Ambil tanggal pembayaran terakhir dari billing
        $tgl_bayar = $billing
            ->pluck('tgl_byr')
            ->filter(function ($tgl) {
                return !empty($tgl) && $tgl != '0000-00-00';
            })
            ->last();

        $tgl_bayar = $tgl_bayar ?? now()->toDateString();

        // Nama petugas
        $nama_petugas = session('simrs_nama');

        // Isi QR
        $qr_text = "Dikeluarkan oleh {$setting->nama_instansi} "
            . "pada tanggal {$tgl_bayar} "
            . "di {$setting->kabupaten} "
            . "oleh '{$nama_petugas}'";

        // Generate QR dari API
        $qr_api = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($qr_text);

        $pdf = Pdf::loadView(
            'simrs.report.billing.index',
            compact(
                'pasien',
                'billing',
                'grandTotal',
                'setting',
                'tgl_bayar',
                'nama_petugas',
                'qr_api'
            )
        )->setPaper([0, 0, 595.28, 935.43], 'portrait');

        return $pdf->stream("billing_{$no_rawat}.pdf");
    }
}
