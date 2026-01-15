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
            </ul>
        </div>
        <div class="right-item" x-data="{ open: false }">
            @auth
                <div class="relative">
                    <button @click="open = !open" class="flex items-center">
                        Welcome Back, {{ auth()->user()->name }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
    
                    <ul x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-blue-500 border rounded shadow-lg">
                        <li>
                            <a href="/dashboard" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="bi bi-layout-text-window-reverse"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <hr>
                        </li>
                        <li>
                            <form action="/signout" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                                    <i class="bi bi-box-arrow-right"></i> Signout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button id="btnsignin" class="cursor-pointer">Login</button>
                {{-- <a href="/signin" class="{{ $active === 'signin' ? 'active' : '' }}" id="btnsignin">
                    Login
                </a> --}}
                <i class="fa-solid fa-right-to-bracket"></i>
            @endauth
        </div>
    </div>
</header>
