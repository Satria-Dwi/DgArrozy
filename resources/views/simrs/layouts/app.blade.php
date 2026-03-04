<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo-pin-edit.png') }}" type="image/x-icon">
    <title>{{ 'SIMRS Master | ' . $title }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signin.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/mainadmin.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="bg-white text-gray-800 font-sans">

    {{-- NAVBAR --}}
    {{-- @include('partials.mainmenu.navbar') --}}

    {{-- CONTENT --}}
    {{-- <div class="page-wrapper">
        @yield('content')
    </div> --}}

    <div x-data="{ open: false, collapse: false }" @toggle-sidebar.window="open = !open" class="flex min-h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        @include('simrs.partials.sidebar')

        {{-- BACKDROP MOBILE --}}
        <div x-show="open" @click="open = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 md:hidden"
            x-cloak>
        </div>

        {{-- MAIN --}}
        <div :class="collapse ? 'md:ml-20' : 'md:ml-56'"
            class="flex-1 flex flex-col min-h-screen overflow-x-hidden transition-all duration-300">

            {{-- TOPBAR --}}
            @include('simrs.partials.header')

            {{-- CONTENT --}}
            <main class="flex-1 p-6 bg-slate-100 transition-all duration-300">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- CHART JS --}}
    <script>
        const sidebar = document.getElementById("sidebar");
        const toggle = document.getElementById("sidebarToggle");
        const overlay = document.getElementById("sidebarOverlay");

        toggle?.addEventListener("click", () => {
            sidebar?.classList.toggle("active");
            overlay?.classList.toggle("active");
        });

        overlay?.addEventListener("click", () => {
            sidebar?.classList.remove("active");
            overlay?.classList.remove("active");
        });
    </script>
    <script>
        document.addEventListener("keydown", function(e) {

            const activeTag = document.activeElement.tagName.toLowerCase();
            if (activeTag === "input" || activeTag === "textarea") return;

            // CTRL + SHIFT + H → Hide
            if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "h") {
                document.body.classList.add("navbar-hidden");
            }

            // CTRL + SHIFT + S → Show
            if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === "s") {
                document.body.classList.remove("navbar-hidden");
            }

            // Double ESC → Toggle
            if (e.key === "Escape") {
                const now = Date.now();
                if (!window.lastEsc) window.lastEsc = 0;

                if (now - window.lastEsc < 400) {
                    document.body.classList.toggle("navbar-hidden");
                }

                window.lastEsc = now;
            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="{{ asset('js/simrs/chartkunjungan.js') }}"></script>
    <script src="{{ asset('js/simrs/vidplayschedule.js') }}"></script>
    <script>
        const el = (id) => document.getElementById(id);

        function realtimeClock() {
            const now = new Date();
            if (el("rt-hari"))
                el("rt-hari").textContent = now
                .toLocaleDateString("id-ID", {
                    weekday: "long"
                })
                .toUpperCase();

            if (el("rt-tanggal"))
                el("rt-tanggal").textContent = now.toLocaleDateString("id-ID", {
                    day: "2-digit",
                    month: "long",
                    year: "numeric",
                });

            if (el("rt-jam"))
                el("rt-jam").textContent = now.toLocaleTimeString("id-ID", {
                    hour12: false
                }) + " WIB";
        }

        realtimeClock();
        setInterval(realtimeClock, 1000);
    </script>


    {{-- PAGE SCRIPT --}}
    @yield('scripts')
    @stack('scripts')


</body>

</html>
