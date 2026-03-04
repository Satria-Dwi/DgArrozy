<?php

namespace App\Http\Controllers\admin\DgarrozySimrs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TicketErmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|max:100',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
        ]);

        DB::table('dgarrozy_ticketserm')->insert([
            'user_nik'       => session('simrs_nik'),
            'user_nama'      => session('simrs_nama'),
            'user_departemen' => session('simrs_dept'),
            'kode_ticket'    => 'TKT-' . time(), // auto generate kode unik
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'kategori'       => $request->kategori,
            'prioritas'      => $request->prioritas,
            'status'         => 'open',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return back()->with('success', 'Tiket berhasil dibuat!');
    }
}
