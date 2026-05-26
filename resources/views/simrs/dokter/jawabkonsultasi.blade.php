@extends('simrs.layouts.app')

@section('content')
    {{-- <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-200"> --}}

    @role(['admin', 'dokter'])
        {{-- CARD TOTAL PASIEN DOKTER --}}
        <div
            class="relative overflow-hidden rounded-2xl
                        bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800
                        border border-white/10
                        p-6 shadow-lg
                        transition-all duration-300
                        hover:-translate-y-1 hover:shadow-blue-500/20 mb-2">

            {{-- Glow Accent --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>

            <div class="relative flex items-center justify-between gap-4">

                {{-- LEFT --}}
                <div class="flex-1">

                    <p class="text-xs uppercase tracking-wider text-slate-400">
                        Total Pasien Hari Ini
                    </p>

                    {{-- ANGKA + CHART --}}
                    <div
                        class="mt-2
                                    flex flex-col items-center
                                    md:flex-row md:items-center md:justify-between
                                    gap-2">

                        {{-- ANGKA --}}
                        <h2 id="totalPasienDokter"
                            class="text-4xl md:text-4xl font-extrabold text-white leading-none tracking-tight">
                            0
                        </h2>

                        {{-- CHART --}}
                        <div
                            class="flex flex-col
                                        md:flex-col
                                        w-50 md:w-[240px]
                                        h-auto
                                        md:ml-auto">

                            {{-- LABEL HARI --}}
                            <h2 id="labelHari"
                                class="order-1
                                            text-[10px] text-slate-400
                                            flex justify-between
                                            mb-3 md:mb-1">
                            </h2>

                            {{-- CHART --}}
                            <div id="chartpasienperdokter" class="order-2
                                         h-[60px]">
                            </div>
                        </div>
                    </div>

                    {{-- INFO DOKTER --}}
                    <div
                        class="mt-6 md:mt-2
           flex items-center gap-2
           text-2xl text-slate-400
           justify-center md:justify-start">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3M4 11h16M5 19h14a2 2 0 002-2v-6H3v6a2 2 0 002 2z" />
                        </svg>
                        <span>Dokter: {{ session('simrs_nama') }}</span>
                    </div>

                </div>


                {{-- RIGHT ICON --}}
                <div
                    class="flex items-center justify-center
                                w-14 h-14 rounded-xl
                                bg-gradient-to-br from-blue-500/20 to-cyan-500/20
                                text-blue-400 shadow-inner">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5h6a2 2 0 012 2v2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2H5a2 2 0 01-2-2v-2a2 2 0 012-2h2V7a2 2 0 012-2z" />
                    </svg>
                </div>
            </div>

            {{-- FILTER DATE RANGE --}}
            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 
                            rounded-2xl p-4 mb-4 shadow-lg shadow-black/20 mt-4 text-center content-center">

                <div class="flex flex-col md:flex-row gap-3 md:items-end justify-center">

                    {{-- Tanggal Awal --}}
                    <div class="relative">
                        <label class="text-sm text-slate-400 mb-1">Tanggal Awal</label>
                        <input type="date" id="tglAwal"
                            class="w-full px-3 py-2 rounded-lg bg-white border border-white/10 
                                             focus:ring-2 focus:ring-blue-500 outline-none text-black">
                    </div>


                    {{-- Tanggal Akhir --}}
                    <div class="relative">
                        <label class="text-sm text-slate-400 mb-1">Tanggal Akhir</label>
                        <input type="date" id="tglAkhir"
                            class="w-full px-3 py-2 rounded-lg bg-white border border-white/10 
                                         focus:ring-2 focus:ring-blue-500 outline-none text-black">
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-2 mt-2 md:mt-0">

                        <button onclick="applyFilterTanggal()"
                            class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 
                           transition text-white font-medium">
                            Apply
                        </button>

                        <button onclick="resetFilter()"
                            class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 
                           transition text-white font-medium">
                            Reset
                        </button>

                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                {{-- Total Pasien rawat Jalan --}}
                <div
                    class="relative overflow-hidden rounded-2xl
                                bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900
                                border border-white/10
                                p-5 md:p-6
                                shadow-xl
                                transition-all duration-300
                                hover:-translate-y-1 hover:shadow-emerald-400/20
                                mb-2 mt-2">

                    <!-- Neon line accent -->
                    <div
                        class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-emerald-400 to-transparent">
                    </div>

                    <!-- Soft glow -->
                    <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-emerald-400/20 rounded-full blur-3xl"></div>

                    <!-- Content -->
                    <div class="relative">

                        <!-- Title -->
                        <p class="text-[10px] md:text-xs uppercase tracking-wider text-white font-semibold">
                            Total Pasien Rawat Jalan
                        </p>

                        <!-- Total -->
                        <h2
                            class="mt-2
                                        text-4xl md:text-5xl
                                        font-extrabold text-white
                                        flex items-end gap-2
                                        leading-none tracking-tight tabular-nums">

                            <span id="rawatJalan">0</span>

                            <span class="text-sm md:text-base font-semibold text-white">
                                pasien
                            </span>
                        </h2>

                        <!-- Subtitle -->
                        <p class="mt-1 text-xs md:text-sm text-slate-200">
                            Hari ini
                        </p>
                    </div>
                </div>

                {{-- Total pasien rawat inap --}}
                <div
                    class="relative overflow-hidden rounded-2xl
                                bg-gradient-to-br from-sky-600 via-blue-700 to-slate-900
                                border border-white/10
                                p-5 md:p-6
                                shadow-xl
                                transition-all duration-300
                                hover:-translate-y-1 hover:shadow-sky-400/20
                                mb-2 mt-2">

                    <!-- Neon line accent -->
                    <div
                        class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-sky-400 to-transparent">
                    </div>

                    <!-- Soft glow -->
                    <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-emerald-400/20 rounded-full blur-3xl"></div>

                    <!-- Content -->
                    <div class="relative">

                        <!-- Title -->
                        <p class="text-[10px] md:text-xs uppercase tracking-wider text-white font-semibold">
                            Total Pasien Rawat Inap
                        </p>

                        <!-- Total -->
                        <h2
                            class="mt-2
                                        text-4xl md:text-5xl
                                        font-extrabold text-white
                                        flex items-end gap-2
                                        leading-none tracking-tight tabular-nums">

                            <span id="jumlahRanap">0</span>

                            <span class="text-sm md:text-base font-semibold text-white">
                                pasien
                            </span>
                        </h2>

                        <!-- Subtitle -->
                        <p class="mt-1 text-xs md:text-sm text-slate-200">
                            Hari ini
                        </p>
                    </div>
                </div>

                {{-- CARD KHUSUS BEDAH & ORTOPEDI --}}
                @if (isset($user['spesialis']))
                    @php
                        $spesialis = strtolower($user['spesialis']);
                    @endphp

                    @if (str_contains($spesialis, 'bedah') ||
                            str_contains($spesialis, 'ortopedi') ||
                            Str_contains($spesialis, 'orthopaedi') ||
                            str_contains($spesialis, 'kandungan') ||
                            str_contains($spesialis, 'kebidanan'))
                        <div
                            class="relative overflow-hidden rounded-2xl
                                    bg-gradient-to-br from-red-700 via-red-900 to-slate-900
                                    border border-white/10
                                    p-5 md:p-6
                                    shadow-xl
                                    transition-all duration-300
                                    hover:-translate-y-1 hover:shadow-red-400/20
                                    mb-2 mt-2">

                            <!-- Neon line accent -->
                            <div
                                class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-red-400 to-transparent">
                            </div>

                            <!-- Soft glow -->
                            <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-emerald-400/20 rounded-full blur-3xl">
                            </div>

                            <!-- Content -->
                            <div class="relative">

                                <!-- Title -->
                                <p class="text-[10px] md:text-xs uppercase tracking-wider text-white font-semibold">
                                    Total Pasien Operasi Hari Ini
                                </p>

                                <!-- Total -->
                                <h2
                                    class="mt-2
                                            text-4xl md:text-5xl
                                            font-extrabold text-white
                                            flex items-end gap-2
                                            leading-none tracking-tight tabular-nums">

                                    <span id="totalOperasi">0</span>

                                    <span class="text-sm md:text-base font-semibold text-white">
                                        pasien
                                    </span>
                                </h2>

                                <!-- Subtitle -->
                                <p class="mt-1 text-xs md:text-sm text-slate-200">
                                    Hari ini
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900
                        border border-slate-200 dark:border-slate-800
                        rounded-lg shadow-sm mb-2">

            <!-- Header -->
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    Pasien Rawat Jalan Hari Ini
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Berdasarkan dokter yang sedang login
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-300 dark:bg-slate-800">
                        <tr class="text-center text-slate-50">
                            <th class="px-4 py-3 font-medium">No Rawat</th>
                            {{-- <th class="px-4 py-3 font-medium">No RM</th> --}}
                            <th class="px-4 py-3 font-medium">Nama Pasien</th>
                            <th class="px-4 py-3 font-medium">Penjamin</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableRawatJalan" class="divide-y divide-slate-200 dark:divide-slate-800">

                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                Memuat data pasien...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div
                class="px-4 py-2 border-t border-slate-200 dark:border-slate-800
                    text-xs text-slate-500 dark:text-slate-400">
                Data diperbarui otomatis
            </div>
            <div id="modalDetailRalan" class="fixed inset-0 bg-black/50 items-center justify-center hidden">

                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-3 text-green-500">Detail Pasien</h3>

                    <p class="text-black dark:text-white">No Rawat : <span id="detailNoRawat"></span></p>
                    <p class="text-black dark:text-white">No RM : <span id="detailNoRM"></span></p>
                    <p class="text-black dark:text-white">Nama : <span id="detailNama"></span></p>
                    <p class="text-black dark:text-white">Jenis Kelamin : <span id="detailJK"></span></p>
                    <p class="text-black dark:text-white">Umur: <span id="detailUmur"></span></p>
                    <p class="text-black dark:text-white">Alamat: <span id="detailAlamat"></span></p>
                    <p class="text-black dark:text-white">Poli : <span id="detailPoli"></span></p>
                    <p class="text-black dark:text-white">Penjamin : <span id="detailPnjwb"></span></p>
                    <p class="text-black dark:text-white">Dokter : <span id="detailDokter"></span></p>

                    <button onclick="closeModalDetailRalan()" class="mt-4 px-4 py-2 bg-slate-600 text-white rounded">
                        Tutup
                    </button>
                </div>
            </div>
            <div id="paginationRalan" class="flex justify-center mt-2 mb-2 gap-2"></div>
        </div>

        <div
            class="bg-white dark:bg-slate-900
                        border border-slate-200 dark:border-slate-800
                        rounded-lg shadow-sm mb-2">

            <!-- Header -->
            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    Pasien Rawat Inap Hari Ini
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Berdasarkan dokter yang sedang login
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-300 dark:bg-slate-800">
                        <tr class="text-center text-slate-50">
                            <th class="px-4 py-3 font-medium">No Rawat</th>
                            {{-- <th class="px-4 py-3 font-medium">No RM</th> --}}
                            <th class="px-4 py-3 font-medium">Nama Pasien</th>
                            <th class="px-4 py-3 font-medium">Tanggal Masuk</th>
                            <th class="px-4 py-3 font-medium">Tanggal Keluar</th>
                            <th class="px-4 py-3 font-medium">Penjamin</th>
                            <th class="px-4 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableRawatInap" class="divide-y divide-slate-200 dark:divide-slate-800">

                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                Memuat data pasien...
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div
                class="px-4 py-2 border-t border-slate-200 dark:border-slate-800
                    text-xs text-slate-500 dark:text-slate-400">
                Data diperbarui otomatis
            </div>
            <div id="modalDetailRanap" class="fixed inset-0 bg-black/50 items-center justify-center hidden">

                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-3 text-green-500">Detail Pasien</h3>

                    <p class="text-black dark:text-white">No Rawat : <span id="detailNoRawat"></span></p>
                    <p class="text-black dark:text-white">No RM : <span id="detailNoRM"></span></p>
                    <p class="text-black dark:text-white">Nama : <span id="detailNama"></span></p>
                    <p class="text-black dark:text-white">Jenis Kelamin : <span id="detailJK"></span></p>
                    <p class="text-black dark:text-white">Umur : <span id="detailUmur"></span></p>
                    <p class="text-black dark:text-white">Alamat : <span id="detailAlamat"></span></p>
                    <p class="text-black dark:text-white">Kamar : <span id="detailKamar"></span></p>
                    <p class="text-black dark:text-white">Tgl Masuk : <span id="detailTglMasuk"></span></p>
                    <p class="text-black dark:text-white">Jam Masuk : <span id="detailJamMasuk"></span></p>
                    <p class="text-black dark:text-white">tgl Keluar : <span id="detailTglKeluar"></span></p>
                    <p class="text-black dark:text-white">Jam Keluar : <span id="detailJamKeluar"></span></p>
                    <p class="text-black dark:text-white">Diagnosa Awal : <span id="detailDiagnosaAwal"></span></p>
                    <p class="text-black dark:text-white">Diagnosa Akhir : <span id="detailDiagnosaAkhir"></span></p>
                    <p class="text-black dark:text-white">Dokter : <span id="detailPnjwb"></span></p>

                    <button onclick="closeModalDetailRanap()" class="mt-4 px-4 py-2 bg-slate-600 text-white rounded">
                        Tutup
                    </button>
                </div>
            </div>
            <div id="paginationRanap" class="flex justify-center mt-2 mb-2 gap-2"></div>
        </div>
    @endrole

    @role(['admin', 'dokter'])
        @if (isset($user['spesialis']))
            @php
                $spesialis = strtolower($user['spesialis']);
            @endphp

            @if (str_contains($spesialis, 'bedah') ||
                    str_contains($spesialis, 'ortopedi') ||
                    Str_contains($spesialis, 'orthopaedi') ||
                    str_contains($spesialis, 'kandungan') ||
                    str_contains($spesialis, 'kebidanan'))
                {{-- TABLE OPERASI --}}
                <div
                    class="bg-white dark:bg-slate-900
border border-slate-200 dark:border-slate-800
rounded-lg shadow-sm mb-2">

                    <div class="px-4 py-3 border-b
    border-slate-200 dark:border-slate-800">

                        <h3 class="text-sm font-semibold
        text-slate-800 dark:text-slate-200">

                            Permintaan Konsultasi Dokter

                        </h3>

                        <p class="text-xs text-slate-500">

                            Konsultasi yang belum dijawab

                        </p>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full text-sm">

                            <thead class="bg-slate-300 dark:bg-slate-800">

                                <tr class="text-center text-slate-50">

                                    <th>Tanggal</th>
                                    <th>No Permintaan</th>
                                    <th>No RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Dokter Konsul</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody id="tableKonsultasi">

                                <tr>

                                    <td colspan="6" class="text-center py-4">

                                        Loading...

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div id="paginationKonsultasi" class="flex justify-center
    mt-3 mb-3 gap-2">

                    </div>

                </div>
            @endif
        @endif
    @endrole
    {{-- </div> --}}
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @role(['admin', 'dokter'])
        <script>
            function loadDashboardMedis() {

                loadTotalPasienDokter();
                loadChartPasienPerDokter();

                if (!filterAktif) {
                    setDefaultTanggalHariIni();
                }

                @php
                    $spesialis = strtolower($user['spesialis'] ?? '');
                    $isBedahOrOrtho = str_contains($spesialis, 'bedah') || str_contains($spesialis, 'ortopedi') || str_contains($spesialis, 'orthopaedi') || str_contains($spesialis, 'kandungan') || str_contains($spesialis, 'kebidanan');
                @endphp

                @if ($isBedahOrOrtho)
                    loadTotalOperasiDokter();
                    loadTableOperasi(1);
                @endif

                loadRawatInap();
                loadRawatJalan();
                loadtableRawatJalan();
                loadtableRawatInap();
            }
        </script>
    @endrole

    <script src="{{ asset('js/simrs/dokter.konsultasi.js') }}"></script>
@endsection
