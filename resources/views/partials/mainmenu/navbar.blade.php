@php
    $active = $active ?? '';
@endphp

<header class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-2 flex items-center">
        <img src="/img/logo-pin-edit.png" class="header-icon">
        <span class="text-xl font-bold tracking-wide">DgArrozy</span>
        <h1>|</h1>
        <div class="menu">
            <ul class="flex gap-6 font-medium">
                <li><a href="/" class="{{ $active === 'home' ? 'active' : '' }}">Home</a></li>
                <li><a href="/dashboard" class="{{ $active === 'dashboard' ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/stream" class="{{ $active === 'stream' ? 'active' : '' }}">Stream</a></li>
                @php
                    $roleCode = session('account_role_code'); // session simpan code
                @endphp

                @if (session('dgarrozy_login') && in_array($roleCode, ['admin', 'manajemen']))
                    <li>
                        <a href="/mainadmin"
                            class="{{ $active === 'mainadmin' ? 'active' : '' }}">
                            Manajemen
                        </a>
                    </li>
                @endif

            </ul>
        </div>

        <div class="right-item" x-data="{ open: false }">
            @if (session()->has('dgarrozy_login'))
                <div class="relative">
                    <button @click="open = !open" class="flex items-center gap-1">
                        Welcome Back, {{ session('account_email') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-cloak @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 border rounded shadow-lg">

                        {{-- Menu Dashboard / Profil --}}
                        <li>
                            <a href="{{ session('account_role') === 'user' ? '/profile' : '/mainadmin' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-blue-500/10">
                                <i
                                    class="{{ session('account_role') === 'user' ? 'bi bi-person' : 'bi bi-layout-text-window-reverse' }}"></i>
                                {{ session('account_role') === 'user' ? 'Profil Saya' : 'MArrozy' }}
                            </a>
                        </li>

                        <li>
                            <hr>
                        </li>

                        {{-- Sign Out --}}
                        <li>
                            <form action="/signout" method="POST">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 hover:text-red-600 w-full text-left">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Signout
                                </button>
                            </form>
                        </li>
                    </ul>

                </div>
            @else
                {{-- BELUM LOGIN --}}
                <a href="/signin" class="{{ ($active ?? '') === 'signin' ? 'active' : '' }}">
                    Login
                </a>
                <i class="fa-solid fa-right-to-bracket"></i>
            @endif
        </div>


    </div>
</header>
