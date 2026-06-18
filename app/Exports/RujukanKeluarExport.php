<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RujukanKeluarExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('rujuk as rk')
            ->join('reg_periksa as rp', 'rk.no_rawat', '=', 'rp.no_rawat')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('dokter as d', 'rk.kd_dokter', '=', 'd.kd_dokter')
            ->select(
                'rk.no_rujuk',
                'rk.no_rawat',
                'p.no_rkm_medis',
                'p.nm_pasien',
                'rk.asal',
                'rk.rujuk_ke',
                'rk.tgl_rujuk',
                'rk.keterangan_diagnosa',
                'd.nm_dokter',
                'rk.kat_rujuk',
                'rk.ambulance',
                'rk.keterangan',
                'rk.jam',
                'p.alamat',
                'p.kelurahanpj',
                'p.kecamatanpj',
                'p.kabupatenpj',
            );

        // Contoh filter jika ada
        if ($this->request->filled('tanggal')) {
            $query->whereDate('rk.tgl_rujuk', $this->request->tanggal);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;

            $query->where(function ($q) use ($search) {
                $q->where('p.nm_pasien', 'like', "%{$search}%")
                    ->orWhere('p.no_rkm_medis', 'like', "%{$search}%")
                    ->orWhere('rk.no_rawat', 'like', "%{$search}%")
                    ->orWhere('rk.no_rujuk', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderBy('rk.tgl_rujuk', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No Rujuk',
            'No Rawat',
            'No RM',
            'Nama Pasien',
            'Asal',
            'Rujuk Ke',
            'Tanggal Rujuk',
            'Diagnosa',
            'Dokter',
            'Kategori Rujuk',
            'Ambulance',
            'Keterangan',
            'Jam',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Kabupaten/Kota'
        ];
    }
}