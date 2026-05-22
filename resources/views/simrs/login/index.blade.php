<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login SIMRS' }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-700 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 sm:p-8">

        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Login Dashboard
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Masuk menggunakan akun
            </p>
        </div>

        <!-- ERROR (SATU SAJA) -->
        @if (session('error'))
            <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="/login" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- ID USER / NIK -->
            <div>
                <label class="text-sm text-gray-600">NIK</label>
                <input type="text" name="id_user" value="{{ old('id_user') }}" required maxlength="30"
                    inputmode="numeric" autocomplete="username"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Masukkan NIK">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password" required maxlength="50" autocomplete="current-password"
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    placeholder="Masukkan password">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99]
                       text-white py-2.5 sm:py-3 rounded-lg font-semibold
                       text-sm sm:text-base transition-all">
                Login
            </button>
        </form>

        <!-- Back -->
        <div class="mt-6 sm:mt-8 text-center text-xs sm:text-sm text-gray-400">
            <a href="/" class="hover:underline">Back To Menu</a>
        </div>

        <div class="mt-4 flex flex-col items-center scale-75 animate-bounce" style="animation: float 3s ease-in-out infinite;">

            <img id="pixelGuy" src="" class="w-20 h-20 pixel-art transition-all duration-300">

            <div id="pixelBubble"
                class="mt-2 bg-gray-800 text-white text-[10px]
               px-2 py-1 rounded-lg opacity-0
               transition-all max-w-[140px] text-center">
                ...
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-6 sm:mt-8 text-center text-xs sm:text-sm text-gray-400">
            © {{ date('Y') }} MarRozy • All rights reserved.
        </div>
    </div>
    <script src="{{ asset('js/login-character.js') }}"></script>
</body>

</html>
