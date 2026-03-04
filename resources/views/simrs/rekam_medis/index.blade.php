@extends('simrs.layouts.app')

@section('content')
    <div class="p-6 transition-all duration-300" id="mainContent">

        <h1 class="text-2xl font-bold mb-6">Data Pasien Resume</h1>

        {{-- TAB STATUS --}}
        <div class="flex space-x-2 ">
            <button id="tabRalan" onclick="setTab('Ralan')"
                class="px-4 py-2 rounded-t-lg border-b-2 border-blue-600 bg-blue-100 text-blue-700 font-medium">
                Ralan
            </button>
            <button id="tabRanap" onclick="setTab('Ranap')"
                class="px-4 py-2 rounded-t-lg border-b-2 border-transparent bg-gray-100 text-gray-700 font-medium hover:bg-gray-200">
                Ranap
            </button>
        </div>

        {{-- FILTER --}}
        <div class="bg-white shadow rounded-lg p-4 mb-6 grid grid-cols-1 md:grid-cols-6 gap-4 text-black">

            <!-- Filter Tanggal -->
            <div class="md:col-span-2 p-3 border border-gray-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="date" id="tanggal_awal"
                        class="w-full sm:w-1/2 px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="date" id="tanggal_akhir"
                        class="w-full sm:w-1/2 px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <!-- Filter Umur -->
            <div class="md:col-span-1 p-3 border border-gray-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Umur</label>
                <div class="flex gap-2">
                    <select id="umur_operator"
                        class="w-13 px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="<">
                            < </option>
                        <option value="=">=</option>
                        <option value=">">></option>
                    </select>

                    <input type="number" id="umur_tahun" placeholder="Tahun"
                        class="flex-1 px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" />
                </div>
            </div>

            <!-- Filter Jenis Kelamin -->
            <div class="md:col-span-1 p-3 border border-gray-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <select id="jk"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            <!-- Kode/Nama Penyakit -->
            <div class="md:col-span-2 p-3 border border-gray-200 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode/Nama Penyakit</label>
                <input type="text" id="kode_penyakit" placeholder="Kode / Nama ICD"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Diagnosa Akhir -->
            <div id="filterDiagnosaFinal" class="md:col-span-2 p-3 border border-gray-200 rounded-lg hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosa Akhir</label>
                <input type="text" id="diagnosa_final" placeholder="Diagnosa Akhir"
                    class="w-full px-3 py-2 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Button Section -->
            <div class="md:col-span-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">

                <button onclick="loadData(1)"
                    class="flex justify-center items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all duration-200">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2l-7 7v5l-4 2v-7L3 6V4z" />
                    </svg>
                    Filter Data
                </button>

                <button onclick="exportExcel()"
                    class="flex justify-center items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-emerald-700 hover:shadow-md active:scale-95 transition-all duration-200">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                    </svg>
                    Export Excel
                </button>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left whitespace-nowrap">
                    <thead class="bg-gray-100 text-black uppercase text-xs text-center" id="tableHead">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3 whitespace-nowrap">No Rawat</th>
                            <th class="px-4 py-3 whitespace-nowrap">No RM</th>
                            <th class="px-4 py-3 whitespace-nowrap">Nama</th>
                            <th class="px-4 py-3 whitespace-nowrap">Jenis Kelamin</th>
                            <th class="px-4 py-3 whitespace-nowrap">Umur</th>
                            <th class="px-4 py-3 whitespace-nowrap">NIK</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 whitespace-nowrap">Kasus</th>
                            <th class="px-4 py-3 whitespace-nowrap" id="extraHeader">Poli</th>
                            <th class="px-4 py-3 whitespace-nowrap">Kode</th>
                            <th class="px-4 py-3 whitespace-nowrap">Diagnosa</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y">
                        <tr>
                            <td colspan="12" class="text-center py-8 text-gray-400">
                                Silakan gunakan filter terlebih dahulu
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-1 font-medium text-white" id="totalPasien">
            Total pasien: 0
        </div>

        {{-- PAGINATION --}}
        <div id="pagination" class=" flex justify-center"></div>

    </div>
@endsection

@section('scripts')
    @if (session('simrs_tipe') === 'petugas')
        @if (session('simrs_dep_id') === '07' ||
                session('simrs_dept') === 'REKAM MEDIK' ||
                session('simrs_dept') === 'IT' ||
                session('simrs_dept') === 'TEKNOLOGI INFORMASI')
            <script src="{{ asset('js/simrs/rekammedis/datapasienresume.js') }}"></script>
        @endif
    @endif
@endsection
