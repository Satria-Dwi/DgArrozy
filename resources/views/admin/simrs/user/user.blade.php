@extends('admin.mainlayouts.app')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        @include('admin.layouts.sidebar')
        <main class="flex-1 p-6 main-content">
            <!-- Header -->
            <div class="p-6 bg-gradient-to-r from-indigo-600 to-blue-500 text-white">


                {{-- FILTER --}}
                <form id="filter-form" class="p-5 bg-gradient-to-r from-indigo-600 to-blue-500">

                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <div>
                            <h2 class="text-3xl font-bold">👨‍⚕️ Account SIMRS</h2>
                            <p class="text-sm text-indigo-100 mt-1 mb-2">
                                Daftar seluruh Account SIMRS terdaftar
                            </p>
                        </div>

                        {{-- Nama --}}
                        <div class="relative w-full md:w-1/3 group">

                            <!-- Icon -->
                            <span
                                class="absolute inset-y-0 left-4 flex items-center
                                            text-indigo-500 z-10
                                            transition-all duration-300
                                            group-focus-within:text-indigo-600
                                            group-focus-within:scale-110">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>

                            <!-- Input -->
                            <input type="text" id="filterNama" name="nama" placeholder="Cari Nama Account..."
                                class="relative z-0 w-full pl-11 pr-4 py-3 rounded-2xl
                                            bg-white/70 backdrop-blur-xl
                                            border border-white/40
                                            text-gray-800 placeholder-gray-400
                                            shadow-md
                                            transition-all duration-300
                                            focus:outline-none
                                            focus:ring-4 focus:ring-indigo-400/30
                                            focus:border-indigo-400" />

                            <!-- Glow -->
                            <div
                                class="absolute inset-0 rounded-2xl
                                        pointer-events-none
                                        opacity-0
                                        group-focus-within:opacity-100
                                        transition duration-300
                                        ring-2 ring-indigo-400/20
                                        z-0">
                            </div>
                        </div>

                        {{-- Jabatan --}}
                        <div class="relative w-full md:w-1/3 group">

                            <!-- Icon -->
                            <span
                                class="absolute inset-y-0 left-4 flex items-center
                                            text-indigo-500 z-10
                                            transition-all duration-300
                                            group-focus-within:text-indigo-600
                                            group-focus-within:scale-110">
                                <i class="fa-solid fa-user text-sm"></i>
                            </span>

                            <!-- Input -->
                            <input type="text" id="filterJabatan" name="jabatan" placeholder="Cari Jabatan..."
                                class="relative z-0 w-full pl-11 pr-4 py-3 rounded-2xl
                                            bg-white/70 backdrop-blur-xl
                                            border border-white/40
                                            text-gray-800 placeholder-gray-400
                                            shadow-md
                                            transition-all duration-300
                                            focus:outline-none
                                            focus:ring-4 focus:ring-indigo-400/30
                                            focus:border-indigo-400" />

                            <!-- Glow -->
                            <div
                                class="absolute inset-0 rounded-2xl
                                        pointer-events-none
                                        opacity-0
                                        group-focus-within:opacity-100
                                        transition duration-300
                                        ring-2 ring-indigo-400/20
                                        z-0">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="grid">
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-5 shadow-lg border border-white/30 mt-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-100">Total Pegawai</p>
                                <h3 class="text-2xl font-bold mt-1">
                                    <span id="total-pegawai">
                                        Total User: <b id="total-user">{{ $totalUser }}</b> |
                                        User Pegawai: <b id="total-user-pegawai">{{ $totalUserPegawai }}</b>
                                    </span>

                                </h3>
                            </div>
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-500/20 text-green-200 text-2xl">
                                👮‍♂️
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ================= USER PEGAWAI ================= --}}
            <div class="bg-white rounded shadow mb-10">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-indigo-600">
                        User SIMRS (Terkait Pegawai)
                    </h2>
                </div>

                <div class="overflow-x-auto" id="table-user-pegawai">
                    @include('admin.simrs.user.table', ['user' => $user])
                </div>
            </div>

            {{-- ================= ADMIN ACCOUNT ================= --}}
            <div class="bg-white rounded shadow">
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-red-600">
                        Admin Sistem
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border">
                        <thead class="bg-red-600 text-white">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">Username</th>
                                <th class="px-3 py-2 border">Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admin as $i => $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 border text-center">
                                        {{ $i + 1 }}
                                    </td>
                                    <td class="px-3 py-2 border font-mono">
                                        {{ $row->username }}
                                    </td>
                                    <td class="px-3 py-2 border font-mono text-red-600">
                                        {{ $row->password }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-gray-500">
                                        Tidak ada data admin
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
@endsection
@push('scripts')
    <script>
        let timer = null;

        document.addEventListener('DOMContentLoaded', () => {

            // ===== Filter input =====
            ['filterNama', 'filterJabatan'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;

                el.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(loadTable, 400);
                });
            });

            // ===== Delegated listener untuk Add/Remove =====
            document.getElementById('table-user-pegawai').addEventListener('click', function(e) {
                const btn = e.target;

                // ===== ADD =====
                if (btn.classList.contains('add-to-userticket-btn')) {
                    const id_user = btn.dataset.id;
                    const nik = btn.dataset.nik;

                    fetch("{{ route('user.addToUserTicket') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id_user,
                                nik
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            showToast(res.message, res.status === 'success' ? 'success' : 'error');
                            if (res.status === 'success') {
                                btn.outerHTML = `
                        <button class="remove-from-userticket-btn text-xs px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                data-id="${id_user}">Remove</button>`;
                            }
                        })
                        .catch(() => showToast('Terjadi kesalahan', 'error'));
                }

                // ===== REMOVE =====
                if (btn.classList.contains('remove-from-userticket-btn')) {
                    const id_user = btn.dataset.id;

                    fetch("{{ route('user.removeFromUserTicket') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id_user
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            showToast(res.message, 'error'); // Remove = merah
                            if (res.status === 'success') {
                                btn.outerHTML = `
                        <button class="add-to-userticket-btn text-xs px-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                                data-id="${id_user}" data-nik="${id_user}">Add to UserTicket</button>`;
                            }
                        })
                        .catch(() => showToast('Terjadi kesalahan', 'error'));
                }
            });

        });

        function loadTable() {
            const nama = document.getElementById('filterNama')?.value || '';
            const jabatan = document.getElementById('filterJabatan')?.value || '';

            fetch(
                    `{{ route('admin.simrs.user-table') }}?nama=${encodeURIComponent(nama)}&jabatan=${encodeURIComponent(jabatan)}`
                    )
                .then(res => res.json())
                .then(res => {

                    // UPDATE TABLE
                    document.getElementById('table-user-pegawai').innerHTML = res.html;

                    // UPDATE TOTAL
                    document.getElementById('total-user').innerText = res.totalUser;
                    document.getElementById('total-user-pegawai').innerText = res.totalUserPegawai;

                    // 🔥 Tidak perlu re-init tombol Add/Remove karena sudah pakai delegated listener
                    if (typeof initRoleSelect === 'function') {
                        initRoleSelect();
                    }
                })
                .catch(err => console.error('Load table error:', err));
        }
    </script>
@endpush
