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
    .menu-item,
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
    .menu-item:hover,
    .submenu-item:hover {
        background: rgba(99, 102, 241, 0.08);
        color: #4f46e5;
    }

    /* =========================
   ACTIVE PARENT (Soft)
    ========================= */
    .menu-parent-active {
        background: rgba(99, 102, 241, 0.08);
        color: #4f46e5 !important;
        font-weight: 500 !important;
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
    class="fixed inset-y-0 left-0 z-40
           bg-white
           border-r border-slate-200
           transform transition-all duration-300 ease-in-out
           flex flex-col shadow-sm">

    {{-- LOGO + COLLAPSE BUTTON --}}
    <div class="sidebar-brand flex items-center justify-between px-4 py-4">

        <!-- Logo / Brand -->
        <div x-show="!collapse" x-transition class="flex items-center gap-2">

            <!-- Icon Logo -->
            <div 
                class="w-9 h-9 rounded-xl 
                    bg-gradient-to-br from-indigo-500 to-purple-600
                    flex items-center justify-center
                    text-white font-bold shadow-sm">
                🏥
            </div>

            <!-- Text Brand -->
            <span 
                class="text-lg font-extrabold tracking-wide 
                   bg-gradient-to-r from-indigo-600 to-purple-600 
                   bg-clip-text text-transparent">
                M-ArRozy
            </span>
        </div>

        <!-- Collapse Button -->
        <button @click="collapse = !collapse"
            class="w-10 h-10 flex items-center justify-center rounded-full
               bg-gradient-to-br from-indigo-100 to-purple-100
               hover:from-indigo-200 hover:to-purple-200
               text-indigo-600 hover:text-indigo-800
               shadow-md hover:shadow-xl
               transition-all duration-300 transform"
            title="Toggle Sidebar">

            <i class="fas fa-bars-staggered"></i>
        </button>
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

        {{-- @if (session('simrs_tipe') === 'dokter')
            <div x-data="{ openMenu: {{ request()->routeIs('marrozy.dokter') ? 'true' : 'false' }} }">

                <button @click="openMenu = !openMenu"
                    class="menu-item w-full justify-between {{ request()->is('dokter*') ? 'menu-parent-active' : '' }}">

                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-md"></i>
                        <span x-show="!collapse" x-transition>Dokter</span>
                    </div>

                    <i x-show="!collapse" class="fas fa-chevron-down transition-transform duration-300"
                        :class="{ 'rotate-180': openMenu }">
                    </i>

                </button>

                <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                    <a href="{{ route('marrozy.dokter') }}"
                        class="submenu-item {{ request()->routeIs('marrozy.dokter') ? 'submenu-active' : '' }}">

                        <i class="fas fa-users"></i>
                        <span x-show="!collapse" x-transition>Pasien</span>

                    </a>

                </div>

                <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                    <a href="{{ route('marrozy.konsultasi') }}"
                        class="submenu-item {{ request()->routeIs('marrozy.dokter') ? 'submenu-active' : '' }}">

                        <i class="fas fa-users"></i>
                        <span x-show="!collapse" x-transition>Konsultasi</span>

                    </a>

                </div>

            </div>
        @endif --}}
        @if (session('simrs_tipe') === 'dokter')
            <div x-data="{
                openMenu: {{ request()->routeIs('marrozy.dokter') || request()->routeIs('marrozy.konsultasidokter') || request()->routeIs('marrozy.konsultasiperawat') ? 'true' : 'false' }}
            }">

                <button @click="openMenu = !openMenu"
                    class="menu-item w-full justify-between
                    {{ request()->routeIs('marrozy.dokter') || request()->routeIs('marrozy.konsultasidokter') || request()->routeIs('marrozy.konsultasiperawat') ? 'menu-parent-active' : '' }}">

                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-md"></i>
                        <span x-show="!collapse" x-transition>Dokter</span>
                    </div>

                    <i x-show="!collapse" class="fas fa-chevron-down transition-transform duration-300"
                        :class="{ 'rotate-180': openMenu }">
                    </i>

                </button>

                <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1">

                    <a href="{{ route('marrozy.dokter') }}"
                        class="submenu-item
                         {{ request()->routeIs('marrozy.dokter') ? 'submenu-active' : '' }}">

                        <i class="fas fa-users"></i>
                        <span x-show="!collapse" x-transition>Pasien</span>

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
        @if (session('simrs_tipe') === 'petugas')
            @if (session('simrs_dep_id') === '06' ||
                    session('simrs_dept') === 'MANAJEMEN' ||
                    session('simrs_dept') === 'IT' ||
                    session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                {{-- MANAJEMEN --}}
                <div x-data="{ openMenu: {{ request()->is('manajemen*') ? 'true' : 'false' }} }">
                    <button @click="openMenu = !openMenu"
                        class="menu-item w-full justify-between {{ request()->is('manajemen*') ? 'menu-parent-active' : '' }}">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group"></i>
                            <span x-show="!collapse" x-transition>Manajemen</span>
                        </div>
                        <i x-show="!collapse" class="fas fa-chevron-down transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }"></i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1 ">
                        <!-- Submenu Dokter -->
                        <a href="{{ route('manajemen.index') }}"
                            class="submenu-item {{ request()->routeIs('manajemen.index') ? 'submenu-active' : '' }}">
                            <i class="fas fa-users-cog"></i>
                            <span x-show="!collapse" x-transition>Dokter</span>
                        </a>

                        @if (session('simrs_nik') === '198611172005012002' ||
                                session('simrs_dept_id') === 'IT' ||
                                session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                            <!-- Submenu Detail Tindakan -->
                            <a href="{{ route('manajemen.detailtindakan.index') }}"
                                class="submenu-item {{ request()->routeIs('manajemen.detailtindakan.index', 'manajemen.detailtindakan') ? 'submenu-active' : '' }}">
                                <i class="fas fa-notes-medical"></i>
                                <span x-show="!collapse" x-transition>Detail Tindakan</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
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

                        <i x-show="!collapse" class="fas fa-chevron-down transition-transform duration-300"
                            :class="{ 'rotate-180': openMenu }">
                        </i>
                    </button>

                    <div x-show="openMenu && !collapse" x-collapse class="ml-4 mt-1 ">

                        <a href="{{ url('/rm') }}"
                            class="submenu-item {{ request()->is('rm*') ? 'submenu-active' : '' }}">

                            <i class="fas fa-users"></i>
                            <span x-show="!collapse" x-transition>Data Pasien</span>
                        </a>

                    </div>
                </div>
            @endif

            @if (session('simrs_dept') === 'IT' || session('simrs_dept') === 'TEKNOLOGI INFORMASI')
                {{-- REKAM MEDIS --}}
                <div x-data="{ openMenu: {{ request()->is('user*') ? 'true' : 'false' }} }">

                    <button @click="openMenu = !openMenu"
                        class="menu-item w-full justify-between {{ request()->is('user*') ? 'menu-parent-active' : '' }}">

                        <div class="flex items-center gap-2">
                            <i class="fas fa-server"></i>
                            <span x-show="!collapse" x-transition>IT Master</span>
                        </div>

                        <i x-show="!collapse" class="fas fa-chevron-down transition-transform duration-300"
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
