<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signin.css') }}">
    <link rel="stylesheet" href="../css/app.css">
    
    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&display=swap" rel="stylesheet">

    {{-- FONT AWESOME --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-white text-gray-800 font-sans">

    {{-- NAVBAR --}}
    @include('partials.mainmenu.navbar')

    {{-- CONTENT --}}
    <div>
        @yield('content')
    </div>

    @include('partials.mainmenu.footer')

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    {{-- PAGE SCRIPT --}}
    @yield('scripts')

</body>
</html>
