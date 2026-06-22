<style>
    /* ===== BRAND ===== */
    .sidebar-brand {
        padding: 1.2rem;
        font-size: 1.2rem;
        font-weight: 800;
        text-align: center;
        letter-spacing: 1px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        color: transparent;
    }

    /* ===== MENU BASE ===== */
    .menu-item {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;

        padding: 12px 16px;
        border-radius: 14px;

        color: #64748b;
        transition: all 0.25s ease;
    }

    .submenu-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 10px;
        transition: 0.25s;
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
    }

    aside.w-20 .menu-item,
    aside.w-20 .submenu-item {
        justify-content: center;
    }

    .menu-item {
        color: #334155;
        /* slate-700 */
    }

    .submenu-item {
        color: #64748b;
        /* slate-500 */
        padding-left: 10px;
    }

    /* Hover soft modern */
    .menu-item:hover {
        background: rgba(99, 102, 241, 0.06);
        color: #4f46e5;
    }

    .submenu-item:hover {
        background: rgba(99, 102, 241, 0.08);
        color: #4f46e5;
    }

    /* =========================
   ACTIVE PARENT (Soft)
    ========================= */
    .menu-parent-active {
        background: linear-gradient(135deg,
                rgba(99, 102, 241, 0.16),
                rgba(99, 102, 241, 0.08));

        color: #4338ca !important;
        font-weight: 600 !important;

        border-left: 4px solid #4f46e5;

        box-shadow:
            0 4px 14px rgba(99, 102, 241, 0.12);

        transform: translateX(2px);
    }

    .menu-parent-active i {
        color: #4f46e5;
    }

    .menu-parent-active:hover {
        background: linear-gradient(135deg,
                rgba(99, 102, 241, 0.22),
                rgba(99, 102, 241, 0.12));
    }

    /* =========================
   SUBMENU BASE (ANTI SHIFT)
    ========================= */
    .submenu-item {
        color: #64748b;
        padding-left: 10px;

        /* Tambahkan ini supaya ukuran tidak berubah */
        border-left: 4px solid transparent;
        box-sizing: border-box;
    }

    /* =========================
   ACTIVE SUBMENU (NO ZOOM)
    ========================= */
    .submenu-active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #ffffff !important;

        border-left: 4px solid #6366f1;
        /* Tidak akan geser lagi */

        box-shadow: none;
        transform: none;

        border-radius: 10px;
        font-weight: 500 !important;
    }

    /* Hover submenu yang aktif */
    .submenu-active:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        /* tetap gradient */
        color: #e0e7ff !important;
        /* putih lembut (light indigo) supaya kontras di gradient tapi tidak terlalu menyilaukan */
    }

    /* ===== FOOTER ===== */
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    /* Logout */
    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 10px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 0.85rem;
        transition: 0.25s;
    }

    .logout-btn:hover {
        background: #fee2e2;
    }

    .copyright {
        margin-top: 12px;
        font-size: 11px;
        text-align: center;
        color: #94a3b8;
    }
</style>

<aside
    :class="[
        open ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        collapse ? 'w-20' : 'w-56'
    ]"
    class="
        fixed inset-y-0 left-0 z-40

        bg-white
        border-r border-slate-200

        flex flex-col shadow-sm

        transform-gpu

        transition-transform
        duration-700
        ease-[cubic-bezier(.22,1,.36,1)]
    ">

    {{-- LOGO + COLLAPSE BUTTON --}}
    <div class="sidebar-brand relative flex items-center justify-between
           px-4 py-4 mb-3">

        <!-- Background Accent -->
        <div
            class="absolute inset-0 rounded-2xl
                bg-gradient-to-r from-indigo-500/5 via-purple-500/5 to-pink-500/5
                pointer-events-none">
        </div>

        <!-- Brand -->
        <div x-show="!collapse" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 -translate-x-2" class="relative flex items-center gap-3">

            <!-- Logo -->
            <div class="flex items-center justify-center w-18 h-18">

                <img src="{{ asset('img/ARION-ICON.png') }}" alt="ARION"
                    class="w-16 h-16 object-contain drop-shadow-md
               group-hover:scale-105 transition-transform duration-300" />

            </div>

            <!-- Brand Text -->
            <div class="flex flex-col leading-tight">

                <span
                    class="text-xl font-black tracking-widest
           bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500
           bg-clip-text text-transparent
           relative
           drop-shadow-[0_2px_6px_rgba(99,102,241,0.35)]
           font-sans">

                    ARION

                    <!-- glow underline accent -->
                    <span
                        class="absolute left-0 -bottom-1 w-full h-[2px]
                 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500
                 opacity-60 blur-[1px]"></span>
                </span>

                <span class="text-[11px] text-slate-400 font-medium mt-2">
                    Integrated Hospital System
                </span>

            </div>
        </div>

        <!-- Collapse Button -->
        {{-- <button @click="collapse = !collapse"
            class="relative group flex items-center justify-center
               w-11 h-11 rounded-xl

               bg-white/80 dark:bg-slate-800/80
               backdrop-blur-xl

               border border-slate-200/60 dark:border-slate-700

               text-slate-500 dark:text-slate-300

               hover:text-indigo-600
               hover:border-indigo-200
               hover:shadow-lg hover:shadow-indigo-500/10

               transition-all duration-300"
            :class="{ 'rotate-180': collapse }" title="Toggle Sidebar">

            <i class="fas fa-bars-staggered transition-transform duration-300"></i>

            <span
                class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100
                   bg-gradient-to-br from-indigo-500/10 to-purple-500/10
                   transition-opacity duration-300">
            </span>

        </button> --}}

        <button @click="collapse = !collapse" title="Toggle Sidebar"
                        class="
                    absolute top-4
                    flex items-center justify-center

                    w-11 h-11 rounded-xl

                    text-slate-500 dark:text-slate-300

                    hover:text-indigo-600
                    hover:-translate-y-0.5
                    hover:scale-105
                    active:scale-90

                    transition-all duration-500 ease-out
                "
                        :class="collapse
                            ?
                            'bg-transparent border-transparent shadow-none backdrop-blur-0' :
                            'bg-white/80 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700 backdrop-blur-xl shadow-sm hover:border-indigo-300 hover:shadow-xl hover:shadow-indigo-500/20'"
                        :style="collapse
                            ?
                            'transform: translateX(0px)' :
                            'transform: translateX(240px)'">

            <!-- Glow -->
            <span
                class="absolute inset-0 rounded-xl

               opacity-0 scale-75

               bg-gradient-to-br
               from-indigo-500/15
               via-purple-500/15
               to-pink-500/15

               group-hover:opacity-100
               group-hover:scale-100

               transition-all duration-500">
            </span>

            <!-- Icon -->
            <!-- ARION ICON (collapse) -->
            <img x-show="collapse" x-transition src="{{ asset('img/ARION-ICON.png') }}"
                class="w-14 h-14 object-contain relative z-10" :class="collapse ? 'mt-4 mb-10' : ''" alt="ARION">

            <!-- Hamburger (expand) -->
            <i x-show="!collapse" x-transition class="fas fa-bars-staggered relative z-10"
                style="transition: transform 700ms cubic-bezier(.34,1.56,.64,1);">
            </i>

        </button>
    </div>

    <!-- Divider -->
    <div
        class="mx-4 mb-1 h-px bg-gradient-to-r
            from-transparent via-slate-200 dark:via-slate-700 to-transparent">
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-3  overflow-y-auto">

        {{-- BERANDA --}}
        @if (session('simrs_tipe') === 'petugas')
            <a href="{{ route('marrozy.dashboard') }}" @click="open=false"
                class="menu-item {{ request()->routeIs('marrozy.dashboard') ? 'menu-parent-active' : '' }}">

                <i class="fa-solid fa-chart-line"></i>
                <span x-show="!collapse" x-transition>Dashboard</span>
            </a>
        @endif

        {{-- MENU PER DOKTER --}}
        @if (session('simrs_tipe') === 'dokter')
            <div x-data="{
                openMenu: {{ request()->routeIs('marrozy.dokter') || request()->routeIs('marrozy.konsultasidokter') || request()->routeIs('marrozy.konsultasimenuperawat') ? 'true' : 'false' }}
            }">

                <button @click="openMenu = !openMenu"
                    class="menu-item w-full justify-between
                    {{ request()->routeIs('marrozy.dokter') || request()->routeIs('marrozy.konsultasidokter') || request()->routeIs('marrozy.konsultasimenuperawat') ? 'menu-parent-active' : '' }}">

                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-md"></i>
                        <span x-show="!collapse" x-transition>Dokter</span>
                    </div>

                    <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                        :class="{ 'rotate-180': openMenu }">
                    </i>

                </button>

                <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                    <a href="{{ route('marrozy.dokter') }}"
                        class="submenu-item
                         {{ request()->routeIs('marrozy.dokter') ? 'submenu-active' : '' }}">

                        <i class="fas fa-users"></i>
                        <span x-show="!collapse" x-transition>Data Pasien</span>

                    </a>

                    <a href="{{ route('marrozy.konsultasidokter') }}"
                        class="submenu-item
                        {{ request()->routeIs('marrozy.konsultasidokter') ? 'submenu-active' : '' }}">

                        <i class="fas fa-user-doctor"></i>
                        <span x-show="!collapse" x-transition>Konsultasi Dokter</span>

                    </a>

                    <a href="{{ route('marrozy.konsultasiperawat') }}"
                        class="submenu-item
                        {{ request()->routeIs('marrozy.konsultasiperawat') ? 'submenu-active' : '' }}">

                        <i class="fas fa-user-nurse"></i>
                        <span x-show="!collapse" x-transition>Konsultasi Perawat</span>

                    </a>

                </div>

            </div>
        @endif

        {{-- MENU MANAJEMEN --}}
        @if (session('simrs_tipe') === 'petugas')

            {{-- MENU DOKTER --}}
            @if (session('simrs_dep_id') === '06' ||
                    session('simrs_dept') === 'MANAJEMEN' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI' ||
                    session('simrs_dep_id') === '07' ||
                    session('simrs_dept') === 'REKAM MEDIK')
                {{-- Dokter --}}
                <div x-data="{ openMenu: {{ request()->routeIs('menudokter.*') ? 'true' : 'false' }} }">
                    <button @click="openMenu = !openMenu"
                        class="menu-item flex items-center w-full  {{ request()->routeIs('menudokter.*') ? 'menu-parent-active' : '' }}">

                        <div class="flex items-center gap-2">
                            <i class="fas fa-stethoscope"></i>
                            <span x-show="!collapse" x-transition>Dokter</span>
                        </div>

                        <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }">
                        </i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                        <a href="{{ route('menudokter.konsultasi.index') }}"
                            class="submenu-item {{ request()->routeIs('menudokter.konsultasi.*') ? 'submenu-active' : '' }}">
                            <i class="fas fa-user-doctor"></i>
                            <span x-show="!collapse" x-transition>Konsultasi Dokter</span>
                        </a>

                        <a href="{{ route('menudokter.konsultasiperawat.index') }}"
                            class="submenu-item {{ request()->routeIs('menudokter.konsultasiperawat.*') ? 'submenu-active' : '' }}">
                            <i class="fas fa-user-nurse"></i>
                            <span x-show="!collapse" x-transition>Konsultasi Perawat</span>
                        </a>

                    </div>
                </div>
            @endif

            {{-- Menu menuperawat --}}
            @if (in_array(session('simrs_dep_id'), ['06', '07']) ||
                    in_array(session('simrs_dept'), ['MANAJEMEN', 'IT', 'TEKNOLOGI INFORMASI', 'REKAM MEDIK']) ||
                    \Illuminate\Support\Str::contains(strtolower(trim(session('simrs_jbtn', ''))), 'perawat'))
                {{-- menuperawat --}}
                <div x-data="{ openMenu: {{ request()->routeIs('menuperawat.*') ? 'true' : 'false' }} }">
                    <button @click="openMenu = !openMenu"
                        class="menu-item flex items-center w-full  {{ request()->routeIs('menuperawat.*') ? 'menu-parent-active' : '' }}">

                        <div class="flex items-center gap-2">
                            <i class="fas fa-heartbeat"></i>
                            <span x-show="!collapse" x-transition>Perawat</span>
                        </div>

                        <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }">
                        </i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                        {{-- <a href="{{ route('menudokter.konsultasi.index') }}"
                            class="submenu-item {{ request()->routeIs('menudokter.konsultasi.*') ? 'submenu-active' : '' }}">
                            <i class="fas fa-user-doctor"></i>
                            <span x-show="!collapse" x-transition>Konsultasi Dokter</span>
                        </a> --}}

                        <a href="{{ route('menuperawat.konsultasiperawat.index') }}"
                            class="submenu-item {{ request()->routeIs('menuperawat.konsultasiperawat.*') ? 'submenu-active' : '' }}">
                            <i class="fas fa-user-nurse"></i>
                            <span x-show="!collapse" x-transition>Konsultasi Perawat</span>
                        </a>

                    </div>
                </div>
            @endif
            {{-- MANAJEMEN --}}
            @if (session('simrs_dep_id') === '06' ||
                    session('simrs_dept') === 'MANAJEMEN' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                <div x-data="{
                    openMenu: {{ request()->routeIs('manajemen.*') ? 'true' : 'false' }}
                }">
                    <button @click="openMenu = !openMenu"
                        class="menu-item w-full justify-between {{ request()->routeIs('manajemen.*') ? 'menu-parent-active' : '' }}">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group"></i>
                            <span x-show="!collapse" x-transition>Manajemen</span>
                        </div>
                        <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }"></i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                        <a href="{{ route('manajemen.index') }}"
                            class="submenu-item {{ request()->routeIs('manajemen.index') ? 'submenu-active' : '' }}">
                            <i class="fas fa-users-cog"></i>
                            <span x-show="!collapse" x-transition>Dokter Hari Ini</span>
                        </a>

                        @if (session('simrs_nik') === '198611172005012002' ||
                                session('simrs_dept_id') === 'IT' ||
                                session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                            <a href="{{ route('manajemen.detailtindakan.index') }}"
                                class="submenu-item {{ request()->routeIs('manajemen.detailtindakan.*') ? 'submenu-active' : '' }}">
                                <i class="fas fa-notes-medical"></i>
                                <span x-show="!collapse" x-transition>Detail Tindakan</span>
                            </a>
                        @endif

                    </div>
                </div>
            @endif
            {{-- REKAM MEDIS --}}
            @if (session('simrs_dep_id') === '07' ||
                    session('simrs_dept') === 'REKAM MEDIK' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI' ||
                    session('simrs_nik') === '3513196706930001')
                {{-- REKAM MEDIS --}}
                <div x-data="{ openMenu: {{ request()->is('rm*') ? 'true' : 'false' }} }">

                    <button @click="openMenu = !openMenu"
                        class="menu-item w-full justify-between {{ request()->is('rm*') ? 'menu-parent-active' : '' }}">

                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-medical"></i>
                            <span x-show="!collapse" x-transition>Rekam Medis</span>
                        </div>

                        <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }">
                        </i>
                    </button>

                    {{-- <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1 ">

                        <a href="{{ url('/rm') }}"
                            class="submenu-item {{ request()->is('rm*') ? 'submenu-active' : '' }}">

                            <i class="fas fa-users"></i>
                            <span x-show="!collapse" x-transition>Data Pasien</span>
                        </a>
                        
                        <a href="{{ url('/rm/rujukankeluar') }}"
                            class="submenu-item {{ request()->is('rm*') ? 'submenu-active' : '' }}">

                            <i class="fas fa-users"></i>
                            <span x-show="!collapse" x-transition>Rujukan Keluar</span>
                        </a>
                    </div> --}}

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                        {{-- Data Pasien --}}
                        <a href="{{ url('/rm') }}"
                            class="submenu-item {{ request()->is('rm') ? 'submenu-active' : '' }}">

                            <i class="fas fa-users"></i>
                            <span x-show="!collapse" x-transition>
                                Data Pasien
                            </span>
                        </a>

                        {{-- Rujukan Keluar --}}
                        <a href="{{ url('/rm/rujukankeluar') }}"
                            class="submenu-item {{ request()->is('rm/rujukankeluar*') ? 'submenu-active' : '' }}">

                            <i class="fas fa-share-square"></i>
                            <span x-show="!collapse" x-transition>
                                Rujukan Keluar
                            </span>
                        </a>

                    </div>
                </div>
            @endif
            {{-- IT MASTER --}}
            @if (session('simrs_dept') === 'IT' || session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                {{-- REKAM MEDIS --}}
                <div x-data="{ openMenu: {{ request()->is('user*') ? 'true' : 'false' }} }">

                    <button @click="openMenu = !openMenu"
                        class="menu-item w-full justify-between {{ request()->is('user*') ? 'menu-parent-active' : '' }}">

                        <div class="flex items-center gap-2">
                            <i class="fas fa-server"></i>
                            <span x-show="!collapse" x-transition>IT Master</span>
                        </div>

                        <i x-show="!collapse" class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }">
                        </i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1 ">

                        <a href="{{ url('/user') }}"
                            class="submenu-item {{ request()->is('user*') ? 'submenu-active' : '' }}">

                            <i class="fas fa-users"></i>
                            <span x-show="!collapse" x-transition>User Accounts</span>
                        </a>

                    </div>
                </div>
            @endif
        @endif

    </nav>

    {{-- FOOTER --}}
    <div class="sidebar-footer">

        <form action="/logout" method="POST">
            @csrf
            <button class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span x-show="!collapse" x-transition>Signout</span>
            </button>
        </form>

        <div class="copyright" x-show="!collapse" x-transition>
            IT Arrozy © 2026
        </div>
    </div>

</aside>
