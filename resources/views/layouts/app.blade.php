<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo-pin-edit.png') }}" type="image/x-icon">
    <title>{{ $title }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signin.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/mainadmin.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Smooth global */
        header {
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.35s ease;
            will-change: transform;
        }

        /* Body content wrapper */
        .page-wrapper {
            transition: margin-top 0.45s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hidden state */
        .navbar-hidden header {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        /* Saat navbar hidden → content naik */
        .navbar-hidden .page-wrapper {
            margin-top: -70px;
            /* sesuaikan tinggi navbar */
        }
    </style>
</head>

<body class="bg-white text-gray-800 font-sans">

    {{-- NAVBAR --}}
    @include('partials.mainmenu.navbar')

    {{-- CONTENT --}}
    <div class="page-wrapper">
        @yield('content')
    </div>

    @include('partials.mainmenu.footer')

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

    {{-- PAGE SCRIPT --}}
    @yield('scripts')
    @stack('scripts')


</body>

</html>
