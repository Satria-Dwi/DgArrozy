@extends('layouts.app')
@section('content')
    <!-- Sidebar -->
    <div class="flex min-h-screen">
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold tracking-wide">MArRozy</div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="#" class="block px-4 py-2 rounded-lg bg-blue-600">Dashboard</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-gray-800">Users</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-gray-800">Reports</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:bg-gray-800">Settings</a>
            </nav>
            <div class="p-4 text-sm text-gray-400">© 2026</div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Data Manajemen</h1>
                {{-- <div class="flex items-center gap-4">
                    <span class="text-sm">Admin</span>
                    <img src="https://i.pravatar.cc/40" class="rounded-full" />
                </div> --}}
            </div>
            <div class="kpi-card teal">
                <div class="kpi-header">
                    <span class="kpi-title">Total Pasien Terdaftar</span>
                    <i class="fas fa-users kpi-icon"></i>
                </div>
                <div class="kpi-value" id="totalPasien">-</div>
                <div class="kpi-trend">Rekam Medis Tersedia</div>
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
                </div>
            </div>
        </main>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admin/mainadmin.js') }}"></script>
@endsection
