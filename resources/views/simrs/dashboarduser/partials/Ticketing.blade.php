@extends('simrs.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-200">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                    🎟️ Dashboard Ticketing
                </h1>
                <p class="text-sm text-slate-400">
                    Sistem Pelaporan & Ticketing SIMRS
                </p>
            </div>

            <div class="text-sm text-slate-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- USER INFO --}}
        <div class="rounded-2xl bg-white/5 backdrop-blur border border-white/10 p-5 mb-6">
            <h2 class="text-sm font-semibold mb-4 flex items-center gap-2 text-indigo-400">
                <i class="fa-solid fa-user-shield"></i> Informasi User
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-400">NIK</span>
                    <div class="font-semibold text-white">{{ $user['nik'] }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400">Nama</span>
                    <div class="font-semibold text-white">{{ $user['nama'] }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400">Jabatan</span>
                    <div class="font-semibold text-white">{{ $user['jabatan'] }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400">Role</span>
                    <div class="font-semibold text-white">{{ $role_user }}</div>
                </div>
            </div>
        </div>

        {{-- KPI --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div
                class="rounded-2xl p-4 bg-gradient-to-br from-indigo-600 to-indigo-400 text-white flex items-center gap-4 shadow-lg">
                <div class="text-3xl">🎫</div>
                <div>
                    <p class="text-xs opacity-80">Total Tiket</p>
                    <h3 class="text-2xl font-bold">{{ $total }}</h3>
                </div>
            </div>
            <div
                class="rounded-2xl p-4 bg-gradient-to-br from-yellow-500 to-amber-400 text-white flex items-center gap-4 shadow-lg">
                <div class="text-3xl">🟡</div>
                <div>
                    <p class="text-xs opacity-80">Open</p>
                    <h3 class="text-2xl font-bold">{{ $open }}</h3>
                </div>
            </div>
            <div
                class="rounded-2xl p-4 bg-gradient-to-br from-sky-500 to-cyan-400 text-white flex items-center gap-4 shadow-lg">
                <div class="text-3xl">🔧</div>
                <div>
                    <p class="text-xs opacity-80">Progress</p>
                    <h3 class="text-2xl font-bold">{{ $progress }}</h3>
                </div>
            </div>
            <div
                class="rounded-2xl p-4 bg-gradient-to-br from-emerald-500 to-green-400 text-white flex items-center gap-4 shadow-lg">
                <div class="text-3xl">✅</div>
                <div>
                    <p class="text-xs opacity-80">Closed</p>
                    <h3 class="text-2xl font-bold">{{ $closed }}</h3>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div x-data="{ open: false }" @open-ticket-modal.window="open = true" x-cloak>

                <button @if ($role_user !== 'Tidak Aktif') @click="$dispatch('open-ticket-modal')" @endif
                    class="rounded-2xl p-5 flex items-center gap-3 w-full transition
                {{ $role_user === 'Tidak Aktif'
                    ? 'bg-gray-700 border border-gray-600 text-gray-400 cursor-not-allowed'
                    : 'bg-white/5 border border-white/10 hover:bg-indigo-600 hover:border-indigo-400' }}"
                    {{ $role_user === 'Tidak Aktif' ? 'disabled' : '' }}>
                    <i class="fa-solid fa-circle-plus text-lg"></i>
                    <span class="font-semibold">
                        Buat Tiket
                    </span>
                </button>

                {{-- MODAL --}}
                @if ($role_user !== 'Tidak Aktif')
                    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        @include('simrs.dashboarduser.partials.createticket')
                    </div>
                @endif

            </div>
        </div>


        {{-- TABEL --}}
        @include('simrs.dashboarduser.partials.tableticket')

    </div>
@endsection
