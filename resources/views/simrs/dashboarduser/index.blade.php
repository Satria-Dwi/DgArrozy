@extends('simrs.dashboarduser.layouts.app')

@section('content')
    <div class="rounded-2xl bg-white 
           border border-slate-200
           shadow-sm
           p-5 sm:p-6 mb-4">

        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-xl
                       bg-indigo-100 text-indigo-600
                       flex items-center justify-center">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Informasi User
                    </h2>
                    <p class="text-xs text-slate-500">
                        Data akun yang sedang login
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">

            <!-- NIK -->
            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                <span class="text-xs text-slate-500">NIK</span>
                <div class="mt-1 font-semibold text-slate-800 truncate">
                    {{ $user['nik'] ?? '-' }}
                </div>
            </div>

            <!-- Nama -->
            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                <span class="text-xs text-slate-500">Nama</span>
                <div class="mt-1 font-semibold text-slate-800 truncate">
                    {{ $user['nama'] ?? '-' }}
                </div>
            </div>

            <!-- Jabatan -->
            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                <span class="text-xs text-slate-500">Jabatan</span>
                <div class="mt-1 font-semibold text-slate-800 truncate">
                    {{ $user['jabatan'] ?? '-' }}
                </div>
            </div>

            <!-- Departemen -->
            <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                <span class="text-xs text-slate-500">Departemen</span>
                <div class="mt-1 font-semibold text-slate-800 truncate">
                    {{ $user['departemen'] ?? '-' }}
                </div>
            </div>

            @if (!empty($user['spesialis']))
                <!-- Spesialis -->
                <div
                    class="rounded-xl bg-emerald-50 p-4
                       border border-emerald-200
                       sm:col-span-2 lg:col-span-1">
                    <span class="text-xs text-emerald-600">Spesialis</span>
                    <div class="mt-1 font-semibold text-emerald-700 flex items-center gap-2">
                        <span>🩺</span>
                        <span class="truncate">{{ $user['spesialis'] }}</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="px-4">
        <div class="py-2">
            <div class="kpi-container">
                {{-- <div class="kpi-card green">
                        <div class="kpi-header">
                            <div id="rt-hari" class="rt-hari"></div>
                            <i class="fas fa-stopwatch fa-2x" style="color:#10b981;"></i>
                        </div>
                        <div class="clock">
                            <div id="rt-tanggal" class="rt-tanggal"></div>
                            <div id="rt-jam" class="rt-jam"></div>
                        </div>
                    </div> --}}
                <div class="kpi-card orange">
                    <div class="kpi-header">
                        <span class="kpi-title">Rawat Inap Saat Ini</span>
                        <i class="fas fa-bed kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="ranap">-</div>
                    <div class="kpi-trend">Pasien sedang dirawat</div>
                </div>
                <div class="kpi-card blue">
                    <div class="kpi-header">
                        <span class="kpi-title">IGD Hari Ini</span>
                        <i class="fas fa-ambulance kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="igd">-</div>
                    <div class="kpi-trend">Total Kunjungan IGD</div>
                </div>
                <div class="kpi-card red">
                    <div class="kpi-header">
                        <span class="kpi-title">Poliklinik Hari ini</span>
                        <i class="fas fa-clinic-medical kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="poli">-</div>
                    <div class="kpi-trend">Total Kunjungan Poli</div>
                </div>
                <div class="kpi-card green">
                    <div class="kpi-header">
                        <span class="kpi-title">Operasi Hari Ini</span>
                        <i class="fas fa-procedures kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="operasi">-</div>
                    <div class="kpi-trend">Operasi Terjadwal</div>
                </div>
                <div class="kpi-card purple">
                    <div class="kpi-header">
                        <span class="kpi-title">Bayi Lahir Hari ini</span>
                        <i class="fas fa-baby kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="lahir">-</div>
                    <div class="kpi-trend">Kelahiran Hari Ini</div>
                </div>
                <div class="kpi-card teal">
                    <div class="kpi-header">
                        <span class="kpi-title">Total Pasien Terdaftar</span>
                        <i class="fas fa-users kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="pasien">-</div>
                    <div class="kpi-trend">Rekam Medis Tersedia</div>
                </div>
            </div>
        </div>

        <div class="kpi-head">
            <div class="kpi-container">
                {{-- <div class="kpi-card-line crypto">
                    <div class="kpi-header-line">
                        <span class="kpi-title-line">Trend 10 Penyakit Bulan Ini</span>
                        <i class="fas fa-chart-line kpi-icon-line"></i>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="chartPenyakitBulanIni"></canvas>
                    </div>
                </div> --}}
                <div data-video-harian
                    class="
                            relative
                            overflow-hidden
                            rounded-2xl
                            shadow-xl
                            border border-white/10
                            bg-gradient-to-br from-teal-900 via-slate-900 to-slate-950
                            h-100
                            group
                        ">
                    <!-- VIDEO -->
                    <video
                        class="
                                kpi-video
                                w-full
                                h-full
                                object-cover
                                scale-105
                                transition-transform
                                duration-700
                                group-hover:scale-110
                                brightness-95
                            "
                        autoplay muted loop playsinline></video>

                    <!-- OVERLAY GRADIENT -->
                    <div
                        class="
                                absolute
                                inset-0
                                bg-gradient-to-t
                                from-black/70
                                via-black/20
                                to-transparent
                            ">
                    </div>

                    <!-- BADGE -->
                    <div
                        class="
                                absolute
                                top-3
                                left-3
                                px-3
                                py-1
                                rounded-full
                                text-[0.7rem]
                                font-semibold
                                tracking-widest
                                uppercase
                                bg-teal-500/20
                                text-teal-300
                                border border-teal-400/30
                                backdrop-blur
                            ">
                        Today
                    </div>

                    <!-- TITLE -->
                    <div
                        class="
                                absolute
                                bottom-4
                                left-4
                                right-4
                                flex
                                items-center
                                justify-between
                            ">
                        {{-- <div>
                            <p class="text-white text-sm font-semibold leading-tight">
                                Video Edukasi Harian
                            </p>
                            <p class="text-slate-300 text-xs tracking-wide">
                                Auto Schedule • Dashboard
                            </p>
                        </div> --}}

                        <!-- INDICATOR -->
                        <span
                            class="
                            w-2.5
                            h-2.5
                            rounded-full
                            bg-emerald-400
                            animate-pulse
                            shadow-[0_0_12px_rgba(52,211,153,0.4)]
                        "></span>
                    </div>
                </div>
                <div id="chartkunjungan" data-url="/dashboard/pasien-harian" data-interval="60000"
                    class="
                            relative
                            flex
                            items-center
                            justify-center

                            bg-gradient-to-br from-slate-50 to-white
                            dark:from-slate-900 dark:to-slate-950
                            border border-slate-200 dark:border-white/10
                            rounded-xl

                            px-3 pt-2 pb-3
                            min-h-[360px]
                        ">
                </div>
            </div>
        </div>
        <div class="kpi-head">
            <div class="kpi-container">
                <div id="chartJK"
                    class="
                            relative
                            flex
                            items-center
                            justify-center

                            bg-gradient-to-br from-slate-50 to-white
                            dark:from-slate-900 dark:to-slate-950
                            border border-slate-200 dark:border-white/10
                            rounded-xl

                            px-3 pt-2 pb-3
                            min-h-[360px]
                        ">
                </div>
                <div id="chartKetersediaanKamar"
                    class="
                            relative
                            flex
                            items-center
                            justify-center

                            bg-gradient-to-br from-slate-50 to-white
                            dark:from-slate-900 dark:to-slate-950
                            border border-slate-200 dark:border-white/10
                            rounded-xl

                            px-3 pt-2 pb-3
                            min-h-[360px]
                        ">
                </div>
                <div id="chartPenjamin"
                    class="
                            relative
                            flex
                            items-center
                            justify-center

                            bg-gradient-to-br from-slate-50 to-white
                            dark:from-slate-900 dark:to-slate-950
                            border border-slate-200 dark:border-white/10
                            rounded-xl

                            px-3 pt-2 pb-3
                            min-h-[360px]
                        ">
                </div>
            </div>
        </div>
        <div class="kpi-head">
            <div class="kpi-container grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- ================= LEFT : TABLE ================= -->
                <div class="lg:col-span-2">
                    <div class="kpi-card green h-full">
                        <div class="overflow-x-auto h-full flex flex-col">

                            <div class="kpi-header mb-4">
                                <span class="kpi-title-table">
                                    Detail Tempat Tidur per Hari Ini
                                    ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})
                                </span>
                                <i class="fas fa-table kpi-icon"></i>
                            </div>

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
                                <tbody id="tableTempatTidur" class="divide-y divide-gray-100">
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <!-- ================= RIGHT : KPI ================= -->
                <div class="flex flex-col gap-6 h-full">

                    <!-- IKM -->
                    <div
                        class="flex-1 w-full p-6 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg">

                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium opacity-90">Nilai IKM Tahun 2025</h3>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">Baik</span>
                        </div>

                        <div class="mt-6">
                            <!-- NILAI TETAP KIRI -->
                            <p class="text-4xl font-bold">
                                87,00
                            </p>

                            <!-- Progress tetap rapi -->
                            <div class="mt-4 w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full w-[92%]"></div>
                            </div>
                        </div>

                    </div>

                    <!-- GOOGLE REVIEW -->
                    <div class="flex-1 w-full p-6 rounded-xl bg-white border border-slate-200 shadow-sm">

                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium text-slate-600">
                                Google Review
                            </h3>
                            <span class="text-yellow-500 text-xl">⭐</span>
                        </div>

                        <div class="mt-6">
                            <!-- NILAI -->
                            <p class="text-4xl font-bold text-slate-800">
                                4.9
                            </p>

                            <!-- BINTANG VISUAL -->
                            <div class="flex items-center mt-2 space-x-1 text-yellow-400 text-lg">
                                ★ ★ ★ ★ ★
                            </div>

                            <!-- PROGRESS BAR RATING -->
                            <div class="mt-4 w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full w-[98%]"></div>
                            </div>

                            <p class="text-xs text-slate-500 mt-2">
                                Berdasarkan 1.245 ulasan
                            </p>
                        </div>

                    </div>

                    <!-- SPM -->
                    <div
                        class="flex-1 w-full p-6 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg">

                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-medium opacity-90">
                                Capaian SPM Tahun 2025
                            </h3>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                                On Track
                            </span>
                        </div>

                        <div class="mt-6">
                            <!-- NILAI TETAP KIRI -->
                            <p class="text-4xl font-bold">
                                81,61
                            </p>

                            <div class="mt-4 w-full bg-white/20 rounded-full h-2">
                                <div class="bg-white h-2 rounded-full w-[85%]"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="min-h-screen bg-gradient-to-br from-white via-blue-50 to-indigo-50 text-slate-800"> --}}
    {{-- USER INFO --}}

    {{-- </div> --}}
@endsection
@section('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/simrs/vidplayschedule.js') }}"></script>
@endsection
