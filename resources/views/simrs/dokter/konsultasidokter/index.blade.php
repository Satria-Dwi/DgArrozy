@extends('simrs.layouts.app')

@section('content')
    @role(['admin', 'dokter'])
        {{-- list konsultasi blm di jawab --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">
                            Konsultasi Belum Dijawab
                        </h2>

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Daftar konsultasi yang menunggu respon dokter
                        </p>
                    </div>

                    <div
                        class="
                                px-4 py-2
                                bg-amber-100
                                dark:bg-amber-900/30
                                text-amber-700
                                dark:text-amber-400
                                rounded-xl
                                text-sm
                                font-semibold
                            ">
                        Menunggu Jawaban
                    </div>

                </div>

            </div>
            <div
                class="
                    bg-white
                    rounded-2xl
                    border
                    border-slate-200
                    shadow-sm
                    p-4
                    mb-4
                ">

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

                        <input type="text" id="searchKonsultasi"
                            placeholder="Cari nama pasien, No. RM atau No. Permintaan..."
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
                    <div class="relative min-w-[220px]">

                        <input type="date" id="tanggalKonsultasi"
                            class="
                    w-full
                    py-3
                    px-4
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

                    <!-- Reset -->
                    <button type="button"
                        onclick="
                        document.getElementById('searchKonsultasi').value='';
                        document.getElementById('tanggalKonsultasi').value='';
                        loadKonsultasi(1);
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

                </div>

            </div>
            {{-- <div
                class="
                    flex
                    items-center
                    justify-between
                    mb-4
                ">

                <h2
                    class="
                        text-lg
                        font-bold
                        text-slate-800
                    ">
                    Data Konsultasi
                </h2>

                <div id="infoKonsultasi"
                    class="
                        text-sm
                        text-slate-500
                        bg-slate-50
                        px-4
                        py-2
                        rounded-full
                    ">
                </div>

            </div> --}}

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
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                No Permintaan
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                No RM
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Pasien
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Dokter Konsul
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Jenis Konsultasi
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tableKonsultasi"
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

                        <div
                            class="
                    text-sm
                    font-semibold
                    text-slate-700
                    dark:text-slate-200
                ">
                            Data Konsultasi
                        </div>

                        <div id="infoKonsultasi"
                            class="
                    text-xs
                    text-slate-500
                    dark:text-slate-400
                ">
                            Memuat data...
                        </div>

                    </div>

                </div>

                <!-- Pagination -->
                <div id="paginationKonsultasi"></div>

            </div>

        </div>
        {{-- Popup detil konsultasi --}}
        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="modalKonsultasi" tabindex="-1"
            onclick="tutupModalKonsultasi()">

            <div class="bg-white rounded-lg p-4 lg:p-6 w-[98%] max-w-7xl max-h-[90vh] overflow-y-auto relative"
                onclick="event.stopPropagation()">

                <div class="flex flex-col lg:flex-row gap-6 items-start">
                    <button onclick="tutupModalKonsultasi()"
                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white shadow hover:bg-slate-100 text-slate-600">

                        ✕

                    </button>
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <!-- KIRI : DETAIL -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                            <!-- Header -->
                            <div class="relative bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 p-6 text-white">

                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10">
                                </div>

                                <div class="relative z-10">

                                    <h3 class="text-3xl font-bold tracking-wide">
                                        SURAT KONSULTASI MEDIS
                                    </h3>

                                    <p class="text-blue-100 mt-1 text-center">
                                        Permintaan Konsultasi Antar Dokter
                                    </p>

                                </div>

                            </div>

                            <div class="relative p-8">
                                <div
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">

                                    <div class="text-[120px] font-bold text-slate-100 rotate-[-30deg] opacity-40">

                                        SIMRS

                                    </div>

                                </div>

                                <!-- Nomor & Tanggal -->
                                <div class="flex justify-between border-b pb-4 mb-6">
                                    <div>
                                        <div class="text-sm text-slate-500">
                                            Nomor Permintaan
                                        </div>
                                        <div id="modalNoPermintaan"
                                            class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-sm text-slate-500">
                                            Tanggal
                                        </div>
                                        <div id="modalTanggal" class="font-semibold text-slate-700">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tujuan -->
                                <div class="mb-6">
                                    <p class="text-slate-700">
                                        Kepada Yth.
                                    </p>

                                    <p id="modalDokterTujuan" class="font-bold text-lg text-slate-900">
                                    </p>

                                    <p class="text-slate-600">
                                        Di Tempat
                                    </p>
                                </div>

                                <!-- Pembuka Surat -->
                                <div class="mb-4 text-slate-700 leading-relaxed">
                                    <p>Dengan hormat,</p>

                                    <p class="mt-2">
                                        Mohon untuk memberikan jawaban konsultasi terhadap pasien
                                        berikut :
                                    </p>
                                </div>

                                <!-- Identitas Pasien -->
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 border border-blue-100 rounded-2xl p-5">

                                    <div class="flex items-center gap-3 mb-4">

                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl">
                                            👤
                                        </div>

                                        <div>
                                            <div id="modalPasien" class="font-bold text-lg text-slate-800">
                                            </div>

                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                                RM : <span id="modalNoRM" class="ml-1"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="grid grid-cols-2 gap-4">

                                        <div>
                                            <div class="text-xs text-slate-500">
                                                Jenis Permintaan
                                            </div>

                                            <div id="modalJenis" class="font-medium">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- Diagnosa -->
                                <div class="mb-6 mt-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Diagnosa
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-xl p-5">
                                        <p id="modalDiagnosa" class="text-slate-700 leading-relaxed">
                                        </p>
                                    </div>
                                </div>

                                <!-- Uraian -->
                                <div class="mb-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Uraian Konsultasi
                                    </h4>

                                    <div class="bg-white border-l-4 border-blue-500 rounded-xl p-5 shadow-sm">
                                        <p id="modalUraian" class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>
                                    </div>
                                </div>

                                <!-- Penutup -->
                                <div class="mt-8 text-slate-700">
                                    <p>
                                        Demikian permohonan konsultasi ini disampaikan.
                                        Atas perhatian dan kerja samanya kami ucapkan terima kasih.
                                    </p>
                                </div>
                                <!-- Area Tanda Tangan -->
                                <div class="mt-6 flex justify-end">

                                    <div class="bg-slate-50 border rounded-2xl p-5 w-72 shadow-sm">

                                        <div class="text-sm text-slate-600">
                                            Pengirim
                                        </div>

                                        <div class="text-xs text-slate-500 mb-3">
                                            <span id="modalTanggalTTD"></span>
                                        </div>

                                        <div class="flex justify-center">

                                            <div class="bg-white rounded-xl p-2 shadow border">
                                                <div id="qrTtd"></div>
                                            </div>

                                        </div>

                                        <div class="mt-4">

                                            <div
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs text-center">

                                                ✓ Tanda Tangan Elektronik Valid

                                            </div>

                                        </div>

                                        <div class="mt-4 border-t">

                                            <div id="modalDokter" class="font-bold text-slate-800 text-xs text-center">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- KANAN : TIMELINE -->
                    <div class="w-full lg:w-[380px] shrink-0">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6">

                            <h5 class="text-xl font-bold text-slate-800 mb-6">
                                Riwayat Konsultasi
                            </h5>
                            <div
                                class="mb-6 p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200">

                                <div class="text-sm text-slate-500">
                                    Status Konsultasi
                                </div>

                                <div
                                    class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                                    ⏳ Menunggu Jawaban Dokter

                                </div>

                            </div>
                            <div class="relative border-l-2 border-gray-300 ml-4 pl-6">

                                <!-- STEP 1 -->
                                <div class="relative mb-8">
                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-green-500 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold">
                                        Konsultasi Dikirim
                                    </div>

                                    <div class="text-sm text-gray-600 mt-1" id="modalKeteranganKonsultasi">
                                    </div>

                                    {{-- <div class="mt-2 p-2 bg-gray-50 rounded border">
                                        <div class="text-xs text-gray-500">
                                            Dokter Tujuan
                                        </div>

                                        <div class="font-medium" id="modalDokterTujuan">
                                        </div>
                                    </div> --}}

                                    <div class="text-xs text-gray-400 mt-2" id="modalWaktuKirim">
                                    </div>
                                </div>

                                <!-- STEP 2 -->
                                <div class="relative mb-8">
                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-yellow-500 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold text-yellow-600">
                                        Menunggu Jawaban
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        Menunggu respon dokter tujuan.
                                        <br>
                                        <p class="font-semibold text-red-600">Untuk Menjawab Konsultasi, Bisa Input di SIMRS
                                            KHANZA</p>
                                    </div>
                                </div>

                                <!-- STEP 3 -->
                                <div class="relative">
                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-gray-400 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold text-gray-500">
                                        Konsultasi Selesai
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Menunggu penyelesaian konsultasi.
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- list history konsultasi --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg overflow-hidden mt-6">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">
                            History Konsultasi
                        </h2>

                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Daftar history konsultasi dokter
                        </p>
                    </div>

                    <div
                        class="
                            px-4 py-2
                            bg-green-100
                            dark:bg-green-900/30
                            text-green-700
                            dark:text-green-400
                            rounded-xl
                            text-sm
                            font-semibold
                        ">
                        ✅ Selesai
                    </div>

                </div>

            </div>
            <div
                class="
                    bg-white
                    rounded-2xl
                    border
                    border-slate-200
                    shadow-sm
                    p-4
                    mb-4
                ">

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

                        <input type="text" id="searchKonsultasiSelesai"
                            placeholder="Cari nama pasien, No. RM atau No. Permintaan..."
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
                    <div class="relative min-w-[220px]">

                        <input type="date" id="tanggalKonsultasiSelesai"
                            class="
                    w-full
                    py-3
                    px-4
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

                    <!-- Reset -->
                    <button type="button"
                        onclick="
                        document.getElementById('searchKonsultasiSelesai').value='';
                        document.getElementById('tanggalKonsultasiSelesai').value='';
                        loadKonsultasiSelesai(1);
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
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                No Permintaan
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                No RM
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Pasien
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Dokter Konsul
                            </th>

                            <th class="px-4 py-3 text-left font-bold">
                                Jenis Konsultasi
                            </th>

                            <th class="px-4 py-3 text-center font-bold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tableKonsultasiSelesai"
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

                        <div
                            class="
                    text-sm
                    font-semibold
                    text-slate-700
                    dark:text-slate-200
                ">
                            Data Konsultasi
                        </div>

                        <div id="infoKonsultasiSelesai"
                            class="
                    text-xs
                    text-slate-500
                    dark:text-slate-400
                ">
                            Memuat data...
                        </div>

                    </div>

                </div>

                <!-- Pagination -->
                <div id="paginationKonsultasiSelesai"></div>

            </div>

        </div>

        {{-- Popup detil History konsultasi --}}
        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="modalKonsultasiHistory"
            tabindex="-1" onclick="tutupModalKonsultasiHistory()">

            <div class="bg-white rounded-lg p-4 lg:p-6 w-[98%] max-w-7xl max-h-[90vh] overflow-y-auto relative"
                onclick="event.stopPropagation()">

                <div class="flex flex-col lg:flex-row gap-6">
                    <button onclick="tutupModalKonsultasiHistory()"
                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white shadow hover:bg-slate-100 text-slate-600">

                        ✕

                    </button>
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <!-- KIRI : DETAIL -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                            <!-- Header -->
                            <div class="relative bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 p-6 text-white">

                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10">
                                </div>

                                <div class="relative z-10">

                                    <h3 class="text-3xl font-bold tracking-wide">
                                        SURAT KONSULTASI MEDIS
                                    </h3>

                                    <p class="text-blue-100 mt-1 text-center">
                                        Permintaan Konsultasi Antar Dokter
                                    </p>

                                </div>

                            </div>

                            <div class="relative p-8">
                                <div
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">

                                    <div class="text-[120px] font-bold text-slate-100 rotate-[-30deg] opacity-40">

                                        SIMRS

                                    </div>

                                </div>

                                <!-- Nomor & Tanggal -->
                                <div class="flex justify-between border-b pb-4 mb-6">
                                    <div>
                                        <div class="text-sm text-slate-500">
                                            Nomor Permintaan
                                        </div>
                                        <div id="modalHistoryNoPermintaan"
                                            class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-sm text-slate-500">
                                            Tanggal
                                        </div>
                                        <div id="modalHistoryTanggal" class="font-semibold text-slate-700">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tujuan -->
                                <div class="mb-6">
                                    <p class="text-slate-700">
                                        Kepada Yth.
                                    </p>

                                    <p id="modalHistoryDokterTujuan" class="font-bold text-lg text-slate-900">
                                    </p>

                                    <p class="text-slate-600">
                                        Di Tempat
                                    </p>
                                </div>

                                <!-- Pembuka Surat -->
                                <div class="mb-4 text-slate-700 leading-relaxed">
                                    <p>Dengan hormat,</p>

                                    <p class="mt-2">
                                        Mohon untuk memberikan jawaban konsultasi terhadap pasien
                                        berikut :
                                    </p>
                                </div>

                                <!-- Identitas Pasien -->
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 border border-blue-100 rounded-2xl p-5">

                                    <div class="flex items-center gap-3 mb-4">

                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl">
                                            👤
                                        </div>

                                        <div>
                                            <div id="modalHistoryPasien" class="font-bold text-lg text-slate-800">
                                            </div>

                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                                RM : <span id="modalHistoryNoRM" class="ml-1"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="grid grid-cols-2 gap-4">

                                        <div>
                                            <div class="text-xs text-slate-500">
                                                Jenis Permintaan
                                            </div>

                                            <div id="modalHistoryJenis" class="font-medium">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- Diagnosa -->
                                <div class="mb-6 mt-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Diagnosa
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-xl p-5">
                                        <p id="modalHistoryDiagnosa" class="text-slate-700 leading-relaxed">
                                        </p>
                                    </div>
                                </div>

                                <!-- Uraian -->
                                <div class="mb-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Uraian Konsultasi
                                    </h4>

                                    <div class="bg-white border-l-4 border-blue-500 rounded-xl p-5 shadow-sm">
                                        <p id="modalHistoryUraian" class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>
                                    </div>
                                </div>

                                <!-- Penutup -->
                                <div class="mt-8 text-slate-700">
                                    <p>
                                        Demikian permohonan konsultasi ini disampaikan.
                                        Atas perhatian dan kerja samanya kami ucapkan terima kasih.
                                    </p>
                                </div>
                                <!-- Area Tanda Tangan -->
                                <div class="mt-6 flex justify-end">

                                    <div class="bg-slate-50 border rounded-2xl p-5 w-72 shadow-sm">

                                        <div class="text-sm text-slate-600">
                                            Pengirim
                                        </div>

                                        <div class="text-xs text-slate-500 mb-3">
                                            <span id="modalHistoryTanggalTTD"></span>
                                        </div>

                                        <div class="flex justify-center">

                                            <div class="bg-white rounded-xl p-2 shadow border">
                                                <div id="qrTtdHistory"></div>
                                            </div>

                                        </div>

                                        <div class="mt-4">

                                            <div
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs text-center">

                                                ✓ Tanda Tangan Elektronik Valid

                                            </div>

                                        </div>

                                        <div class="mt-4 border-t">

                                            <div id="modalHistoryDokter" class="font-bold text-slate-800 text-xs text-center">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- KANAN : TIMELINE -->
                    <div class="w-full lg:w-[380px] shrink-0">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6">

                            <h5 class="text-xl font-bold text-slate-800 mb-6">
                                Riwayat Konsultasi
                            </h5>

                            <!-- STATUS -->
                            <div
                                class="mb-6 p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200">

                                <div class="text-sm text-slate-500">
                                    Status Konsultasi
                                </div>

                                <div
                                    class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">

                                    ✅ Konsultasi Selesai

                                </div>

                            </div>

                            <div class="relative border-l-2 border-emerald-300 ml-4 pl-6">

                                <!-- STEP 1 -->
                                <div class="relative mb-8">

                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold text-emerald-700">
                                        Konsultasi Dikirim
                                    </div>

                                    <div class="text-sm text-gray-600 mt-1" id="modalHistoryKeteranganKonsultasi">
                                    </div>

                                    <div class="text-xs text-gray-400 mt-2" id="modalHistoryWaktuKirim">
                                    </div>

                                </div>

                                <!-- STEP 2 -->
                                <div class="relative mb-8">

                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold text-emerald-700">
                                        Konsultasi Dijawab
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        Dokter tujuan telah memberikan jawaban konsultasi.
                                    </div>

                                    <div class="text-xs text-gray-400 mt-2" id="modalHistoryWaktuJawab">
                                    </div>

                                </div>

                                <!-- STEP 3 -->
                                <div class="relative">

                                    <span
                                        class="absolute -left-[33px] top-1 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white">
                                    </span>

                                    <div class="font-semibold text-emerald-700">
                                        Konsultasi Selesai
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        Proses konsultasi telah selesai dan tersimpan dalam sistem.
                                    </div>
                                    {{-- <button class="btn-lihat-ulang px-3 py-1 bg-blue-600 text-white rounded-lg text-xs"
                                        data-permintaan="">
                                        👁️ Lihat Detail Jawaban
                                    </button> --}}
                                    <div id="modalHistoryActionButton" class="mt-4"></div>
                                    <div class="text-xs text-gray-400 mt-2" id="modalHistoryWaktuSelesai">
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Popup detil hasil History konsultasi --}}
        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" id="modalKonsultasiHistoryJawaban"
            tabindex="-1" onclick="tutupModalJawabanKonsultasiHistory()">

            <div class="bg-white rounded-lg p-4 lg:p-6 w-[80%] max-w-3xl max-h-[90vh] overflow-y-auto relative"
                onclick="event.stopPropagation()">

                <div class="flex flex-col lg:flex-row gap-6 items-start">
                    <button onclick="tutupModalJawabanKonsultasiHistory()"
                        class="absolute top-4 right-4 z-[9999] w-10 h-10 rounded-full bg-white border border-slate-200 shadow-lg hover:bg-slate-100 text-slate-600 flex items-center justify-center">

                        ✕

                    </button>
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <!-- KIRI : DETAIL -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                            <!-- Header -->
                            <div class="relative bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 p-6 text-white">

                                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10">
                                </div>

                                <div class="relative z-10">

                                    <h3 class="text-3xl font-bold tracking-wide">
                                        SURAT HASIL KONSULTASI MEDIS
                                    </h3>

                                    <p class="text-blue-100 mt-1 text-center">
                                        Hasil Konsultasi Antar Dokter
                                    </p>

                                </div>

                            </div>
                            <div class="p-4 border-b bg-green-50">

                                <div
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">

                                    ✅ Konsultasi Selesai

                                </div>

                            </div>
                            <div class="relative p-8">
                                <div
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none overflow-hidden">

                                    <div class="text-[120px] font-bold text-slate-100 rotate-[-30deg] opacity-40">

                                        SIMRS

                                    </div>

                                </div>

                                <!-- Nomor & Tanggal -->
                                <div class="flex justify-between border-b pb-4 mb-6">
                                    <div>
                                        <div class="text-sm text-slate-500">
                                            Nomor Permintaan
                                        </div>
                                        <div id="modalHistoryJawabanNoPermintaan"
                                            class="inline-flex px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold">
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-sm text-slate-500">
                                            Tanggal
                                        </div>
                                        <div id="modalHistoryJawabanTindakLanjut" class="font-semibold text-slate-700">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tujuan -->
                                <div class="mb-6">
                                    <p class="text-slate-700">
                                        Kepada Yth.
                                    </p>

                                    <p id="modalHistoryJawabanDokterPengirim" class="font-bold text-lg text-slate-900">
                                    </p>

                                    <p class="text-slate-600">
                                        Di Tempat
                                    </p>
                                </div>

                                <!-- Pembuka Surat -->
                                <div class="mb-4 text-slate-700 leading-relaxed">

                                    <p>
                                        Berikut merupakan hasil konsultasi medis yang telah diberikan
                                        oleh dokter konsulen terhadap pasien berikut :
                                    </p>

                                </div>

                                <!-- Identitas Pasien -->
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 border border-blue-100 rounded-2xl p-5">

                                    <div class="flex items-center gap-3 mb-4">

                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl">
                                            👤
                                        </div>

                                        <div>
                                            <div id="modalHistoryJawabanPasien" class="font-bold text-lg text-slate-800">
                                            </div>

                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                                RM : <span id="modalHistoryJawabanNoRM" class="ml-1"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="grid grid-cols-2 gap-4">

                                        <div>
                                            <div class="text-xs text-slate-500">
                                                Jenis Permintaan
                                            </div>

                                            <div id="modalHistoryJawabanJenis" class="font-medium">
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- Diagnosa -->
                                <div class="mb-6 mt-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Diagnosa
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-xl p-5">
                                        <p id="modalHistoryJawabanDiagnosa" class="text-slate-700 leading-relaxed">
                                        </p>
                                    </div>
                                </div>

                                <!-- Uraian -->
                                <div class="mb-6">
                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Uraian Konsultasi
                                    </h4>

                                    <div class="bg-white border-l-4 border-blue-500 rounded-xl p-5 shadow-sm">
                                        <p id="modalHistoryJawabanUraian"
                                            class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>
                                    </div>
                                </div>

                                <!-- Diagnosa -->
                                <div class="mb-6">

                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Diagnosa Kerja
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-xl p-5">

                                        <p id="modalHistoryJawabanSaran"
                                            class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>

                                    </div>

                                </div>

                                <!-- Jawaban Konsultasi -->
                                <div class="mb-6">

                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Jawaban Konsultasi
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-xl p-5">

                                        <p id="modalHistoryJawabanJawaban"
                                            class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>

                                    </div>

                                </div>



                                <!-- Tindak Lanjut -->
                                {{-- <div class="mb-6">

                                    <h4 class="font-bold text-slate-800 border-b pb-2 mb-3">
                                        Tindak Lanjut
                                    </h4>

                                    <div
                                        class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 rounded-xl p-5">

                                        <p id="modalHistoryJawabanJawaban"
                                            class="text-slate-700 leading-relaxed whitespace-pre-line">
                                        </p>

                                    </div>

                                </div> --}}

                                <!-- Penutup -->
                                <div class="mt-8 text-slate-700">

                                    <p>
                                        Demikian hasil konsultasi medis ini dibuat dan telah
                                        ditandatangani secara elektronik melalui sistem informasi rumah sakit.
                                    </p>

                                </div>
                                <!-- Area Tanda Tangan -->
                                <div class="mt-6 flex justify-end">

                                    <div class="bg-slate-50 border rounded-2xl p-5 w-72 shadow-sm">

                                        <div class="text-sm text-slate-600">
                                            Pengirim
                                        </div>

                                        <div class="text-xs text-slate-500 mb-3">
                                            <span id="modalHistoryJawabanTanggalTTD"></span>
                                        </div>

                                        <div class="flex justify-center">

                                            <div class="bg-white rounded-xl p-2 shadow border">
                                                <div id="qrTtdHistoryJawaban"></div>
                                            </div>

                                        </div>

                                        <div class="mt-4">

                                            <div
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs text-center">

                                                ✓ Tanda Tangan Elektronik Valid

                                            </div>

                                        </div>

                                        <div class="mt-4 border-t">

                                            <div id="modalHistoryJawabanDokterTujuan"
                                                class="font-bold text-slate-800 text-xs text-center">
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endrole
@endsection
@section('scripts')
    <script src="{{ asset('js/simrs/dokter/konsultasi.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection
