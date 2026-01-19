@extends('layouts.app')
@section('content')
    <!-- Sidebar -->
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Data Manajemen RSUD Ar Rozy</h1>
                {{-- <div class="flex items-center gap-4">
                    <span class="text-sm">Admin</span>
                    <img src="https://i.pravatar.cc/40" class="rounded-full" />
                </div> --}}
            </div>
            <div class="kpi-head">
                <div class="kpi-container">
                    <div class="kpi-card green">
                        <div class="kpi-header">
                            <span class="kpi-title">Total Pasien Terdaftar</span>
                            <i class="fas fa-user kpi-icon"></i>
                        </div>
                        <div class="kpi-value" id="totalPasien">-</div>
                        <div class="kpi-trend">Rekam Medis Tersedia</div>
                    </div>
                    <div class="kpi-card teal">
                        <div class="kpi-header">
                            <span class="kpi-title">IKM TW1 2026</span>
                            <i class="fas fa-smile kpi-icon"></i>
                        </div>
                        <div class="kpi-value">84,42</div>
                        <div class="kpi-trend">(Baik)</div>
                    </div>
                    <div class="kpi-card orange">
                        <div class="kpi-header">
                            <span class="kpi-title">Indeks Mutu</span>
                            <i class="fas fa-thumbs-up kpi-icon"></i>
                        </div>
                        <div class="kpi-value">---</div>
                        <div class="kpi-trend">(Baik)</div>
                    </div>
                    <div class="kpi-card blue">
                        <div class="kpi-header">
                            <span class="kpi-title">Rata2 Lama Layanan</span>
                            <i class="fas fa-heart kpi-icon"></i>
                        </div>
                        <div class="kpi-value">---</div>
                        <div class="kpi-trend">Menit</div>
                    </div>
                    <div class="kpi-card purple">
                        <div class="kpi-header">
                            <span class="kpi-title">Google Review</span>
                            <i class="fas fa-star kpi-icon"></i>
                        </div>
                        <div class="kpi-value">4.6</div>
                        <div class="kpi-trend">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>                       
            </div>

            <div class="container mx-auto p-6">

                <!-- ================= FILTER CARD ================= -->
                <div class="bg-white rounded-xl shadow-md p-4 mb-6">
                    <div class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="from" class="block text-sm font-medium text-gray-600 mb-1">
                                Dari Tanggal
                            </label>
                            <input type="date" id="from"
                                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label for="to" class="block text-sm font-medium text-gray-600 mb-1">
                                Sampai Tanggal
                            </label>
                            <input type="date" id="to"
                                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div class="flex gap-2">
                            <button id="btnFilter"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                                🔍 Filter
                            </button>

                            <button id="btnRefresh"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm font-semibold">
                                🔄 Refresh
                            </button>
                        </div>

                    </div>
                </div>

                <!-- ================= SUMMARY ================= -->
                <div class="mb-4 text-sm text-gray-600">
                    Filter Tanggal:
                    <span id="filterTanggal" class="font-semibold text-gray-800"></span>
                </div>

                <!-- ================= KPI CARDS ================= -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- REGISTRASI PASIEN -->
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    Registrasi Pasien
                                </p>
                                <p id="totalRegPasien" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                🧾
                            </div>
                        </div>
                    </div>

                    <!-- RAWAT INAP -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    Rawat Inap Aktif
                                </p>
                                <p id="totalRanap" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                🏥
                            </div>
                        </div>
                    </div>

                    <!-- RAWAT INAP -->
                    <div class="bg-gradient-to-br from-red-400 to-red-500 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    Poli Aktif
                                </p>
                                <p id="totalPoli" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                🏬
                            </div>
                        </div>
                    </div>

                    <!-- RAWAT IGD -->
                    <div class="bg-gradient-to-br from-purple-400 to-purple-500 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    IGD Aktif
                                </p>
                                <p id="totaligd" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                🚑
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- Operasi -->
                    <div class="bg-gradient-to-br from-gray-400 to-gray-500 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    Jadwal Operasi
                                </p>
                                <p id="totaloperasi" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                👩‍⚕️👨‍⚕️
                            </div>
                        </div>
                    </div>
                    
                    <!-- Operasi -->
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 text-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-wide opacity-80">
                                    Bayi Lahir Hari Ini
                                </p>
                                <p id="totaloperasi" class="text-4xl font-bold mt-2">
                                    0
                                </p>
                            </div>
                            <div class="text-4xl opacity-70">
                                👶
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="kpi-card orange">
                <div class="kpi-header">
                    <span class="kpi-title-mainadmin">Trend Kunjungan Poli
                        ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})</span>
                    <i class="fas fa-chart-bar kpi-icon"></i>
                </div>
                <div class="chart-wrapper-mainadmin">
                    <canvas id="chartKunjunganPoli"></canvas>
                </div>
            </div>

            <div class="kpi-head">
                <div class="kpi-container">
                    <div class="kpi-card teal">
                        <!-- ===== TEMPAT TIDUR PER BANGSAL ===== -->
                        <div class="kpi-header">
                            <span class="kpi-title-table">Detail Tempat Tidur per Hari Ini
                                ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})</span>
                            <i class="fas fa-table kpi-icon"></i>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Bangsal</th>
                                        <th class="px-4 py-2 text-center">Jumlah Bed</th>
                                        <th class="px-4 py-2 text-center">Bed Terisi</th>
                                        <th class="px-4 py-2 text-center">Bed Kosong</th>
                                        <th class="px-4 py-2 text-center">Persentase BOR</th>
                                    </tr>
                                </thead>
                                <tbody id="tempat_tidur_per_bangsal" class="divide-y divide-gray-100">
                                    <!-- Akan diisi JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/mainadmin.js') }}"></script>
@endsection
