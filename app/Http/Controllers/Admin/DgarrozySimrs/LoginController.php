<?php

namespace App\Http\Controllers\Admin\DgarrozySimrs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        return view('simrs.login.index', [
            'title' => 'Login SIMRS',
        ]);
    }

    public function authenticate(Request $request)
    {
        // ================= RATE LIMIT =================
        $key = Str::lower($request->input('id_user')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('error', 'Terlalu banyak percobaan login. Coba lagi nanti.');
        }

        // ================= VALIDASI =================
        $validated = $request->validate([
            'id_user'  => 'required|string|min:1|max:30',
            'password' => 'required|string|min:1|max:50',
        ]);

        $idUser   = trim(strip_tags($validated['id_user']));
        $password = trim(strip_tags($validated['password']));

        // ================= CEK ADMIN =================
        $admin = $this->checkAdmin($idUser, $password);
        if ($admin) {
            $this->setAdminSession($request, $admin->username);
            RateLimiter::clear($key);
            return redirect('/marrozy')->with('success', 'Login admin berhasil');
        }

        // ================= CEK USER =================
        $user = $this->checkUser($idUser, $password);
        if (!$user) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'ID User atau Password salah');
        }

        // ================= CEK PEGAWAI =================
        $pegawai = $this->getPegawai($user->nik);
        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan / tidak aktif');
        }

        // ================= CEK ROLE =================
        [$tipe_user, $jabatan, $spesialis] = $this->determineRole($pegawai->nik);

        // ================= SET SESSION =================
        $request->session()->regenerate();

        session([
            'simrs_login' => true,
            'simrs_nik'   => $pegawai->nik,
            'simrs_nama'  => $pegawai->nama,
            'simrs_dept'  => $pegawai->nama_departemen ?? '-',
            'simrs_dep_id' => $pegawai->dep_id,
            'simrs_bdg'   => $pegawai->bidang,
            'simrs_jbtn'  => $jabatan,
            'simrs_sps'   => $spesialis,
            'simrs_tipe'  => $tipe_user, // admin | petugas | dokter | pegawai
        ]);

        RateLimiter::clear($key);

        // ================= REDIRECT BERDASARKAN ROLE =================
        switch ($tipe_user) {

            case 'dokter':
                return redirect('/dokter')
                    ->with('success', 'Login berhasil sebagai dokter');

            case 'petugas':
                return redirect('/marrozy')
                    ->with('success', 'Login berhasil sebagai petugas');

            case 'pegawai':
                return redirect('/marrozy')
                    ->with('success', 'Login berhasil');

            default:
                return redirect('/marrozy')
                    ->with('success', 'Login berhasil');
        }
    }

    // ====================== PRIVATE HELPERS ======================

    private function checkAdmin($idUser, $password)
    {
        return DB::table('admin')
            ->whereRaw("AES_DECRYPT(usere,'nur') = ?", [$idUser])
            ->whereRaw("AES_DECRYPT(passworde,'windi') = ?", [$password])
            ->selectRaw("CAST(AES_DECRYPT(usere,'nur') AS CHAR(50)) AS username")
            ->first();
    }

    private function setAdminSession(Request $request, $username)
    {
        $request->session()->regenerate();
        session([
            'simrs_login' => true,
            'simrs_role'  => 'admin',
            'simrs_user'  => $username,
            'simrs_nama'  => 'Administrator',
            'simrs_tipe'  => 'admin',
        ]);
    }

    private function checkUser($idUser, $password)
    {
        return DB::table('user as u')
            ->whereRaw("CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR) = ?", [$idUser])
            ->whereRaw("CAST(AES_DECRYPT(u.password,'windi') AS CHAR) = ?", [$password])
            ->selectRaw("CAST(AES_DECRYPT(u.id_user,'nur') AS CHAR(20)) AS nik")
            ->first();
    }

    private function getPegawai($nik)
    {
        return DB::table('pegawai as p')
            ->leftJoin('departemen as d', 'd.dep_id', '=', 'p.departemen')
            ->where('p.nik', $nik)
            ->where('p.stts_aktif', 'AKTIF')
            ->select([
                'p.nik',
                'p.nama',
                'p.bidang',
                'p.departemen as dep_id',   // ⬅️ TAMBAH INI
                'd.nama as nama_departemen',
            ])
            ->first();
    }

    /**
     * Tentukan role: petugas / dokter / pegawai default
     * @return array [$tipe_user, $jabatan, $spesialis]
     */
    private function determineRole($nik)
    {
        // ================= PETUGAS =================
        $petugas = DB::table('petugas')->where('nip', $nik)->first();
        if ($petugas) {
            $jabatan = DB::table('jabatan')
                ->where('kd_jbtn', $petugas->kd_jbtn)
                ->value('nm_jbtn');
            return ['petugas', $jabatan, null];
        }

        // ================= DOKTER =================
        $dokter = DB::table('dokter')->where('kd_dokter', $nik)->first();
        if ($dokter) {
            $spesialis = DB::table('spesialis')
                ->where('kd_sps', $dokter->kd_sps)
                ->value('nm_sps');
            return ['dokter', 'Dokter', $spesialis];
        }

        // ================= DEFAULT =================
        return ['pegawai', null, null];
    }


    public function logout(Request $request)
    {
        // Hanya hapus SESSION SIMRS, jangan flush seluruh session
        session()->forget([
            'simrs_login',
            'simrs_nik',
            'simrs_nama',
            'simrs_jbtn',
            'simrs_dept',
            'simrs_dep_id',
            'simrs_bdg',
            'simrs_sps',
            'simrs_tipe',
            'simrs_menu',
        ]);
        // Regenerate CSRF token untuk keamanan
        session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
