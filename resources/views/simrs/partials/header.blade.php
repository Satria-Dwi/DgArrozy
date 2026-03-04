<header
    class="h-14 md:h-16
           flex items-center justify-between
           px-6
          bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 
           text-white
           border-b border-slate-200
           sticky top-0 z-30
           shadow-sm">

    <!-- Toggle Sidebar (mobile only) -->
    <button @click="$dispatch('toggle-sidebar')"
        class="md:hidden p-2 rounded-lg 
               hover:bg-slate-100 active:scale-95 transition">
        <i class="fa-solid fa-bars text-lg"></i>
    </button>

    <!-- Date -->
    <div class="text-xs md:text-sm  truncate">
        {{ now()->locale('id')->translatedFormat('l, d F Y') }}
        <div id="rt-jam"
            class="font-mono text-sm md:text-base tracking-wider">
            09:00:00 WIB
        </div>
    </div>

    <!-- Right Icons -->
    <div class="flex items-center gap-4">

        <!-- Notification -->
        <button class="relative p-2 rounded-lg hover:bg-indigo-300 transition">
            <i class="fas fa-bell"></i>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- Profile -->
        <div x-data="{ openProfile: false }" class="relative">

            <!-- AVATAR -->
            <button @click="openProfile = !openProfile"
                class="w-9 h-9 rounded-full
                       bg-gradient-to-br from-indigo-500 to-purple-600
                       flex items-center justify-center
                       text-xs font-bold uppercase
                       text-white
                       hover:ring-2 hover:ring-indigo-300
                       transition shadow-sm">

                {{ strtoupper(substr(session('simrs_nama'), 0, 1)) }}
            </button>

            <!-- DROPDOWN -->
            <div x-show="openProfile"
                @click.outside="openProfile = false"
                x-transition
                class="absolute right-0 mt-3 w-56
                       bg-white
                       rounded-xl shadow-xl
                       border border-slate-200
                       overflow-hidden
                       z-50">

                <!-- HEADER PROFILE -->
                <div class="px-4 py-3 border-b border-slate-200">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ session('simrs_nama') }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ session('simrs_dept') }}
                    </div>
                </div>

                <!-- MENU -->
                <div class="py-2">

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2 text-sm
                               text-slate-700
                               hover:bg-indigo-50
                               transition">
                        <i class="fas fa-user text-indigo-500"></i>
                        Profile
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2 text-sm
                               text-slate-700
                               hover:bg-indigo-50
                               transition">
                        <i class="fas fa-cog text-indigo-500"></i>
                        Settings
                    </a>

                    <div class="border-t border-slate-200 my-1"></div>

                    <form action="/logout" method="POST">
                        @csrf
                        <button
                            class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm
                                   text-red-600 hover:bg-red-50
                                   transition">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</header>