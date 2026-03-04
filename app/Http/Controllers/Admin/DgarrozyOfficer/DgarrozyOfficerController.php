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
        $query = DB::table('pegawai as p')
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

        // 🔹 FILTER
        if ($request->filled('nama')) {
            $query->where('p.nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('jabatan')) {
            $query->where('p.jbtn', 'like', '%' . $request->jabatan . '%');
        }

        if ($request->filled('departemen')) {
            $query->where('d.nama', 'like', '%' . $request->departemen . '%');
        }

        // 🔹 PAGINATION (FIX LARAVEL 8)
        $pegawai = $query
            ->orderBy('p.nama', 'asc')
            ->paginate(20)
            ->appends($request->query()); // ✅ INI KUNCINYA

        // 🔹 AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'table'      => view('admin.officer.partials.table', compact('pegawai'))->render(),
                'pagination' => view('admin.officer.partials.pagination', compact('pegawai'))->render(),
                'total'      => $pegawai->total(),
            ]);
        }

        // 🔹 NORMAL VIEW
        return view('admin.officer.index', compact('pegawai'), [
            'title'  => 'MArRozzy | Officer',
            'active' => 'mainadmin'
        ]);
    }
}
