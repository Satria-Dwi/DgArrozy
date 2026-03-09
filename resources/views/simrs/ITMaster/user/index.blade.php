@extends('simrs.layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-600 shadow-2xl p-8 mb-10">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            <div>
                <h2 class="text-4xl font-bold tracking-tight flex items-center gap-3">
                    <span class="bg-white/20 p-3 rounded-xl">👨‍⚕️</span>
                    Account SIMRS
                </h2>
                <p class="text-indigo-100 mt-2">
                    Daftar seluruh Account SIMRS terdaftar
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl px-6 py-4 shadow-lg">
                <p class="text-sm text-indigo-100">Total Account</p>
                <h3 class="text-2xl font-bold mt-1">
                    <span class="text-white" id="total-user">
                        {{ $totalUser }}
                    </span>
                    <span class="text-indigo-200 text-base font-medium">
                        /
                        <span id="total-user-pegawai">
                            {{ $totalUserPegawai }}
                        </span>
                        Pegawai
                    </span>
                </h3>
            </div>

        </div>

        <!-- FILTER -->
        <div class="grid md:grid-cols-3 gap-4 mt-8">

            <input type="text" id="filterNama" placeholder="Cari Nama..."
                class="w-full px-4 py-3 rounded-2xl bg-white text-gray-800 shadow">

            <input type="text" id="filterJabatan" placeholder="Cari Jabatan..."
                class="w-full px-4 py-3 rounded-2xl bg-white text-gray-800 shadow">

            <input type="text" id="filterDepartemen" placeholder="Cari Departemen..."
                class="w-full px-4 py-3 rounded-2xl bg-white text-gray-800 shadow">

        </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="overflow-x-auto" id="table-user-pegawai">
            @include('simrs.ITMaster.user.table', ['user' => $user])
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let timer = null;

        function loadTable(url = null) {

            const nama = document.getElementById('filterNama').value;
            const jabatan = document.getElementById('filterJabatan').value;
            const departemen = document.getElementById('filterDepartemen').value;

            let fetchUrl = url ??
                `{{ route('simrs.user.table') }}?nama=${encodeURIComponent(nama)}&jabatan=${encodeURIComponent(jabatan)}&departemen=${encodeURIComponent(departemen)}`;

            fetch(fetchUrl)
                .then(res => res.json())
                .then(res => {
                    document.getElementById('table-user-pegawai').innerHTML = res.html;
                    document.getElementById('total-user').innerText = res.totalUser;
                    document.getElementById('total-user-pegawai').innerText = res.totalUserPegawai;
                });
        }

        document.getElementById('filterNama').addEventListener('keyup', () => {
            clearTimeout(timer);
            timer = setTimeout(() => loadTable(), 400);
        });

        document.getElementById('filterJabatan').addEventListener('keyup', () => {
            clearTimeout(timer);
            timer = setTimeout(() => loadTable(), 400);
        });

        document.getElementById('filterDepartemen').addEventListener('keyup', () => {
            clearTimeout(timer);
            timer = setTimeout(() => loadTable(), 400);
        });

        // AJAX pagination
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('.pagination a').getAttribute('href');
                loadTable(url);
            }
        });
    </script>
@endpush
