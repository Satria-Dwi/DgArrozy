@extends('admin.mainlayouts.app')
@section('content')
    <!-- Sidebar -->
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-3 main-content">
            <!-- Header -->
            {{-- <div class="flex justify-between items-center mb-3">
                <h1 class="text-3xl font-bold">Data Manajemen RSUD Ar Rozy</h1> --}}
            {{-- <div class="flex items-center gap-4">
                    <span class="text-sm">Admin</span>
                    <img src="https://i.pravatar.cc/40" class="rounded-full" />
                </div> --}}
            {{-- </div> --}}
            <div class="kpi-head-mainmenu">
                <div class="kpi-container-mainmenu">
                    <div class="kpi-card-mainmenu green">
                        <div class="kpi-header-mainmenu">
                            <span>Total Pasien Terdaftar</span>
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="kpi-value-mainmenu" id="totalPasien">-</div>
                        <div class="kpi-trend text-white">Rekam Medis Tersedia</div>
                    </div>

                    <div class="kpi-card-mainmenu teal">
                        <div class="kpi-header-mainmenu">
                            <span>IKM TW1 2026</span>
                            <i class="fas fa-smile"></i>
                        </div>
                        <div class="kpi-value-mainmenu">84,42</div>
                        <div class="kpi-trend text-white">Baik</div>
                    </div>

                    <div class="kpi-card-mainmenu orange">
                        <div class="kpi-header-mainmenu">
                            <span>Indeks Mutu</span>
                            <i class="fas fa-thumbs-up"></i>
                        </div>
                        <div class="kpi-value-mainmenu">---</div>
                        <div class="kpi-trend text-white">Baik</div>
                    </div>

                    <div class="kpi-card-mainmenu blue">
                        <div class="kpi-header-mainmenu">
                            <span>Rata-rata Lama Layanan</span>
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="kpi-value-mainmenu">---</div>
                        <div class="kpi-trend text-white">Menit</div>
                    </div>

                    <div class="kpi-card-mainmenu purple">
                        <div class="kpi-header-mainmenu">
                            <span>Google Review</span>
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="kpi-value-mainmenu">4.6</div>
                        <div class="kpi-trend text-white">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto p-6">

                <!-- ================= FILTER CARD ================= -->
                <div class="filter-card">
                    <div class="filter-grid">

                        <div class="filter-item">
                            <label for="from">Dari Tanggal</label>
                            <input type="date" id="from">
                        </div>

                        <div class="filter-item">
                            <label for="to">Sampai Tanggal</label>
                            <input type="date" id="to">
                        </div>

                        <div class="filter-action">
                            <button id="btnFilter" class="btn-primary">
                                <i class="fas fa-search"></i>
                                Filter
                            </button>

                            <button id="btnRefresh" class="btn-secondary">
                                <i class="fas fa-rotate"></i>
                                Refresh
                            </button>
                        </div>

                    </div>
                </div>


                <!-- ================= SUMMARY ================= -->
                <div class="mb-6 flex items-center gap-2 text-sm text-slate-600">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                    <span>Filter Tanggal:</span>
                    <span id="filterTanggal" class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 font-semibold">
                        -
                    </span>
                </div>


                <!-- ================= KPI CARDS ================= -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Registrasi Pasien -->
                    <div
                        class="group relative bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    Registrasi Pasien
                                </p>
                                <p id="totalRegPasien" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                🧾
                            </div>
                        </div>
                    </div>

                    <!-- Rawat Inap -->
                    <div
                        class="group relative bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    Rawat Inap Aktif
                                </p>
                                <p id="totalRanap" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                🏥
                            </div>
                        </div>
                    </div>

                    <!-- Poli -->
                    <div
                        class="group relative bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    Poli Aktif
                                </p>
                                <p id="totalPoli" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                🏬
                            </div>
                        </div>
                    </div>

                    <!-- IGD -->
                    <div
                        class="group relative bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    IGD Aktif
                                </p>
                                <p id="totaligd" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                🚑
                            </div>
                        </div>
                    </div>

                    <!-- Operasi -->
                    <div
                        class="group relative bg-gradient-to-br from-slate-500 to-slate-600 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    Jadwal Operasi
                                </p>
                                <p id="totaloperasi" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                👩‍⚕️👨‍⚕️
                            </div>
                        </div>
                    </div>

                    <!-- Bayi Lahir -->
                    <div
                        class="group relative bg-gradient-to-br from-amber-400 to-amber-500 text-white rounded-2xl p-6 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-wider opacity-80">
                                    Bayi Lahir Hari Ini
                                </p>
                                <p id="bayilahir" class="text-4xl font-bold mt-3">
                                    0
                                </p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center rounded-xl bg-white/20 text-3xl">
                                👶
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div
                class="kpi-card-mainadmin-menu relative overflow-hidden bg-gradient-to-br from-blue-400 via-sky-400 to-blue-500
 rounded-2xl shadow-xl p-6 text-white mb-4">

                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs uppercase tracking-widest opacity-80">
                            Trend Kunjungan Poli
                        </p>
                        <p class="text-sm font-semibold opacity-90">
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/20 text-xl">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>

                <!-- Chart -->
                <div class="chart-wrapper-mainadmin-menu relative">
                    <canvas id="chartKunjunganPoli"></canvas>
                </div>
            </div>


            <div class="bed-card">
                <!-- Header -->
                <div class="bed-card-header">
                    <div>
                        <p class="bed-card-subtitle">Detail Tempat Tidur</p>
                        <p class="bed-card-title">
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                    <div class="bed-card-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                </div>

                <!-- Table -->
                <div class="bed-table-wrapper">
                    <table class="bed-table">
                        <thead>
                            <tr>
                                <th>Bangsal</th>
                                <th class="center">Jumlah Bed</th>
                                <th class="center">Bed Terisi</th>
                                <th class="center">Bed Kosong</th>
                                <th class="center">BOR</th>
                            </tr>
                        </thead>
                        <tbody id="tempat_tidur_per_bangsal">
                            <!-- Diisi via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/mainadmin.js') }}"></script>
@endsection
