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
        <div id="rt-jam" class="font-mono text-sm md:text-base tracking-wider">
            09:00:00 WIB
        </div>
    </div>

    <!-- Right Icons -->
    <div class="flex items-center gap-4">

        <!-- Notification -->
        @role(['dokter'])
            <div class="relative">

                <button id="btnNotifKonsultasi"
                    class="
        relative
        p-2
        rounded-xl
        transition-all
        duration-300
        hover:bg-white/20
        hover:backdrop-blur-sm
        hover:scale-110
        hover:shadow-lg
        group
    ">

                    <i
                        class="
        fas fa-bell
        text-lg
        transition-all
        duration-300
        group-hover:text-yellow-300
        group-hover:rotate-12
    "></i>

                    <span id="badgeNotifKonsultasi"
                        class="
            hidden
            absolute
            -top-1
            -right-1
            min-w-[18px]
            h-[18px]
            px-1
            flex
            items-center
            justify-center
            text-[10px]
            font-bold
            text-white
            bg-red-500
            rounded-full
            animate-pulse
        ">
                    </span>

                </button>

                <div id="dropdownNotifKonsultasi"
                    class="
                        hidden
                        absolute
                        right-0
                        mt-2
                        w-80
                        bg-white
                        rounded-2xl
                        shadow-xl
                        border
                        border-slate-200
                        overflow-hidden
                        z-50
                    ">

                    <div class="px-4 py-3 border-b bg-slate-50">
                        <div class="font-semibold text-black">
                            Notifikasi Konsultasi
                        </div>
                    </div>

                    <div id="notifKonsultasiContent">

                        <div class="p-4 text-sm text-slate-500">
                            Memuat...
                        </div>

                    </div>

                </div>

            </div>
        @endrole

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
            <div x-show="openProfile" @click.outside="openProfile = false" x-transition
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
<script>
    // console.log('SCRIPT NOTIF LOADED');

    function loadNotifKonsultasi() {

        fetch('/dokter/notif-konsultasi')

            .then(res => res.json())


            .then(result => {

                // console.log('RESULT :', result);

                if (result.error) return;

                const badge =
                    document.getElementById(
                        'badgeNotifKonsultasi'
                    );

                const content =
                    document.getElementById(
                        'notifKonsultasiContent'
                    );
                // console.log('CONTENT :', content);

                if (!badge || !content) return;

                if (result.total > 0) {

                    badge.classList.remove('hidden');

                    badge.textContent =
                        result.total > 99 ?
                        '99+' :
                        result.total;

                } else {

                    badge.classList.add('hidden');
                }

                content.innerHTML = `
                <div class="p-4 space-y-3">

                    <div
                        class="
                            flex
                            justify-between
                            items-center
                            p-3
                            rounded-xl
                            bg-blue-50
                        ">
                        <span class="font-semibold text-blue-600">
                            Konsultasi Masuk
                        </span>

                        <span class="
                            font-bold
                            text-blue-600
                        ">
                            ${result.masuk}
                        </span>
                    </div>

                    <div
                        class="
                            flex
                            justify-between
                            items-center
                            p-3
                            rounded-xl
                            bg-emerald-50
                        ">
                        <span class="font-semibold text-green-500">
                            Konsultasi Keluar
                        </span>

                        <span class="
                            font-bold
                            text-emerald-600
                        ">
                            ${result.keluar}
                        </span>
                    </div>

                    <a
                        href="/dokter/konsultasi"
                        class="
                            block
                            text-center
                            bg-indigo-600
                            text-white
                            py-2
                            rounded-xl
                            hover:bg-indigo-700
                        ">
                        Lihat Konsultasi
                    </a>

                </div>
            `;

            })

            .catch(console.error);
    }

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            // console.log('DOM READY');

            // PANGGIL DI SINI
            loadNotifKonsultasi();

            const btn =
                document.getElementById(
                    'btnNotifKonsultasi'
                );

            const dropdown =
                document.getElementById(
                    'dropdownNotifKonsultasi'
                );

            if (!btn || !dropdown) return;

            btn.addEventListener(
                'click',
                function(e) {

                    e.stopPropagation();

                    dropdown.classList.toggle(
                        'hidden'
                    );

                }
            );

            document.addEventListener(
                'click',
                function() {

                    dropdown.classList.add(
                        'hidden'
                    );

                }
            );

        }
    );
</script>
