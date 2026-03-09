@php
    $active = $active ?? '';
    $roleCode = session('account_role_code') ?? '';
@endphp

<header x-data="{ mobileMenu: false, minimized: false }" x-bind:class="minimized ? 'header-hidden' : ''"
    class="header-wrapper bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 
           text-white shadow sticky top-0 z-50">

    <!-- BAR ATAS -->
    <div class="max-w-7xl mx-auto px-6 py-0.5 flex items-center justify-between">

        <!-- KIRI -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('img/logo-pin-edit.png') }}?v={{ filemtime(public_path('img/logo-pin-edit.png')) }}"
                class="header-icon">
            <span class="text-xl font-bold tracking-wide">DgArrozy</span>
            <span>|</span>
        </div>

        <!-- TOGGLE MOBILE -->
        <button @click="mobileMenu = !mobileMenu" class="md:hidden text-2xl text-white">
            <i class="fa-solid fa-bars"></i>
        </button>

        {{-- <!-- BUTTON MINIMIZE -->
        <button @click="minimized = !minimized" class="hidden md:block text-white text-xl ml-4">
            <i :class="minimized ? 'fa-solid fa-expand' : 'fa-solid fa-minus'"></i>
        </button> --}}

        <!-- MENU DESKTOP -->
        <div class="menu hidden md:block">
            <ul class="flex gap-6 font-medium">
                <li><a href="/" class="{{ $active === 'home' ? 'active' : '' }}">Home</a></li>
                <li><a href="/dashboard" class="{{ $active === 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                {{-- <li><a href="/stream" class="{{ $active === 'stream' ? 'active' : '' }}">Stream</a></li>
                <li><a href="/login">SIMRS-IT</a></li> --}}

                @if (session('dgarrozy_login') && in_array($roleCode, ['admin', 'manajemen']))
                    <li><a href="/mainadmin" class="{{ $active === 'mainadmin' ? 'active' : '' }}">Manajemen</a></li>
                @endif
            </ul>
        </div>

        <!-- RIGHT ITEM -->
        {{-- <div class="right-item hidden sm:block" x-data="{ open: false }">
            @if (session()->has('dgarrozy_login'))
                <div class="relative">
                    <button @click="open = !open" class="flex items-center gap-1">
                        Welcome Back, {{ session('account_email') }}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-cloak @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 mt-2 w-48 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded shadow-lg">
                        <li>
                            <a href="{{ session('account_role') === 'user' ? '/profile' : '/mainadmin' }}"
                                class="block px-4 py-3 hover:bg-blue-500/10">
                                {{ session('account_role') === 'user' ? 'Profil Saya' : 'MArrozy' }}
                            </a>
                        </li>
                        <li>
                            <hr>
                        </li>
                        <li>
                            <form action="/signout" method="POST">
                                @csrf
                                <button class="w-full text-left px-4 py-3 hover:bg-red-500/10">Signout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="/signin" class="flex items-center gap-1">
                    Login <i class="fa-solid fa-right-to-bracket"></i>
                </a>
            @endif
        </div> --}}

        <!-- RIGHT ITEM -->
        <div class="right-item hidden sm:block" x-data="{ open: false }">
            @if (session('simrs_login'))
                <div class="relative">
                    <button @click="open = !open" class="flex items-center gap-1">
                        Welcome Back, {{ session('simrs_nama') }}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-cloak @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 mt-2 w-56
                       bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500
                       rounded shadow-lg">

                        {{-- PROFIL / DASHBOARD --}}
                        <li>
                            <a href="/marrozy" class="block px-4 py-3 hover:bg-white/10">
                                SIMRS
                            </a>
                        </li>

                        {{-- INFO ROLE --}}
                        {{-- <li class="px-4 py-2 text-sm text-white/80">
                            Role: {{ ucfirst(session('simrs_tipe')) }}
                            @if (session('simrs_sps'))
                                <br>Spesialis: {{ session('simrs_sps') }}
                            @endif
                        </li> --}}

                        <li>
                            <hr class="border-white/20">
                        </li>

                        {{-- LOGOUT --}}
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="w-full text-left px-4 py-3 hover:bg-red-500/20">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="/login" class="flex items-center gap-1">
                    Login <i class="fa-solid fa-right-to-bracket"></i>
                </a>
            @endif
        </div>

    </div>

    <!-- MENU MOBILE -->
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden px-6 pb-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500">

        <ul class="flex flex-col gap-4 font-medium">
            <li><a href="/">Home</a></li>
            <li><a href="/dashboard">Dashboard</a></li>
            {{-- <li><a href="/stream">Stream</a></li>
            <li><a href="/login">SIMRS-IT</a></li> --}}

            @if (session('dgarrozy_login') && in_array($roleCode, ['admin', 'manajemen']))
                <li><a href="/mainadmin">Manajemen</a></li>
            @endif

            <!-- USER MOBILE -->
            <li class="border-t border-white/30 pt-4 mt-4">
                @if (session('simrs_login'))
                    <div class="relative">
                        <button @click="open = !open" class="flex items-center gap-1">
                            Welcome Back, {{ session('simrs_nama') }}
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <ul x-show="open" x-cloak @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-2 w-56
                       bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500
                       rounded shadow-lg">

                            {{-- PROFIL / DASHBOARD --}}
                            <li>
                                <a href="{{ session('simrs_tipe') === 'admin' ? '/marrozy' : '/dashboard' }}"
                                    class="block px-4 py-3 hover:bg-white/10">

                                    {{ session('simrs_tipe') === 'admin' ? 'Dashboard Admin' : 'Dashboard SIMRS' }}
                                </a>
                            </li>

                            {{-- INFO ROLE --}}
                            <li class="px-4 py-2 text-sm text-white/80">
                                Role: {{ ucfirst(session('simrs_tipe')) }}
                                @if (session('simrs_sps'))
                                    <br>Spesialis: {{ session('simrs_sps') }}
                                @endif
                            </li>

                            <li>
                                <hr class="border-white/20">
                            </li>

                            {{-- LOGOUT --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="w-full text-left px-4 py-3 hover:bg-red-500/20">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="/login" class="flex items-center gap-1">
                        Login <i class="fa-solid fa-right-to-bracket"></i>
                    </a>
                @endif
            </li>
        </ul>
    </div>
</header>
<script>
    document.addEventListener('alpine:init', () => {

        document.addEventListener('keydown', function(e) {

            const header = document.querySelector(".header-wrapper");
            if (!header) return;

            const activeTag = document.activeElement.tagName.toLowerCase();
            if (activeTag === "input" || activeTag === "textarea") return;

            const component = Alpine.$data(header);

            // CTRL + SHIFT + H → Hide
            if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "h") {
                component.minimized = true;
            }

            // CTRL + SHIFT + S → Show
            if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "s") {
                component.minimized = false;
            }

            // Double ESC → Toggle
            if (e.key === "Escape") {
                const now = Date.now();
                if (!window.lastEsc) window.lastEsc = 0;

                if (now - window.lastEsc < 400) {
                    component.minimized = !component.minimized;
                }

                window.lastEsc = now;
            }

        });

    });
</script>
