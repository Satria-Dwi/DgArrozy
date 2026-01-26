@extends('admin.mainlayouts.app')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        @include('admin.layouts.sidebar')

        <main class="flex-1 p-6 main-content">
            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="p-6 bg-gradient-to-r from-indigo-600 to-blue-500 text-white">


                    {{-- FILTER --}}
                    <form method="GET" id="filter-form" class="p-5 bg-gradient-to-r from-indigo-600 to-blue-500">

                        <div class="flex flex-col md:flex-row gap-4 items-center">

                            {{-- Nama --}}
                            <div class="relative w-full md:w-1/3">
                                <span class="absolute inset-y-0 left-3 flex items-center text-indigo-500">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari Nama"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl
                                            bg-white/90 backdrop-blur
                                            border border-white/30
                                            text-gray-800 placeholder-gray-400
                                            focus:ring-2 focus:ring-white
                                            focus:outline-none
                                            shadow-sm">
                            </div>

                            {{-- Jabatan --}}
                            <div class="relative w-full md:w-1/3">
                                <span class="absolute inset-y-0 left-3 flex items-center text-indigo-500">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                                <input type="text" name="jabatan" value="{{ request('jabatan') }}"
                                    placeholder="Cari Jabatan"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl
                                        bg-white/90 backdrop-blur
                                        border border-white/30
                                        text-gray-800 placeholder-gray-400
                                        focus:ring-2 focus:ring-white
                                        focus:outline-none
                                        shadow-sm">
                            </div>

                            {{-- Departemen --}}
                            <div class="relative w-full md:w-1/3">
                                <span class="absolute inset-y-0 left-3 flex items-center text-indigo-500">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" name="departemen" value="{{ request('departemen') }}"
                                    placeholder="Cari Departemen"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl
                                        bg-white/90 backdrop-blur
                                        border border-white/30
                                        text-gray-800 placeholder-gray-400
                                        focus:ring-2 focus:ring-white
                                        focus:outline-none
                                        shadow-sm">
                            </div>

                        </div>
                    </form>
                    <div class="grid">
                        <div>
                            <h2 class="text-3xl font-bold">👨‍⚕️ Data Pegawai</h2>
                            <p class="text-sm text-indigo-100 mt-1">
                                Daftar seluruh pegawai yang terdaftar
                            </p>
                        </div>
                        <div
                            class="bg-white/20 backdrop-blur
                                        px-5 py-3 rounded-xl
                                        text-white shadow-sm">
                            <p class="text-xs uppercase tracking-wider opacity-80">
                                Total Pegawai
                            </p>
                            <p class="text-2xl font-bold" id="total-pegawai">
                                {{ $pegawai->total() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <div class="p-4 bg-gray-50 border-t" id="pagination">
                        @include('admin.officer.partials.pagination')
                    </div>
                    <div id="ajax-content">
                        @include('admin.officer.partials.table')
                    </div>
                </div>
            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                const ajaxContent = document.getElementById('ajax-content');
                const form = document.getElementById('filter-form');
                const totalPegawai = document.getElementById('total-pegawai');
                const pagination = document.getElementById('pagination');

                let timeout = null;

                function fetchData(url) {
                    ajaxContent.innerHTML = `
                        <div class="p-6 text-center text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Memuat data...
                        </div>
                    `;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            ajaxContent.innerHTML = data.table;
                            pagination.innerHTML = data.pagination;
                            totalPegawai.textContent = data.total;
                            bindPagination();
                        });
                }


                // 🔹 AUTO FILTER
                form.querySelectorAll('input').forEach(input => {
                    input.addEventListener('input', () => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            const params = new URLSearchParams(new FormData(form)).toString();
                            fetchData("{{ route('admin.officer.index') }}?" + params);
                        }, 500);
                    });
                });


                // 🔹 PAGINATION AJAX (JANGAN BUANG QUERY STRING)
                function bindPagination() {
                    pagination.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetchData(this.href); // ✅ PAKAI FULL URL
                        });
                    });
                }


                bindPagination();
            });
        </script>
    @endpush
@endsection
