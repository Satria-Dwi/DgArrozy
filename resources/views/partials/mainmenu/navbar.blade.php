@php
    $active = $active ?? '';
@endphp

<header x-data="{ mobileMenu: false }"
    class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white shadow sticky top-0 z-50">

    <!-- BAR ATAS -->
    <div class="max-w-7xl mx-auto px-6 py-0.5 flex items-center justify-between">

        <!-- KIRI -->
        <div class="flex items-center gap-2">
            <img src="/img/logo-pin-edit.png" class="header-icon">
            <span class="text-xl font-bold tracking-wide">DgArrozy</span>
            <span>|</span>
        </div>

        {{-- <!-- TOGGLE MOBILE -->
        <button @click="mobileMenu = !mobileMenu" class="md:hidden text-2xl text-white">
            <i class="fa-solid fa-bars"></i>
        </button> --}}

        <!-- MENU DESKTOP -->
        <div class="menu hidden md:block">
            <ul class="flex gap-6 font-medium">
                <li><a href="/" class="{{ $active === 'home' ? 'active' : '' }}">Home</a></li>
                <li><a href="/dashboard" class="{{ $active === 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/stream" class="{{ $active === 'stream' ? 'active' : '' }}">Stream</a></li>

                @php $roleCode = session('account_role_code'); @endphp
                @if (session('dgarrozy_login') && in_array($roleCode, ['admin', 'manajemen']))
                    <li><a href="/mainadmin" class="{{ $active === 'mainadmin' ? 'active' : '' }}">Manajemen</a></li>
                @endif
            </ul>
        </div>

        <!-- RIGHT ITEM -->
        <div class="right-item hidden sm:block" x-data="{ open: false }">
            @if (session()->has('dgarrozy_login'))
                <div class="relative">
                    <button @click="open = !open" class="flex items-center gap-1">
                        Welcome Back, {{ session('account_email') }}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-cloak @click.away="open = false"
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
                                <button class="w-full text-left px-4 py-3 hover:bg-red-500/10">
                                    Signout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="/signin">Login</a>
                <i class="fa-solid fa-right-to-bracket"></i>
            @endif
        </div>
    </div>

    <div x-show="mobileMenu" x-transition x-cloak
        class="md:hidden px-6 pb-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500">

        <ul class="flex flex-col gap-4 font-medium">
            <li><a href="/">Home</a></li>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/stream">Stream</a></li>

            @if (session('dgarrozy_login') && in_array($roleCode, ['admin', 'manajemen']))
                <li><a href="/mainadmin">Manajemen</a></li>
            @endif

            <!-- USER MOBILE -->
            <li class="border-t border-white/30 pt-4 mt-4">
                @if (session()->has('dgarrozy_login'))
                    <div class="flex flex-col gap-3">
                        <span class="text-sm opacity-80">
                            {{ session('account_email') }}
                        </span>

                        <a href="{{ session('account_role') === 'user' ? '/profile' : '/mainadmin' }}">
                            {{ session('account_role') === 'user' ? 'Profil Saya' : 'MArrozy' }}
                        </a>

                        <form action="/signout" method="POST">
                            @csrf
                            <button class="text-left text-red-200">
                                Signout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="/signin" class="font-semibold">
                        Login
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </a>
                @endif
            </li>
        </ul>
    </div>


</header>
