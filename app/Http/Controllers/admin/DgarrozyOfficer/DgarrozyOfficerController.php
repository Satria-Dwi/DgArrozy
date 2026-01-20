<?php

namespace App\Http\Controllers\Admin\DgarrozyOfficer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DgarrozyOfficerController extends Controller
{
    /**
     * Tampilkan data pegawai (READ ONLY)
     */
    public function index(Request $request)
    {
        $pegawai = DB::table('pegawai as p')
            ->leftJoin('jnj_jabatan as j', 'j.kode', '=', 'p.jnj_jabatan')
            ->leftJoin('departemen as d', 'd.dep_id', '=', 'p.departemen')
            ->select(
                'p.id',
                'p.nama',
                'p.jk',
                'p.jbtn',
                'j.nama as jenjang_jabatan',
                'd.nama as nama_departemen',
                'p.stts_aktif'
            );

        if ($request->filled('nama')) {
            $pegawai->where('p.nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('jabatan')) {
            $pegawai->where('p.jbtn', 'like', '%' . $request->jabatan . '%');
        }

        if ($request->filled('departemen')) {
            $pegawai->where('d.nama', 'like', '%' . $request->departemen . '%');
        }

        $pegawai = $pegawai
            ->orderBy('p.nama', 'asc')
            ->paginate(20); // ⬅️ PENTING
        
        $paginationView = view('admin.officer.partials.pagination', ['pegawai' => $pegawai])->render();

        // ⬇️ JIKA AJAX
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.officer.partials.table', compact('pegawai'))->render(),
                'total' => $pegawai->total(),
                'pagination' => $paginationView, // ✅ FIX UTAMA
            ]);
        }

        return view('admin.officer.index', compact('pegawai'), [
            'title'  => 'MArRozzy | Officer',
            'active' => 'mainadmin'
        ]);
    }
}
