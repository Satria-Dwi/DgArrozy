@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-6">
                ✏️ Edit Perencanaan Keuangan
            </h2>

            <form action="{{ route('finances.update', $finance->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <input type="text" name="nama_perencanaan"
                        value="{{ $finance->nama_perencanaan }}"
                        class="input-modern" required>

                    <input type="text" name="modal_awal"
                        value="{{ number_format($finance->modal_awal, 2, ',', '.') }}"
                        class="input-modern" required>

                    <input type="text" name="total_pendapatan"
                        value="{{ number_format($finance->total_pendapatan, 2, ',', '.') }}"
                        class="input-modern" required>

                    <input type="text" name="total_pengeluaran"
                        value="{{ number_format($finance->total_pengeluaran, 2, ',', '.') }}"
                        class="input-modern" required>

                    <input type="text" name="total_perencanaan"
                        value="{{ number_format($finance->total_perencanaan, 2, ',', '.') }}"
                        class="input-modern" required>

                    <input type="text" name="deskripsi"
                        value="{{ $finance->deskripsi }}"
                        class="input-modern">
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl">
                        💾 Update
                    </button>

                    <a href="{{ route('finances.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl">
                        ⬅ Kembali
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
