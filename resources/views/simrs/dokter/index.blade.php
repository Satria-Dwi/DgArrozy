@extends('simrs.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-200">
        {{-- USER INFO --}}
        {{-- <div
            class="rounded-2xl bg-white/5 backdrop-blur-md border border-white/10
           shadow-lg shadow-black/20
           p-5 sm:p-6 mb-2">

            <!-- Header -->
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-xl
                       bg-indigo-500/15 text-indigo-400
                       flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-white">
                            Informasi User
                        </h2>
                        <p class="text-xs text-slate-400">
                            Data akun yang sedang login
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
               gap-4 text-sm">

                <!-- NIK -->
                <div class="rounded-xl bg-black/20 p-4 border border-white/5">
                    <span class="text-xs text-slate-400">NIK</span>
                    <div class="mt-1 font-semibold text-white truncate">
                        {{ $user['nik'] ?? '-' }}
                    </div>
                </div>

                <!-- Nama -->
                <div class="rounded-xl bg-black/20 p-4 border border-white/5">
                    <span class="text-xs text-slate-400">Nama</span>
                    <div class="mt-1 font-semibold text-white truncate">
                        {{ $user['nama'] ?? '-' }}
                    </div>
                </div>

                <!-- Jabatan -->
                <div class="rounded-xl bg-black/20 p-4 border border-white/5">
                    <span class="text-xs text-slate-400">Jabatan</span>
                    <div class="mt-1 font-semibold text-white truncate">
                        {{ $user['jabatan'] ?? '-' }}
                    </div>
                </div>

                <!-- Departemen -->
                <div class="rounded-xl bg-black/20 p-4 border border-white/5">
                    <span class="text-xs text-slate-400">Departemen</span>
                    <div class="mt-1 font-semibold text-white truncate">
                        {{ $user['departemen'] ?? '-' }}
                    </div>
                </div>

                @if (!empty($user['spesialis']))
                    <!-- Spesialis -->
                    <div
                        class="rounded-xl bg-emerald-500/10 p-4
                       border border-emerald-500/20
                       sm:col-span-2 lg:col-span-1">
                        <span class="text-xs text-emerald-400">Spesialis</span>
                        <div class="mt-1 font-semibold text-white flex items-center gap-2">
                            <span>🩺</span>
                            <span class="truncate">{{ $user['spesialis'] }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div> --}}

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
                                <div id="chartpasienperdokter"
                                    class="order-2
                                         h-[60px]">
                                </div>
                            </div>
                        </div>

                        {{-- INFO DOKTER --}}
                        <div
                            class="mt-6 md:mt-2
                                    flex items-center gap-2
                                    text-xs text-slate-400
                                    justify-center md:justify-start">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
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
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                            <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                Pasien Operasi
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Berdasarkan dokter yang sedang login
                            </p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-300 dark:bg-slate-800">
                                    <tr class="text-center text-slate-50">
                                        <th class="px-4 py-3 font-medium">No Rawat</th>
                                        <th class="px-4 py-3 font-medium">No Rekam Medis</th>
                                        {{-- <th class="px-4 py-3 font-medium">No RM</th> --}}
                                        <th class="px-4 py-3 font-medium">Nama Pasien</th>
                                        <th class="px-4 py-3 font-medium">Jenis Operasi</th>
                                        <th class="px-4 py-3 font-medium">Tanggal Operasi</th>
                                        <th class="px-4 py-3 font-medium">Status</th>
                                    </tr>
                                </thead>

                                <tbody id="tableOperasi" class="divide-y divide-slate-200 dark:divide-slate-800">
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                            Memuat data pasien...
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <!-- Footer -->
                            <div
                                class="px-4 py-2 border-t border-slate-200 dark:border-slate-800
                                         text-xs text-slate-500 dark:text-slate-400">
                                Data diperbarui otomatis
                            </div>
                            <div id="paginationOperasi" class="flex justify-center mt-4 gap-2 text-black"></div>
                        </div>
                    </div>
                @endif
            @endif
        @endrole
    </div>
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

    <script>
        let chartPasienDokter = null;
        let filterAktif = false;

        document.addEventListener('DOMContentLoaded', function() {

            // 🔥 Khusus admin & dokter
            @role(['admin', 'dokter'])
                loadDashboardMedis();
            @endrole

            // 🔥 Refresh tiap 1 menit
            setInterval(function() {

                @role(['admin', 'dokter'])
                    loadDashboardMedis();
                @endrole

            }, 60000);

        });
    </script>

    {{-- total pasien dokter --}}
    <script>
        function loadTotalPasienDokter() {
            fetch("{{ url('/dokter/total-pasien') }}")
                .then(res => res.json())
                .then(res => {
                    if (res.total_pasien !== undefined) {
                        document.getElementById('totalPasienDokter').innerText =
                            res.total_pasien;
                    }
                });
        }
    </script>

    {{-- Chart pasien per dokter --}}
    <script>
        function loadChartPasienPerDokter() {
            fetch("{{ url('/dokter/chart-pasien') }}")
                .then(res => res.json())
                .then(res => {

                    // CHART
                    const options = {
                        series: [{
                            name: 'Pasien',
                            data: res.data
                        }],
                        chart: {
                            height: 60,
                            sparkline: {
                                enabled: true
                            },
                            animations: {
                                enabled: true,
                                easing: 'easeinout',
                                speed: 300,
                                animateGradually: {
                                    enabled: false
                                },
                                dynamicAnimation: {
                                    enabled: false
                                }
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        markers: {
                            size: 4,
                            hover: {
                                size: 6
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: val => val,
                            offsetY: -6,
                            style: {
                                fontSize: '10px',
                                fontWeight: 600,
                                colors: ['#000']
                            }
                        },
                        tooltip: {
                            enabled: true,
                            theme: 'dark',
                            x: {
                                show: false
                            },
                            y: {
                                title: {
                                    formatter: () => '' // ⬅️ HILANGKAN KETERANGAN
                                },
                                formatter: val => val + ' pasien'
                            },
                            style: {
                                fontSize: '12px'
                            },
                            marker: {
                                show: true
                            }
                        }

                    };

                    if (!chartPasienDokter) {
                        chartPasienDokter = new ApexCharts(
                            document.querySelector("#chartpasienperdokter"),
                            options
                        );
                        chartPasienDokter.render();
                    } else {
                        chartPasienDokter.updateSeries(options.series);
                    }

                    // LABEL HARI (DI ATAS CHART, MOBILE AMAN)
                    const labelWrap = document.getElementById('labelHari');
                    labelWrap.innerHTML = '';

                    res.labels.forEach(item => {
                        const isToday =
                            item.full === new Date().toISOString().slice(0, 10);

                        labelWrap.innerHTML += `
                                <div class="flex flex-col items-center text-white
                                    ${isToday ? 'text-blue-400 font-semibold' : ''}">
                                    <span>${item.hari.substring(0,3)}</span>
                                    <span class="leading-none text-[9px]">/ ${item.tgl}</span>
                                </div>
                            `;
                    });
                });
        }
    </script>

    {{-- filter tanggal --}}
    <script>
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        // hanya set tampilan tanggal
        function setDefaultTanggalHariIni() {
            const today = formatDate(new Date());

            const tglAwal = document.getElementById('tglAwal');
            const tglAkhir = document.getElementById('tglAkhir');

            if (tglAwal && !tglAwal.value) tglAwal.value = today;
            if (tglAkhir && !tglAkhir.value) tglAkhir.value = today;
        }

        function getRangeTanggal() {
            return {
                tgl_awal: document.getElementById('tglAwal')?.value,
                tgl_akhir: document.getElementById('tglAkhir')?.value
            };
        }

        function triggerAutoFilter() {
            const {
                tgl_awal,
                tgl_akhir
            } = getRangeTanggal();

            if (tgl_awal && tgl_akhir) {
                filterAktif = true;
                reloadTables(); // 🔥 otomatis reload
                loadTotalOperasiDokter();
            }
        }

        function applyFilterTanggal() {

            const {
                tgl_awal,
                tgl_akhir
            } = getRangeTanggal();

            if (!tgl_awal || !tgl_akhir) {
                alert('Pilih tanggal awal dan akhir');
                return;
            }

            filterAktif = true;

            loadRawatInap();
            loadRawatJalan();
            loadTotalOperasiDokter();
            reloadTables();
        }

        function resetFilter() {

            filterAktif = false;

            document.getElementById('tglAwal').value = '';
            document.getElementById('tglAkhir').value = '';

            loadRawatInap();
            loadRawatJalan();
            loadTotalOperasiDokter();
            reloadTables();
        }

        function reloadTables() {
            loadtableRawatJalan();
            loadtableRawatInap();
            // 🔥 TAMBAHKAN INI
            if (typeof loadTableOperasi === 'function') {
                loadTableOperasi(1); // reset ke halaman 1 saat filter berubah
            }
        }
    </script>

    {{-- total pasien rawat inap dan rawat jalan sesuai dokter login --}}
    <script>
        function loadRawatInap() {
            const params = new URLSearchParams();

            if (filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal) params.append('tgl_awal', tgl_awal);
                if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
            }

            fetch(`/dokter/total-rawat-inap?${params}`)
                .then(res => res.json())
                .then(data => {
                    const el = document.getElementById("jumlahRanap");
                    if (el) el.textContent = data.jumlah_pasien_rawat_inap ?? 0;
                })
                .catch(err => console.error(err));
        }

        function loadRawatJalan() {
            const el = document.getElementById('rawatJalan');
            if (!el) return;

            const params = new URLSearchParams();

            // 🔥 HANYA kirim tanggal jika filter aktif
            if (filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal) params.append('tgl_awal', tgl_awal);
                if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
            }

            fetch(`{{ url('/dokter/total-rawat-jalan') }}?${params}`)
                .then(res => res.json())
                .then(res => {
                    el.innerText = res.total_pasien_rawat_jalan ?? 0;
                })
                .catch(err => console.error('Rawat jalan error:', err));
        }
    </script>

    {{-- total operasi per hari ini --}}
    <script>
        function loadTotalOperasiDokter() {
            const totalElem = document.getElementById('totalOperasi');
            if (!totalElem) return;

            const params = new URLSearchParams();

            if (filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal) params.append('tgl_awal', tgl_awal);
                if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
            }

            fetch(`{{ url('/dokter/operasi') }}?${params}`)
                .then(res => res.json())
                .then(res => {

                    if (res.error) {
                        totalElem.innerText = 0;
                        return;
                    }

                    totalElem.innerText = res.total_operasi ?? 0;
                })
                .catch(err => {
                    console.error(err);
                    totalElem.innerText = 0;
                });
        }
    </script>

    {{-- table rawat jalan --}}
    <script>
        let currentPageRalan = 1;

        function loadtableRawatJalan(page = 1) {

            currentPageRalan = page;

            const tbody = document.getElementById('tableRawatJalan');
            const pagination = document.getElementById('paginationRalan');
            if (!tbody) return;

            const params = new URLSearchParams();
            params.append('page', page);

            if (filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal) params.append('tgl_awal', tgl_awal);
                if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
            }

            fetch(`/dokter/pasien-rawat-jalan-hari-ini?${params}`)
                .then(res => res.json())
                .then(res => {

                    const data = res.data;
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center text-slate-400">
                                        Tidak ada pasien
                                    </td>
                                </tr>`;
                        pagination.innerHTML = '';
                        return;
                    }
                    // ===== ISI TABLE =====
                    data.forEach((row, index) => {

                        // 🔥 STRIPE CLASS
                        let stripeClass = index % 2 === 0 ?
                            'bg-white dark:bg-slate-900' :
                            'bg-slate-100 dark:bg-slate-800';

                        tbody.innerHTML += `
                                    <tr class="${stripeClass} border-b border-slate-300 dark:border-slate-800
                                            hover:bg-slate-200/50 dark:hover:bg-slate-700/40
                                            text-slate-900 dark:text-slate-100">

                                        <!-- NOMOR URUT HIDDEN -->
                                        <td class="hidden">${index + 1}</td>

                                        <td class="text-center">${row.no_rawat}</td>
                                        <td>${row.nm_pasien}</td>
                                        <td class="text-center">${row.png_jawab ?? '-'}</td>
                                        <td class="text-center">
                                            <button onclick="showDetailPasien('${row.no_rawat}', 'ralan')"
                                                class="px-3 py-1 rounded-lg text-sm
                                                    bg-emerald-600 hover:bg-emerald-700
                                                    text-white transition">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>`;
                    });

                    // ===== BUAT PAGINATION =====
                    renderPaginationRalan(res);

                })
                .catch(err => console.error(err));
        }


        function renderPaginationRalan(res) {

            const pagination = document.getElementById('paginationRalan');
            pagination.innerHTML = '';

            if (res.last_page <= 1) return;

            for (let i = 1; i <= res.last_page; i++) {

                pagination.innerHTML += `
                        <button onclick="loadtableRawatJalan(${i})"
                            class="px-3 py-1 rounded-lg text-sm border
                            ${i === res.current_page
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-white border-slate-300 dark:border-slate-700 hover:bg-blue-100 dark:hover:bg-slate-700'
                            }">
                            ${i}
                        </button>
                    `;
            }
        }
    </script>

    {{-- table rawat inap --}}
    <script>
        let currentPageRanap = 1;

        function loadtableRawatInap(page = 1) {

            currentPageRanap = page;

            const tbody = document.getElementById('tableRawatInap');
            const pagination = document.getElementById('paginationRanap');
            if (!tbody) return;

            const params = new URLSearchParams();
            params.append('page', page);

            if (filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal) params.append('tgl_awal', tgl_awal);
                if (tgl_akhir) params.append('tgl_akhir', tgl_akhir);
            }

            fetch(`/dokter/pasien-rawat-inap?${params}`)
                .then(res => res.json())
                .then(res => {

                    const data = res.data;
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = `
                                        <tr>
                                            <td colspan="6" class="text-center text-slate-400">
                                                Tidak ada pasien rawat inap
                                            </td>
                                        </tr>`;
                        pagination.innerHTML = '';
                        return;
                    }

                    // ===== ISI TABLE =====
                    data.forEach((row, index) => {

                        let statusKeluar = row.tgl_keluar &&
                            row.tgl_keluar !== '0000-00-00' ?
                            row.tgl_keluar :
                            '<span class="text-green-500 font-semibold">Masih Dirawat</span>';

                        // 🔥 STRIPE CLASS BERDASARKAN INDEX
                        let stripeClass = index % 2 === 0 ?
                            'bg-white dark:bg-slate-900' :
                            'bg-slate-100 dark:bg-slate-800';

                        tbody.innerHTML += `
                                <tr class="${stripeClass} border-b border-slate-300 dark:border-slate-800
                                        hover:bg-slate-200/50 dark:hover:bg-slate-700/40
                                        text-slate-900 dark:text-slate-100">

                                    <!-- NOMOR URUT HIDDEN -->
                                    <td class="hidden">${index + 1}</td>

                                    <td class="text-center">${row.no_rawat}</td>
                                    <td>${row.nm_pasien}</td>
                                    <td class="text-center">${row.tgl_masuk}</td>
                                    <td class="text-center">${statusKeluar}</td>
                                    <td class="text-center">${row.png_jawab ?? '-'}</td>
                                    <td class="text-center">
                                        <button onclick="showDetailPasien('${row.no_rawat}', 'ranap')"
                                            class="px-3 py-1 rounded-lg text-sm
                                                bg-emerald-600 hover:bg-emerald-700
                                                text-white transition">
                                            Detail
                                        </button>
                                    </td>
                                </tr>`;
                    });

                    renderPaginationRanap(res);

                })
                .catch(err => console.error(err));
        }


        function renderPaginationRanap(res) {

            const pagination = document.getElementById('paginationRanap');

            if (!pagination) return; // 🔥 TAMBAHAN WAJIB

            if (pagination) pagination.innerHTML = '';

            if (!res.last_page || res.last_page <= 1) return;

            for (let i = 1; i <= res.last_page; i++) {

                pagination.innerHTML += `
                        <button onclick="loadtableRawatInap(${i})"
                            class="px-3 py-1 rounded-lg text-sm border
                            ${i === res.current_page
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-white border-slate-300 dark:border-slate-700 hover:bg-blue-100 dark:hover:bg-slate-700'
                            }">
                            ${i}
                        </button>
                    `;
            }
        }
    </script>

    {{-- modal close detil --}}
    <script>
        function closeModalDetailRalan() {
            const modal = document.getElementById('modalDetailRalan');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function closeModalDetailRanap() {
            const modal = document.getElementById('modalDetailRanap');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    {{-- detil pasien --}}
    <script>
        function setText(modal, id, value = '-') {
            const el = modal.querySelector('#' + id);
            if (el) el.innerText = value;
        }

        function showRow(modal, id, show = true) {
            const el = modal.querySelector('#' + id);
            if (el) el.closest('p').style.display = show ? '' : 'none';
        }

        function showDetailPasien(noRawat, jenis = 'ralan') {
            if (!noRawat) return;

            const encoded = encodeURIComponent(noRawat);

            const config = {
                ralan: {
                    url: `/dokter/pasien-detail-ralan/${encoded}`,
                    modalId: 'modalDetailRalan',
                    before: (modal) => {
                        // sembunyikan field ranap
                        showRow(modal, 'detailKamar', false);
                        showRow(modal, 'detailTglMasuk', false);
                        showRow(modal, 'detailJamMasuk', false);
                        showRow(modal, 'detailDiagnosaAwal', false);
                        showRow(modal, 'detailDiagnosaAkhir', false);

                        // tampilkan field ralan
                        showRow(modal, 'detailPoli', true);
                        showRow(modal, 'detailDokter', true);
                        showRow(modal, 'detailTglKeluar', true);
                        showRow(modal, 'detailJamKeluar', true);

                    },
                    extra: (modal, d) => {
                        setText(modal, 'detailPoli', d.nm_poli);
                        setText(modal, 'detailDokter', d.nm_dokter);
                        setText(modal, 'detailTglKeluar', d.tgl_keluar);
                        setText(modal, 'detailJamKeluar', d.jam_keluar);

                    }
                },
                ranap: {
                    url: `/dokter/pasien-detail-ranap/${encoded}`,
                    modalId: 'modalDetailRanap',
                    before: (modal) => {
                        // tampilkan field ranap
                        showRow(modal, 'detailKamar', true);
                        showRow(modal, 'detailTglMasuk', true);
                        showRow(modal, 'detailJamMasuk', true);
                        showRow(modal, 'detailDiagnosaAwal', true);
                        showRow(modal, 'detailDiagnosaAkhir', true);

                        // sembunyikan field ralan
                        showRow(modal, 'detailPoli', false);
                        showRow(modal, 'detailDokter', false);
                    },
                    extra: (modal, d) => {
                        setText(modal, 'detailKamar', d.kd_kamar);
                        setText(modal, 'detailTglMasuk', d.tgl_masuk);
                        setText(modal, 'detailJamMasuk', d.jam_masuk);
                        setText(modal, 'detailTglKeluar', d.tgl_keluar);
                        setText(modal, 'detailJamKeluar', d.jam_keluar);
                        setText(modal, 'detailDiagnosaAwal', d.diagnosa_awal);
                        setText(modal, 'detailDiagnosaAkhir', d.diagnosa_akhir);
                    }
                }
            };

            const cfg = config[jenis];
            if (!cfg) return;

            const modal = document.getElementById(cfg.modalId);
            if (!modal) return;

            cfg.before(modal);

            fetch(cfg.url, {
                    headers: {
                        Accept: 'application/json'
                    }
                })
                .then(r => {
                    if (!r.ok) throw new Error(r.status);
                    return r.json();
                })
                .then(d => {
                    // ===== DATA UMUM =====
                    setText(modal, 'detailNoRawat', d.no_rawat);
                    setText(modal, 'detailNoRM', d.no_rkm_medis);
                    setText(modal, 'detailNama', d.nm_pasien);
                    setText(modal, 'detailJK', d.jk);
                    setText(modal, 'detailUmur', d.umur);
                    setText(modal, 'detailAlamat', d.alamat);
                    setText(modal, 'detailPnjwb', d.png_jawab ?? '-');
                    setText(modal, 'detailDokter', d.nm_dokter ?? '-');


                    // ===== KHUSUS =====
                    cfg.extra(modal, d);

                    // ===== SHOW MODAL =====
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                })
                .catch(err => {
                    console.error('Fetch detail pasien gagal:', err);
                    alert('Detail pasien gagal dimuat');
                });
        }
    </script>

    {{-- operasi perbulan --}}
    <script>
        let currentPageOperasi = 1;

        function loadTableOperasi(page = 1) {

            currentPageOperasi = page;

            const tbody = document.getElementById('tableOperasi');
            const pagination = document.getElementById('paginationOperasi');

            if (!tbody) return;

            tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-slate-400">
                                Loading...
                            </td>
                        </tr>
                    `;

            const params = new URLSearchParams();
            params.append('page', page);

            if (typeof filterAktif !== 'undefined' && filterAktif) {
                const {
                    tgl_awal,
                    tgl_akhir
                } = getRangeTanggal();
                if (tgl_awal && tgl_akhir) {
                    params.append('tgl_awal', tgl_awal);
                    params.append('tgl_akhir', tgl_akhir);
                }
            }

            fetch(`/dokter/total-operasi-hari-ini?${params.toString()}`)
                .then(res => res.json())
                .then(res => {

                    tbody.innerHTML = '';

                    if (!res.data || res.data.length === 0) {
                        tbody.innerHTML = `
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-slate-400">
                                                Tidak ada data operasi
                                            </td>
                                        </tr>
                                    `;
                        if (pagination) pagination.innerHTML = '';
                        return;
                    }

                    res.data.forEach(row => {

                        let badgeClass = 'bg-emerald-100 text-emerald-600';

                        if (row.status === 'Batal')
                            badgeClass = 'bg-red-100 text-red-600';
                        else if (row.status === 'Selesai')
                            badgeClass = 'bg-blue-100 text-blue-600';
                        else if (row.status === 'Menunggu')
                            badgeClass = 'bg-yellow-100 text-yellow-600';

                        tbody.innerHTML += `
                                    <tr class="border-b border-slate-300 dark:border-slate-800
                                            hover:bg-slate-200/50 dark:hover:bg-slate-800/40
                                            text-black">
                                        <td class="text-center">${row.no_rawat}</td>
                                        <td class="text-center">${row.no_rkm_medis}</td>
                                        <td>${row.nm_pasien}</td>
                                        <td>${row.nm_perawatan}</td>
                                        <td class="text-center">${row.tanggal} ${row.jam_mulai ?? ''}</td>
                                        <td class="text-center">
                                            <span class="px-2 py-1 rounded text-xs ${badgeClass}">
                                                ${row.status}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                    });

                    renderPaginationOperasi(res);
                })
                .catch(err => {
                    console.error(err);
                });
        }


        // 🔥 PISAHKAN DI LUAR
        function renderPaginationOperasi(res) {

            const pagination = document.getElementById('paginationOperasi');
            if (!pagination) return;

            pagination.innerHTML = '';

            if (!res.last_page || res.last_page <= 1) return;

            if (res.current_page > 1) {
                pagination.innerHTML += `
                            <button onclick="loadTableOperasi(${res.current_page - 1})"
                                class="px-3 py-1 border rounded">
                                Prev
                            </button>
                        `;
            }

            for (let i = 1; i <= res.last_page; i++) {
                pagination.innerHTML += `
                            <button onclick="loadTableOperasi(${i})"
                                class="px-3 py-1 border rounded
                                ${i === res.current_page ? 'bg-blue-600 text-white' : ''}">
                                ${i}
                            </button>
                        `;
            }

            if (res.current_page < res.last_page) {
                pagination.innerHTML += `
                            <button onclick="loadTableOperasi(${res.current_page + 1})"
                                class="px-3 py-1 border rounded">
                                Next
                            </button>
                        `;
            }
        }
    </script>
@endsection
