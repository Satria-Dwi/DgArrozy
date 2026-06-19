<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\RekamMedis;

use App\Exports\RujukanKeluarExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RujukanKeluarController extends Controller
{
    public function index()
    {
        if (!session('simrs_login')) {
            return redirect('/login')->with('error', 'Silahkan login dulu');
        }

        return view('simrs.rekam_medis.rujukan_keluar.index', [
            'title' =>  'Rujukan Keluar',
            'user'  =>  [
                'nik'           =>  session('simrs_nik'),
                'nama'          =>  session('simrs_nama'),
                'departemen'    =>  session('simrs_dept'),
                'jabatan'       =>  session('simrs_jbtn'),
                'tipe'          =>  session('simrs_tipe'),
                'spesialis'     =>  session('simrs_sps'),
            ],
        ]);
    }

    public function getDataRujukanKeluar(Request $request)
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
                'rk.kd_dokter',
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
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('rk.tgl_rujuk', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('rk.tgl_rujuk', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('p.alamat', 'like', "%{$search}%")
                    ->orWhere('p.kelurahanpj', 'like', "%{$search}%")
                    ->orWhere('p.kecamatanpj', 'like', "%{$search}%")
                    ->orWhere('p.kabupatenpj', 'like', "%{$search}%");
            });
        }
        
        $query->orderby('rk.tgl_rujuk', 'desc');
        return response()->json(
            $query->paginate($request->get('per_page', 20))
        );
    }

    public function exportRujukanKeluar(Request $request)
    {
        $tanggal = now()->format('Ymd_His');

        return Excel::download(
            new RujukanKeluarExport($request),
            "rujukan_keluar_{$tanggal}.xlsx"
        );
    }
}
