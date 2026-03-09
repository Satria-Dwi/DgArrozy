@extends('simrs.layouts.app')

@section('content')
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 4px 8px;
            outline: none;
        }

        .dataTables_filter input:focus,
        .dataTables_length select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, .2);
        }

        .dataTables_info,
        .dataTables_length label,
        .dataTables_filter label {
            color: #000;
            font-weight: 500;
        }

        /* Table auto fit sesuai text */
        #rawatTable {
            width: 100% !important;
        }

        #rawatTable th,
        #rawatTable td {
            white-space: nowrap;
            width: 1%;
        }

        .dataTables_wrapper {
            overflow-x: auto;
        }
    </style>
    <div class="space-y-6">

        {{-- HEADER + JENIS RAWAT --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Data Detail Tindakan
                    </h1>
                    <p class="text-sm text-slate-500">
                        Filter dan lihat detail tindakan pasien
                    </p>
                </div>
            </div>
        </div>


        <div class="w-full md:w-auto">
            <label class="block text-sm font-semibold text-slate-600 mb-2">
                Jenis Layanan
            </label>

            <div class="inline-flex bg-slate-100 rounded-xl p-1 shadow-inner">

                <!-- RAWAT JALAN -->
                <button type="button"
                    class="tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 bg-white text-blue-600 shadow-sm"
                    data-jenis="ralan">
                    Rawat Jalan
                </button>

                <!-- RAWAT INAP -->
                <button type="button"
                    class="tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600"
                    data-jenis="ranap">
                    Rawat Inap
                </button>

                <!-- OPERASI -->
                <button type="button"
                    class="tabJenis px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 text-slate-600 hover:text-blue-600"
                    data-jenis="operasi">
                    Operasi
                </button>

            </div>

            <!-- hidden input untuk tetap dipakai JS -->
            <input type="hidden" id="jenisRawat" value="ralan">
        </div>
        {{-- FILTER CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-4">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Tanggal Mulai --}}
                <div>
                    <label for="start" class="block text-sm font-semibold text-slate-600 mb-1 cursor-pointer">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="start"
                        class="cursor-pointer w-full rounded-lg border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm">
                </div>

                {{-- Tanggal Akhir --}}
                <div>
                    <label for="end" class="block text-sm font-semibold text-slate-600 mb-1 cursor-pointer">
                        Tanggal Akhir
                    </label>
                    <input type="date" id="end"
                        class="cursor-pointer w-full rounded-lg border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm">
                </div>

                {{-- Spacer kosong supaya tombol rata kanan --}}
                <div class="hidden md:block"></div>

                {{-- Tombol --}}
                <div class="flex items-end">
                    <button id="filterBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 
                       text-white font-semibold rounded-lg py-2.5 
                       transition duration-200 shadow-md">
                        Tampilkan Data
                    </button>
                </div>

            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">

            <div class="overflow-x-auto">
                <table id="rawatTable" class="w-full text-sm text-left text-slate-700 border-collapse">

                    <thead class="bg-slate-100 text-xs uppercase text-slate-600">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">No Rawat</th>
                            <th class="px-4 py-3">No RM</th>
                            <th class="px-4 py-3">Nama Pasien</th>
                            <th class="px-4 py-3">Kode Perawatan</th>
                            <th class="px-4 py-3">Nama Perawatan</th>
                            <th class="px-4 py-3">Kode Dokter</th>
                            <th class="px-4 py-3">Nama Dokter</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Penanggung Jawab</th>
                            <th class="px-4 py-3" id="kolomTerakhirHeader">Poliklinik</th>
                            <th class="border px-2 py-1" id="dokterAnestesiHeader">Dokter Anestesi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">
                        {{-- Data AJAX --}}
                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection


@section('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- jQuery (WAJIB kalau belum ada di app.js) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/simrs/manajemen/detailtindakan/detailtindakan.js') }}"></script>
@endsection
