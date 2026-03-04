<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        // ================= ADMIN =================
        $admin = DB::table('admin')
            ->selectRaw("
                CAST(AES_DECRYPT(usere, 'nur') AS CHAR(50)) AS username,
                CAST(AES_DECRYPT(passworde, 'windi') AS CHAR(50)) AS password
            ")
            ->get();

        // ================= USER + PEGAWAI =================
        $user = DB::table('user as u')
            ->leftJoin('pegawai as p', DB::raw("
                CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
            "), '=', 'p.nik')
            ->selectRaw("
                CAST(AES_DECRYPT(u.id_user, 'nur') AS CHAR(20)) AS nik,
                CAST(AES_DECRYPT(u.password, 'windi') AS CHAR(50)) AS password,
                p.nama,
                p.jk,
                p.jbtn,
                p.departemen,
                p.bidang,
                p.stts_aktif
            ")
            ->orderBy('p.nama')
            ->get();

        // ================= TOTAL =================
        $totalUser = DB::table('user')->count();

        $totalUserPegawai = DB::table('user as u')
            ->join('pegawai as p', DB::raw("
                CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
            "), '=', 'p.nik')
            ->count();

        // ================= VIEW =================
        return view('admin.simrs.user.user', [
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
        $nama    = $request->nama;
        $jabatan = $request->jabatan;

        $query = DB::table('user as u')
            ->leftJoin('pegawai as p', DB::raw("
            CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20))
        "), '=', 'p.nik')
            ->selectRaw("
            CAST(AES_DECRYPT(u.id_user, 'nur') AS CHAR(20)) AS nik,
            CAST(AES_DECRYPT(u.password, 'windi') AS CHAR(50)) AS password,
            p.nama,
            p.jbtn,
            p.departemen,
            p.bidang,
            p.stts_aktif
        ")
            ->when(
                $nama,
                fn($q) =>
                $q->where('p.nama', 'LIKE', "%{$nama}%")
            )
            ->when(
                $jabatan,
                fn($q) =>
                $q->where('p.jbtn', 'LIKE', "%{$jabatan}%")
            );

        // DATA TABLE
        $user = $query->orderBy('p.nama')->get();

        // TOTAL HASIL FILTER
        $totalUserPegawai = $user->count();

        // TOTAL USER (global, tidak ikut filter)
        $totalUser = DB::table('user')->count();

        return response()->json([
            'html' => view('admin.simrs.user.table', compact('user'))->render(),
            'totalUser' => $totalUser,
            'totalUserPegawai' => $totalUserPegawai,
        ]);
    }


    public function addToUserTicket(Request $request)
    {
        $request->validate([
            'id_user' => 'required|string|max:20',
            'nik'     => 'required|string|max:20',
        ]);

        $inserted = DB::table('dgarrozy_usertickets')->insertOrIgnore([
            'id_user'    => $request->id_user,
            'nik'        => $request->nik,
            'role_user'  => 'pembuat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($inserted === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'User sudah terdaftar'
            ], 409);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil ditambahkan'
        ]);
    }


    public function updateUserTicketRole(Request $request)
    {
        $request->validate([
            'id_user'   => 'required|string|max:20',
            'role_user' => 'required|in:pembuat,head_section,app_dept,approved,admin',
        ]);

        $exists = DB::table('dgarrozy_usertickets')
            ->where('id_user', $request->id_user)
            ->exists();

        if (!$exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User ticket belum ditambahkan'
            ]);
        }

        DB::table('dgarrozy_usertickets')
            ->where('id_user', $request->id_user)
            ->update([
                'role_user'  => $request->role_user,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Role berhasil diupdate'
        ]);
    }



    public function removeFromUserTicket(Request $request)
    {
        try {
            $request->validate([
                'id_user' => 'required|string|max:20',
            ]);

            $idUser = $request->id_user;

            $exists = DB::table('dgarrozy_usertickets')->where('id_user', $idUser)->exists();
            if (!$exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan di UserTickets'
                ]);
            }

            DB::table('dgarrozy_usertickets')->where('id_user', $idUser)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil dihapus dari UserTickets'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
