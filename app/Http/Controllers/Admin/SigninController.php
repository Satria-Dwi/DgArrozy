<?php

namespace App\Http\Controllers\admin;

use App\Models\DgarrozyAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SigninController extends Controller
{
    public function index()
    {
        // ✅ cek session admin saja
        if (session()->has('admin_login')) {
            return redirect('/mainadmin');
        }

        return view('signin.index', [
            'title' => 'Signin | Portal DgArRozy',
            'active' => 'signin'
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $account = DgarrozyAccount::with('role')
            ->where('email', $request->email)
            ->first();

        if (!$account) {
            return back()->with('signinneror', 'Email tidak terdaftar');
        }

        if ((int) $account->is_active !== 1) {
            return back()->with('signinneror', 'Akun tidak aktif');
        }

        if (!Hash::check($request->password, $account->password)) {
            return back()->with('signinneror', 'Password salah');
        }

        // ✅ SIMPAN SESSION KHUSUS ADMIN (PAKAI PREFIX)
        session([
            'admin_login'            => true,
            'admin_account_id'       => $account->id,
            'admin_account_email'    => $account->email,
            'admin_role_id'          => $account->role_id,
            'admin_role_code'        => $account->role->code ?? null,
            'admin_role_name'        => $account->role->name ?? null,
            'admin_account_is_active'=> (int) $account->is_active,
        ]);

        return redirect('/mainadmin');
    }

    public function signout()
    {
        // ❌ JANGAN flush()
        // ✅ hapus SESSION ADMIN SAJA
        session()->forget([
            'admin_login',
            'admin_account_id',
            'admin_account_email',
            'admin_role_id',
            'admin_role_code',
            'admin_role_name',
            'admin_account_is_active',
        ]);

        session()->regenerateToken();

        return redirect('/');
    }
}
