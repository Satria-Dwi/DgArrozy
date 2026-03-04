<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs\ITMaster;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $admin = DB::table('admin')
            ->selectRaw("
            CAST(AES_DECRYPT(usere, 'nur') AS CHAR(50)) AS username,
            CAST(AES_DECRYPT(passworde, 'windi') AS CHAR(50)) AS password
        ")
            ->get();

        $user = DB::table('user as u')
            ->leftJoin('pegawai as p', DB::raw("
        CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
            "), '=', 'p.nik')
            ->leftJoin('departemen as d', 'p.departemen', '=', 'd.dep_id')
            ->selectRaw("
                CAST(AES_DECRYPT(u.id_user, 'nur') AS CHAR(20)) AS nik,
                CAST(AES_DECRYPT(u.password, 'windi') AS CHAR(50)) AS password,
                p.nama,
                p.jbtn,
                d.nama as nama_departemen,
                p.bidang,
                p.stts_aktif
            ")
            ->whereNotNull('d.nama')
            ->where('p.nama', '!=', '')
            ->where('p.nama', '!=', '-')

            ->orderBy('p.nama')
            ->paginate(20);

        $totalUser = DB::table('user')->count();

        $totalUserPegawai = DB::table('user as u')
            ->join('pegawai as p', DB::raw("
                CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
            "), '=', 'p.nik')
            ->join('departemen as d', 'p.departemen', '=', 'd.dep_id')

            ->whereNotNull('d.nama')

            ->count();

        return view('simrs.ITMaster.user.index', [
            'title'             => 'MArRozzy | Account SIMRS',
            'active'            => 'accountsimrs',
            'admin'             => $admin,
            'user'              => $user,
            'totalUser'         => $totalUser,
            'totalUserPegawai'  => $totalUserPegawai,
        ]);
    }

    public function table(Request $request)
    {
        $nama        = $request->nama;
        $jabatan     = $request->jabatan;
        $departemen  = $request->departemen; // 🔥 tambahan

        $query = DB::table('user as u')
            ->leftJoin('pegawai as p', DB::raw("
                CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
            "), '=', 'p.nik')

            ->leftJoin('departemen as d', 'p.departemen', '=', 'd.dep_id')

            ->selectRaw("
                CAST(AES_DECRYPT(u.id_user, 'nur') AS CHAR(20)) AS nik,
                CAST(AES_DECRYPT(u.password, 'windi') AS CHAR(50)) AS password,
                p.nama,
                p.jbtn,
                d.nama as nama_departemen,
                p.bidang,
                p.stts_aktif
            ")

            ->whereNotNull('d.nama')
            ->where('p.nama', '!=', '')
            ->where('p.nama', '!=', '-')

            ->when(
                $nama,
                fn($q) =>
                $q->where('p.nama', 'LIKE', "%{$nama}%")
            )

            ->when(
                $jabatan,
                fn($q) =>
                $q->where('p.jbtn', 'LIKE', "%{$jabatan}%")
            )

            // 🔥 FILTER DEPARTEMEN
            ->when(
                $departemen,
                fn($q) =>
                $q->where('d.nama', 'LIKE', "%{$departemen}%")
            );

        $user = $query
            ->orderBy('p.nama')
            ->paginate(20);

        return response()->json([
            'html' => view('simrs.ITMaster.user.table', compact('user'))->render(),
            'totalUser' => DB::table('user')->count(),
            'totalUserPegawai' => $user->total(),
        ]);
    }
}
