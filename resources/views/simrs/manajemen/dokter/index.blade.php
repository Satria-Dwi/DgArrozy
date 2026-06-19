@extends('simrs.layouts.app')

@section('content')
        @if (session('simrs_tipe') === 'petugas')
            @if (session('simrs_dep_id') === '06' ||
                    session('simrs_dept') === 'MANAJEMEN' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                <div
                    class="bg-white dark:bg-slate-900
                            border border-slate-200 dark:border-slate-800
                            rounded-lg shadow-sm mb-2">
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            Laporan Pasien Semua Dokter - Hari Ini
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" id="tableDokter">
                            <thead class="bg-slate-300 dark:bg-slate-800">
                                <tr class="text-center text-slate-50">
                                    <th class="px-4 py-3 font-medium">No</th>
                                    <th class="px-4 py-3 font-medium">Nama Dokter</th>
                                    <th class="px-4 py-3 font-medium">Total Pasien</th>
                                    <th class="px-4 py-3 font-medium">Rawat Jalan</th>
                                    <th class="px-4 py-3 font-medium">Rawat Inap</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="px-4 py-2 border-t border-slate-200 dark:border-slate-800
                                text-xs text-slate-500 dark:text-slate-400">
                        Data diperbarui otomatis
                    </div>
                </div>
            @endif
        @endif
    @endsection

    @section('scripts')
        @if (session('simrs_tipe') === 'petugas')
            @if (session('simrs_dep_id') === '06' ||
                    session('simrs_dept') === 'MANAJEMEN' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                <script src="{{ asset('js/simrs/manajemen/dokter/dokter.js') }}"></script>
            @endif
        @endif

        <script>
            let chartPasienDokter = null;
            let filterAktif = false;

            document.addEventListener('DOMContentLoaded', function() {

                @if (session('simrs_tipe') === 'petugas')
                    @if (session('simrs_dep_id') === '06' ||
                            session('simrs_dept') === 'MANAJEMEN' ||
                            session('simrs_dept') === 'IT' ||
                            session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                        if (typeof loadDashboardManajemen === "function") {
                            loadDashboardManajemen();
                        }
                    @endif
                @endif

                // 🔥 Refresh tiap 1 menit
                setInterval(function() {

                    @if (session('simrs_tipe') === 'petugas')
                        @if (session('simrs_dep_id') === '06' ||
                                session('simrs_dept') === 'MANAJEMEN' ||
                                session('simrs_dept') === 'IT' ||
                                session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                            if (typeof loadDashboardManajemen === "function") {
                                loadDashboardManajemen();
                            }
                        @endif
                    @endif

                }, 60000);

            });
        </script>
    @endsection
