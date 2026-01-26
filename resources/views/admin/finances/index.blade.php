@extends('admin.mainlayouts.app') {{-- sesuaikan dengan layout kamu --}}

@section('content')
    <div class="flex min-h-screen">
        @include('admin.layouts.sidebar')

        {{-- Alert success --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        <main class="flex-1 p-6 main-content">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-indigo-600 to-blue-500">


                    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg p-6 mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">
                            💹 Tambah Perencanaan Keuangan
                        </h2>

                        <form id="finance-form" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <input type="text" name="nama_perencanaan" placeholder="Nama Perencanaan"
                                    class="input-modern" required>

                                <input type="text" name="modal_awal" placeholder="Modal Awal (Rp)" class="input-modern"
                                    required>

                                <input type="text" name="total_pendapatan" placeholder="Total Pendapatan (Rp)"
                                    class="input-modern" required>

                                <input type="text" name="total_pengeluaran" placeholder="Total Pengeluaran (Rp)"
                                    class="input-modern" required>

                                <input type="text" name="total_perencanaan" placeholder="Total Perencanaan (Rp)"
                                    class="input-modern" required>

                                <input type="text" name="deskripsi" placeholder="Deskripsi" class="input-modern">
                            </div>

                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl shadow transition">
                                💾 Simpan Data
                            </button>
                        </form>
                    </div>


                    <div class="grid text-white">
                        <div>
                            <h2 class="text-3xl font-bold">💸 Data Finance</h2>
                            <p class="text-sm text-indigo-100 mt-1">
                                Data Finance Manajemen
                            </p>
                        </div>
                    </div>
                    <!-- Card Total Pendapatan -->
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-5 shadow-lg border border-white/30 mt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-100">Total Pendapatan</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    Rp <span id="total-pendapatan">
                                        {{ number_format($totalPendapatan, 2, ',', '.') }}
                                    </span>
                                </h3>
                            </div>
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-500/20 text-green-200 text-2xl">
                                💰
                            </div>
                        </div>
                    </div>

                </div>
                {{-- Table --}}
                <div class="overflow-x-auto bg-white shadow rounded">
                    <table class="min-w-full border border-gray-200" id="finance-table">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama Perencanaan</th>
                                <th class="px-4 py-2 border">Modal Awal</th>
                                <th class="px-4 py-2 border">Pendapatan</th>
                                <th class="px-4 py-2 border">Pengeluaran</th>
                                <th class="px-4 py-2 border">Modal Akhir</th>
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="finance-table-body" class="divide-y divide-gray-100">
                            @include('admin.finances.partials.table')
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            document.getElementById('finance-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                fetch("{{ route('finances.store') }}", {
                        method: "POST",
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('finance-table-body').innerHTML = data.table;
                        document.getElementById('total-pendapatan').innerText = data.total_pendapatan;
                        this.reset();
                    })
                    .catch(err => console.error(err));
            });

            function deleteFinance(id) {
                if (!confirm('Yakin hapus data ini?')) return;

                fetch(`/finances/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('finance-table-body').innerHTML = data.table;
                        document.getElementById('total-pendapatan').innerText = data.total_pendapatan;
                    })
                    .catch(err => console.error(err));
            }
        </script>
    @endpush
@endsection
