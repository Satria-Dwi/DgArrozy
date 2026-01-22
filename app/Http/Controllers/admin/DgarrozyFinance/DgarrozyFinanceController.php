<?php

namespace App\Http\Controllers\admin\DgarrozyFinance;

use App\Models\DgarrozyFinance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DgarrozyFinanceController extends Controller
{
    /**
     * Helper: format Indonesia ke decimal
     */
    private function toDecimal($value)
    {
        if (!$value) return 0;

        return (float) str_replace(['.', ','], ['', '.'], $value);
    }

    /**
     * Index
     */
    public function index()
    {
        $finances = DgarrozyFinance::latest()->get();
        $totalPendapatan = $finances->sum('total_pendapatan');

        return view('admin.finances.index', compact('finances', 'totalPendapatan'), [
            'title' => 'MArRozzy | Finance'
        ]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perencanaan'   => 'required|string|max:150',
            'total_perencanaan' => 'required',
            'modal_awal'         => 'required',
            'total_pengeluaran' => 'required',
            'total_pendapatan'  => 'required',
        ]);

        $modal_awal        = $this->toDecimal($request->modal_awal);
        $total_pengeluaran = $this->toDecimal($request->total_pengeluaran);
        $total_pendapatan  = $this->toDecimal($request->total_pendapatan);

        DgarrozyFinance::create([
            'nama_perencanaan'   => $request->nama_perencanaan,
            'total_perencanaan' => $this->toDecimal($request->total_perencanaan),
            'deskripsi'          => $request->deskripsi,
            'modal_awal'         => $modal_awal,
            'total_pengeluaran' => $total_pengeluaran,
            'total_pendapatan'  => $total_pendapatan,
            'modal_akhir'        => $modal_awal + $total_pendapatan - $total_pengeluaran,
        ]);

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            $finances = DgarrozyFinance::latest()->get();
            $totalPendapatan = $finances->sum('total_pendapatan');

            return response()->json([
                'table' => view('admin.finances.partials.table', compact('finances'))->render(),
                'total_pendapatan' => number_format($totalPendapatan, 2, ',', '.')
            ]);
        }


        return redirect()
            ->route('finances.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Edit
     */
    public function edit(DgarrozyFinance $finance)
    {
        return view('admin.finances.edit', compact('finance'), [
            'title' => 'MArRozzy | Finance'
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, DgarrozyFinance $finance)
    {
        $request->validate([
            'nama_perencanaan'   => 'required|string|max:150',
            'total_perencanaan' => 'required',
            'modal_awal'         => 'required',
            'total_pengeluaran' => 'required',
            'total_pendapatan'  => 'required',
        ]);

        $modal_awal        = $this->toDecimal($request->modal_awal);
        $total_pengeluaran = $this->toDecimal($request->total_pengeluaran);
        $total_pendapatan  = $this->toDecimal($request->total_pendapatan);

        $finance->update([
            'nama_perencanaan'   => $request->nama_perencanaan,
            'total_perencanaan' => $this->toDecimal($request->total_perencanaan),
            'deskripsi'          => $request->deskripsi,
            'modal_awal'         => $modal_awal,
            'total_pengeluaran' => $total_pengeluaran,
            'total_pendapatan'  => $total_pendapatan,
            'modal_akhir'        => $modal_awal + $total_pendapatan - $total_pengeluaran,
        ]);

        return redirect()
            ->route('finances.index')
            ->with('success', 'Data berhasil diupdate');
    }

    /**
     * Destroy
     */
    public function destroy(DgarrozyFinance $finance, Request $request)
    {
        $finance->delete();

        if ($request->ajax()) {
            $finances = DgarrozyFinance::latest()->get();
            $totalPendapatan = $finances->sum('total_pendapatan');

            return response()->json([
                'table' => view('admin.finances.partials.table', compact('finances'))->render(),
                'total_pendapatan' => number_format($totalPendapatan, 2, ',', '.')
            ]);
        }


        return redirect()
            ->route('finances.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
