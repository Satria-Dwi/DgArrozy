<?php

namespace App\Http\Controllers\admin;

use App\Models\DgarrozyRole;
use Illuminate\Http\Request;
use App\Models\DgarrozyAccount;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class DgarrozyAccountController extends Controller
{
    // List semua akun
    public function index()
    {
        // Ambil semua akun beserta role
        $accounts = DgarrozyAccount::with('role')->get();

        return view('admin.account.index', compact('accounts'), [
            'title' => 'MArRozzy | Create Accounts',
            'active' => 'mainadmin'
        ]);
    }

    public function create()
    {
        return view('admin.account.create_accounts', [
            'title' => 'MArRozzy | Create Accounts',
            'active' => 'mainadmin'
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:dgarrozy_account,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:dgarrozy_role,id',
            'is_active' => 'nullable|in:0,1',
        ]);

        DgarrozyAccount::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.account.index')->with('success', 'Account berhasil dibuat');
    }

    // Tampilkan form edit akun
    public function edit($id)
    {
        $accounts = DgarrozyAccount::findOrFail($id);
        $roles = DgarrozyRole::all(); // untuk dropdown role

        return view('admin.account.edit', [
            'account' => $accounts,
            'roles' => $roles,
            'title' => 'Edit Account',
            'active' => 'mainadmin'
        ]);
    }

    // Update akun
    public function update(Request $request, $id)
    {
        $account = DgarrozyAccount::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:dgarrozy_account,email,' . $account->id,
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:dgarrozy_role,id',
            'is_active' => 'nullable|in:0,1',
        ]);

        $account->email = $request->email;
        $account->role_id = $request->role_id;
        $account->is_active = $request->is_active ?? 1;

        if ($request->password) {
            $account->password = Hash::make($request->password);
        }

        $account->save();

        // ✅ Redirect ke halaman daftar akun, jangan langsung view
        return redirect()->route('admin.account.index')
            ->with('success', 'Account berhasil diupdate');
    }

    // Hapus akun
    public function destroy($id)
    {
        $account = DgarrozyAccount::findOrFail($id);

        // Opsional: jangan hapus akun admin utama, atau akun sendiri
        // if ($account->role->code === 'admin' && auth()->id() === $account->id) {
        //     return redirect()->route('admin.account.index')->with('error', 'Tidak bisa menghapus akun sendiri.');
        // }

        $account->delete();

        return redirect()->route('admin.account.index')
            ->with('success', 'Account berhasil dihapus');
    }
}
