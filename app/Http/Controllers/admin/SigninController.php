<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DgarrozyAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SigninController extends Controller
{
    public function index()
    {
        if (session()->has('dgarrozy_login')) {
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
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $account = DgarrozyAccount::where('email', $request->email)->first();

        if (!$account) {
            return back()->with('signinneror', 'Email tidak terdaftar');
        }

        if ($account->is_active != 1) {
            return back()->with('signinneror', 'Akun tidak aktif');
        }

        if (!Hash::check($request->password, $account->password)) {
            return back()->with('signinneror', 'Password salah');
        }

        session([
            'dgarrozy_login' => true,
            'account_id'    => $account->id,
            'account_email' => $account->email,
            'account_role'  => $account->role,
            'account_is_active' => (int) $account->is_active
        ]);

        return redirect('/mainadmin');
    }

    public function signout()
    {
        session()->flush();
        session()->regenerateToken();
        return redirect('/');
    }
}
