@extends('simrs.layouts.app')

@section('content')
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-beetween">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">
                        Monitoring Berkas Digital
                    </h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Monitoring Kelengkapan Berkas Klaim
                    </p>
                </div>
                {{-- <div class="px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl text-sm text-semibold">
                    
                </div> --}}
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-4">
            <div
                class="
                        flex
                        flex-col
                        lg:flex-row
                        gap-3
                    ">

                <!-- Search -->
                <div class="relative flex-1">

                    <div
                        class="
                                absolute
                                inset-y-0
                                left-0
                                pl-4
                                flex
                                items-center
                                pointer-events-none
                            ">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" id="searchCekBerkasDigital"
                        placeholder="Cari No Rawat / No RM / Pasien / Dokter / SEP"
                        class="
                                w-full
                                pl-11
                                pr-4
                                py-3
                                rounded-xl
                                border
                                border-slate-200
                                bg-slate-50
                                focus:bg-white
                                focus:border-blue-500
                                focus:ring-4
                                focus:ring-blue-100
                                outline-none
                                transition-all
                            ">
                </div>

                <!-- Date -->
                <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-3 py-2 shadow-sm hover:shadow-md transition cursor-pointer"
                    onclick="openDateRange()">
                    <input type="date" id="tanggalDari"
                        class="outline-none bg-transparent text-sm w-[130px] cursor-pointer"
                        onchange="loadCekBerkasDigital(1)">

                    <span class="text-slate-400">s/d</span>

                    <input type="date" id="tanggalSampai"
                        class="outline-none bg-transparent text-sm w-[130px] cursor-pointer"
                        onchange="loadCekBerkasDigital(1)">
                </div>

                <!-- Reset -->
                <button type="button"
                    onclick="
                            document.getElementById('searchCekBerkasDigital').value='';
                            document.getElementById('tanggalDari').value='';
                            document.getElementById('tanggalSampai').value='';
                            loadCekBerkasDigital(1);
                        "
                    class="
                            px-5
                            py-3
                            rounded-xl
                            bg-slate-100
                            hover:bg-slate-200
                            text-slate-700
                            font-medium
                            transition-all
                        ">
                    <i class="fas fa-rotate-left mr-2"></i>
                    Reset
                </button>
                <button onclick="exportExcel()"
                    class="
                            inline-flex items-center gap-2
                            px-4 py-2.5
                            rounded-xl
                            bg-gradient-to-r from-emerald-500 to-green-600
                            hover:from-emerald-600 hover:to-green-700
                            text-white font-medium text-sm
                            shadow-md hover:shadow-lg
                            transition-all duration-200
                            active:scale-95
                            focus:outline-none focus:ring-4 focus:ring-emerald-200
                        ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 12l-4-4m4 4l4-4M4 20h16" />
                    </svg>

                    <span>Export Excel</span>
                </button>
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>
                    <tr
                        class="
            bg-slate-50
            dark:bg-slate-800
            text-slate-600
            dark:text-slate-300
            text-sm
            uppercase
            font-bold
        ">

                        <th class="px-4 py-3 text-center font-bold">
                            No Rawat / Tgl Closing
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Pasien / No RM / Dokter
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            No SEP / Status / Poli
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Resume
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Billing
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            CPPT
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            CPPT Dokter
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Asmed
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Triase
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Operasi
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Lab
                        </th>

                        <th class="px-4 py-3 text-center font-bold">
                            Radiologi
                        </th>

                    </tr>
                </thead>

                <tbody id="tableCekBerkasDigital"
                    class="
        text-sm
        text-slate-700
        dark:text-slate-200
    ">
                </tbody>

            </table>

        </div>
        <!-- Pagination -->
        <div
            class="
                        flex
                        flex-col
                        md:flex-row
                        md:items-center
                        md:justify-between
                        gap-4
                        px-6
                        py-4
                        border-t
                        border-slate-200
                        dark:border-slate-800
                        bg-slate-50
                        dark:bg-slate-900/50
                    ">

            <!-- Info -->
            <div
                class="
                    flex
                    items-center
                    gap-3
                        ">

                <div
                    class="
                        w-10
                        h-10
                        rounded-xl
                        bg-blue-100
                        dark:bg-blue-900/30
                        flex
                        items-center
                        justify-center
                    ">
                    📋
                </div>

                <div>

                    <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Data Berkas Digital
                    </div>

                    <div id="infoCekBerkasDigital" class="text-xs text-slate-500 dark:text-slate-400">
                        Memuat data...
                    </div>

                </div>

            </div>

            <!-- Pagination -->
            <div id="paginationCekBerkasDigital"></div>

        </div>
    </div>
@endsection
@section('scripts')
    @if (session('simrs_tipe') === 'petugas')
        @if (session('simrs_dep_id') === '07' ||
                session('simrs_dept') === 'REKAM MEDIK' ||
                session('simrs_dept') === 'IT' ||
                session('simrs_dept') === 'TEKNOLOGI INFORMASI')
            <script src="{{ asset('js/simrs/casemix/cekberkasdigital/monitoringberkasdigital.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endif
    @endif
@endsection
