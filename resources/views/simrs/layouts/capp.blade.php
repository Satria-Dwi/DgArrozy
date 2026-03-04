<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/logo-pin-edit.png') }}" type="image/x-icon">
    <title>{{ 'SIMRS Master | ' . $title }}</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mainadmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simrs.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <!-- Collapse plugin dulu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white text-gray-800 font-sans">

    <div x-data="{ open: false, collapse: false }" @toggle-sidebar.window="open = !open" class="flex min-h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        @include('simrs.partials.sidebar')

        {{-- BACKDROP MOBILE --}}
        <div x-show="open" @click="open = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 md:hidden"
            x-cloak>
        </div>

        {{-- MAIN --}}
        <div :class="collapse ? 'md:ml-20' : 'md:ml-56'"
            class="flex-1 flex flex-col min-h-screen overflow-x-hidden transition-[margin-left] duration-300">

            {{-- TOPBAR --}}
            @include('simrs.partials.header')

            {{-- CONTENT --}}
            <main class="flex-1 p-6 bg-slate-100 transition-[margin-left] duration-300">
                @yield('content')
            </main>

        </div>
    </div>
    @yield('scripts')
    @stack('scripts')

</body>
<script>
    function el(id) {
        return document.getElementById(id);
    }

    function realtimeClock() {
        const now = new Date(
            new Date().toLocaleString("en-US", {
                timeZone: "Asia/Jakarta"
            })
        );

        if (el("rt-jam"))
            el("rt-jam").textContent =
            now.toLocaleTimeString("id-ID", {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }) + " WIB";
    }

    realtimeClock();
    setInterval(realtimeClock, 1000);
</script>


</html>
