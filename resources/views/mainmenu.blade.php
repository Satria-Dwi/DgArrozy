@extends('layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="text-center py-28 bg-cover bg-center bg-no-repeat" style="background-image: url('/img/profile.jpg');">
        <div class="max-w-2xl mx-auto px-4 animate-fade-in-up">
            <img src="{{ asset('img/logo-pin-edit.png') }}?v={{ filemtime(public_path('img/logo-pin-edit.png')) }}"
                alt="" class="mx-auto profile-company shadow" style="width: 180px; border-radius:50%">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 text-[#F7941D]"
                style="text-shadow: 0 2px 6px rgba(0,0,0,0.2)">RSUD <span class="text-[#0FA36B]"
                    style="text-shadow: 0 2px 6px rgba(0,0,0,0.2)">AR ROZY</span>
            </h2>
            <p class="text-lg text-white mb-6"
                style="text-shadow:
                    0 2px 4px rgba(0,0,0,0.9),
                    0 4px 8px rgba(0,0,0,0.8),
                    0 8px 16px rgba(0,0,0,0.7),
                    0 12px 24px rgba(0,0,0,0.6);">
                Melayani Sepenuh Hati
                Inti Kesuksesan Kami
            </p>
            <a href="/dashboard"
                class="bg-indigo-600 text-white px-6 py-3 rounded shadow hover:bg-indigo-700 transition">Dashboard</a>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Tentang Kami</h2>
            <div class="mb-6 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            <p class="text-lg text-gray-700 leading-relaxed">
                <strong>RSUD Ar Rozy</strong> dalam melayani pasien selalu dilandasi dengan ketulusan dan keikhlasan secara
                profesional, dengan tetap menjaga motivasi dan semangat yang tinggi tanpa putus asa, dan memenuhi
                standarisasi pelayanan yang prima.

                .<br><br>
                Hal tersebut menjadi landasan RSUD Ar Rozy untuk mencapai keberhasilan dalam kegiatan usaha yang dijalankan,
                karena RSUD Ar Rozy menempatkan pasien/ pelanggan sebagai bagian penting perusahaan dan berkomitmen
                memberikan pelayanan kesehatan terbaik
            </p>
        </div>
    </section>

    <section class="py-20 bg-gray-50">

        <div class="max-w-screen-2xl mx-auto px-6">

            <!-- Judul Section -->
            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">
                    IGD & Rawat Inap
                </h2>
                <p class="mt-4 text-gray-500 text-lg">
                    Pelayanan cepat, profesional, dan penuh kepedulian
                </p>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            </div>

            <!-- Gambar -->
            <div class="flex flex-col md:flex-row items-center min-h-[420px] md:gap-x-[8px]">

                <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl group">

                    <img src="/img/igd.jpg"
                        class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110"
                        style="clip-path: polygon(0 0, 100% 0, 88% 100%, 0% 100%);" alt="IGD">

                    <div class="absolute bottom-32 left-8 text-white">
                        <h3 class="text-2xl md:text-3xl font-bold">Instalasi Gawat Darurat</h3>
                        <p class="text-sm mt-2 text-gray-200">Siaga 24 Jam dengan tim medis profesional</p>
                    </div>

                    <!-- KONTAK (WA + TELP SEJAJAR) -->
                    <div
                        class="absolute bottom-8 left-8 z-20
                                bg-white/95 backdrop-blur-sm
                                rounded-xl px-5 py-4
                                shadow-xl
                                flex flex-col sm:flex-row gap-4 sm:items-center">

                        <!-- WhatsApp -->
                        <a href="https://wa.me/6281234567890" class="flex items-center gap-3 group/contact">

                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.52 3.48A11.86 11.86 0 0012.05 0C5.49 0 .14 5.36.14 11.96c0 2.11.55 4.17 1.6 5.98L0 24l6.26-1.64a11.9 11.9 0 005.79 1.48h.01c6.56 0 11.91-5.36 11.91-11.96 0-3.19-1.24-6.19-3.45-8.4z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 leading-none">WhatsApp</p>
                                <p class="text-sm font-semibold text-green-600">
                                    0812-3456-7890
                                </p>
                            </div>
                        </a>

                        <!-- Divider -->
                        <div class="hidden sm:block h-10 w-px bg-gray-300"></div>

                        <!-- Telepon -->
                        <a href="tel:122" class="flex items-center gap-3">

                            <div class="bg-red-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M6.62 10.79a15.46 15.46 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V21a1 1 0 01-1 1C9.39 22 2 14.61 2 5a1 1 0 011-1h4.49a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.25 1.01l-2.19 2.2z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 leading-none">Hotline IGD</p>
                                <p class="text-sm font-semibold text-red-600">
                                    122
                                </p>
                            </div>
                        </a>

                        <div class="hidden sm:block h-10 w-px bg-gray-300"></div>

                        <a href="tel:03354490000" class="flex items-center gap-3">

                            <div class="bg-red-100 p-2 rounded-full">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M6.62 10.79a15.46 15.46 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V21a1 1 0 01-1 1C9.39 22 2 14.61 2 5a1 1 0 011-1h4.49a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.25 1.01l-2.19 2.2z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 leading-none">Hotline IGD</p>
                                <p class="text-sm font-semibold text-red-600">
                                    0335-4490000
                                </p>
                            </div>
                        </a>

                    </div>

                </div>

                <!-- Rawat Inap -->
                <!-- ================= RAWAT INAP ================= -->
                <div class="w-full md:w-1/2 relative overflow-hidden rounded-2xl group">

                    <!-- Shape sama seperti IGD -->
                    <div class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110"
                        style="clip-path: polygon(12% 0, 100% 0, 100% 100%, 0% 100%);" alt="Rawat Inap">

                        <!-- Slider -->
                        <div id="slider" class="flex transition-transform duration-700 ease-in-out">

                            <!-- Slides -->
                            <div class="min-w-full">
                                <img src="/img/leaflet/1.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/2.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/3.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/4.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/5.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/6.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/7.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                            <div class="min-w-full">
                                <img src="/img/leaflet/8.png"
                                    class="w-full h-[320px] md:h-[460px] object-cover transition duration-700 group-hover:scale-110">
                            </div>

                        </div>
                    </div>
                    <!-- Text -->
                    <div class="absolute bottom-8 left-8 text-white z-10">
                        <h3 class="text-2xl md:text-3xl font-bold">Rawat Inap</h3>
                        <p class="text-sm mt-2 text-gray-200">Fasilitas modern dengan pelayanan optimal</p>
                    </div>

                    <!-- Dots -->
                    <div class="absolute bottom-4 right-6 flex space-x-2 z-10">
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                        <span class="dot w-3 h-3 bg-white/60 rounded-full cursor-pointer"></span>
                    </div>

                </div>
            </div>
    </section>

    <!-- Services -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">
                    Rawat Jalan 🩺
                </h2>
                <p class="mt-4 text-gray-500 text-lg">
                    Langkah Kecil Hari Ini, Sehat Esok Hari.
                </p>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- dr. novita --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                                bg-gradient-to-r from-indigo-600 to-purple-600 
                                rounded-t-2xl flex items-center
                                pl-40 pr-6 z-10">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug ml-auto z-20">
                            dr. Novita Lavi Nikmah, Sp.PD
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 left-6 z-20">
                        <img src="/img/dokter/dr. Novita Lavi Nikmah, Sp. PD.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Penyakit Dalam
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa & Rabu, 09:00 - 11:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. dicky --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                                bg-gradient-to-r from-indigo-600 to-purple-600 
                                rounded-t-2xl flex items-center
                                pl-40 pr-6 z-10">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug ml-auto z-20">
                            dr. Dicky Febrianto, Sp.PD
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 left-6 z-20">
                        <img src="/img/dokter/dr. Dicky Febrianto, Sp.PD.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Penyakit Dalam
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa, 08:00 - 11:00 WIB</p>
                            <p>Rabu, 08:00 - 13:00 WIB</p>
                            <p>Junm'at, 08:30 - 13:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. eliza --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Eliza L. Pramugaria, Sp.PD., FINASIM
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6">
                        <img src="/img/dokter/dr. Elieza L Pramugaria, Sp. PD.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Penyakit Dalam
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Senin & Kamis, 08:30 - 13:00 WIB</p>
                            <p>Jum'at, 08:00 - 11:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. yusni --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                                bg-gradient-to-r from-indigo-600 to-purple-600 
                                rounded-t-2xl flex items-center
                                pl-40 pr-6 z-10">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug ml-auto z-20">
                            dr. Mohammad Ali Yusni, Sp.B. FINACS
                        </span>

                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 left-6 z-20">
                        <img src="/img/dokter/dr. Mohammad Ali Yusni Sp.B.FINACS.png"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Bedah
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa, 08:30 - 13:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. Abraar --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                                bg-gradient-to-r from-indigo-600 to-purple-600 
                                rounded-t-2xl flex items-center
                                pl-40 pr-6 z-10">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug ml-auto z-20">
                            dr. Abraar HS Kuddah, M.Si.,Med.,Sp.B
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 left-6 z-20">
                        <img src="/img/dokter/dr. Abraar HS Kuddah, M.Si.,Med.,Sp.B.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Bedah
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Senin & Rabu, 08:30 - 13:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. Risa --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Risa Yolanda Matullesy, Sp.B
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6">
                        <img src="/img/dokter/dr. Risa Yolanda Matullessy, Sp.B.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Bedah
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Kamis, 08:30 - 13:00 WIB</p>
                            <p>Jum'at, 08:00 - 11:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. maria --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Maria Diah Zakiyah, Sp.OG, M.H.
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6">
                        <img src="/img/dokter/dr. Maria Diah Zakiyah, Sp.OG.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Kebidanan & Kandungan
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa & Kamis, 08:30 - 13:00 WIB</p>
                            <p>Jum'at, 08:00 - 11:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. Adrian  --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Adrian Yusdianto Sp.P
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6">
                        <img src="/img/dokter/dr. Adrian Yusdianto Sp.P.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Paru
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Senin & Jum'at, 08:00 - 11:00 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. Andre --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Muhammad Andrie Wibowo Sp.OT
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/dokter/dr. Muhammad Andrie Wibowo Sp.OT.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Orthopaedi & Traumatologi
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa, 08:00 - 11:00 WIB</p>
                            <p>Jum'at, 07:30 - 09:30 WIB</p>
                        </div>
                    </div>
                </div>
                {{-- dr. Normayanti --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                                bg-gradient-to-r from-indigo-600 to-purple-600 
                                rounded-t-2xl flex items-center
                                pl-40 pr-6 z-10">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug ml-auto z-20">
                            dr. Normayanti, Sp.KG
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 left-6 z-20">
                        <img src="/img/dokter/drg. Normayanti, Sp.KG.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Konservasi Gigi
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa, 10:30 - 12:00 WIB</p>
                            <p>Jum'at, 10:00 - 11:30 WIB</p>
                        </div>
                    </div>

                </div>
                {{-- dr. Cik Kahadi --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Cik Kahadi, Sp.JP.,FIHA
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/dokter/dr. Cik Kahadi, Sp.JP.,FIHA.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Jantung & Pembuluh Darah
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa, 07:30 - 09:30 WIB</p>
                            <p>Jum'at, 07:30 - 09:30 WIB</p>
                        </div>
                    </div>
                </div>
                {{-- dr. Dwi --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Dwi Agustina Ramadani, Sp.A
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/dokter/dr. Lilis Catur Setyawati, Sp.Rad.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Klinik Anak
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Senin, Rabu, Jum'at, 07:30 - 08:50 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mb-24 mt-20">
                <h2 class="text-4xl md:text-3xl font-bold text-gray-800 tracking-tight">
                    Pelayanan Penunjang 🩺❤
                </h2>
                <p class="mt-4 text-gray-500 text-lg">
                    Setiap Detail untuk Kesembuhan Anda.
                </p>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                {{-- dr. Lilis --}}
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Lilis Catur Setyawati, Sp.Rad
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/dokter/dr. Lilis Catur Setyawati, Sp.Rad.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Instalasi Radiologi
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        {{-- <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa & Kamis, 10:00 - 13:00 WIB</p>
                            <p>Sabtu, 09:00 - 12:00 WIB</p>
                        </div> --}}
                    </div>
                </div>
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            dr. Boby Mulyadi, Sp.PK.png
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/dokter/dr. Boby Mulyadi, Sp.PK.png" alt="Foto Dokter"
                            class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Instalasi Laboratorium
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        {{-- <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa & Kamis, 09:00 - 13:00 WIB</p>
                        </div> --}}
                    </div>
                </div>
                <div
                    class="relative bg-white rounded-2xl shadow-lg 
                            hover:shadow-2xl transition duration-300 
                            transform hover:-translate-y-2 
                            pt-24 pb-8 px-8 overflow-visible">

                    <!-- Gradient Header -->
                    <div
                        class="absolute top-0 left-0 w-full h-20
                            bg-gradient-to-r from-indigo-600 to-purple-600 
                            rounded-t-2xl flex items-center px-6 pr-40">

                        <span class="text-white font-semibold text-sm tracking-wide leading-snug">
                            Instalasi Farmasi
                        </span>
                    </div>

                    <!-- Foto Dokter -->
                    <div class="absolute -top-16 right-6 z-20">
                        <img src="/img/instfar.png" alt="Foto Dokter" class="w-36 h-36 object-contain drop-shadow-2xl">
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Instalasi Farmasi
                        </h3>

                        <div class="w-12 h-1 bg-indigo-500 mx-auto rounded-full mb-4"></div>

                        {{-- <p class="text-sm text-gray-500 mb-2">
                            Jadwal Praktik
                        </p>

                        <div class="space-y-1 text-gray-700 font-medium">
                            <p>Selasa & Kamis, 09:00 - 13:00 WIB</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">

        <div class="max-w-6xl mx-auto px-6">

            <!-- Judul Section -->
            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight">
                    Layanan Unggulan Kami
                </h2>
                <p class="mt-4 text-gray-500 text-lg">
                    Pelayanan cepat, profesional, dan penuh kepedulian
                </p>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            </div>

            <!-- Gambar -->
            <div class="max-w-7xl mx-auto px-6">

                <!-- Grid Brosur -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                    <!-- Card -->
                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/instalasi gizi.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Instalasi Gizi</h3>
                            <p class="text-sm opacity-80">Menu sehat & terkontrol ahli</p>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/MCU.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Medical Check Up</h3>
                            <p class="text-sm opacity-80">Deteksi dini untuk kesehatan optimal</p>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/ruang bermain rawat inap anak.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Ruang Bermain Anak</h3>
                            <p class="text-sm opacity-80">Nyaman & ramah anak</p>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/ruang operasi.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Ruang Operasi</h3>
                            <p class="text-sm opacity-80">Standar medis modern & steril</p>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/VIP.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Kamar VIP</h3>
                            <p class="text-sm opacity-80">Privasi & kenyamanan maksimal</p>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden rounded-3xl shadow-xl">
                        <img src="/img/layanan_unggulan/VVIP.JPG"
                            class="w-full h-80 object-cover transform group-hover:scale-110 transition duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold">Kamar VVIP</h3>
                            <p class="text-sm opacity-80">Fasilitas eksklusif & premium</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">

            <!-- Header -->
            <div class="text-center mb-14">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800">
                    Ikuti Aktivitas & Informasi Terbaru Kami
                </h2>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                    Dapatkan update kegiatan, edukasi kesehatan, dan informasi penting lainnya
                    melalui Instagram dan YouTube resmi kami.
                </p>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

                <!-- Instagram (1 Kolom) -->
                <div class="md:col-span-1 bg-white rounded-2xl shadow-md p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        Instagram Terbaru
                    </h3>

                    <blockquote class="instagram-media w-full"
                        data-instgrm-permalink="https://www.instagram.com/reel/DVKsgqUDufr/">
                    </blockquote>
                    <script async src="//www.instagram.com/embed.js"></script>

                    <div class="text-center mt-4">
                        <a href="https://www.instagram.com/rsudarrozy/" target="_blank"
                            class="inline-block px-5 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-full text-sm transition">
                            Follow Instagram
                        </a>
                    </div>
                </div>

                <!-- YouTube (2 Kolom) -->
                <div class="md:col-span-2 bg-white rounded-2xl shadow-md p-6 flex flex-col">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Profile Kami
                    </h3>

                    <!-- Container dengan tinggi sama seperti IG -->
                    <div class="aspect-video w-full rounded-xl overflow-hidden shadow-lg">
                        <iframe class="w-full h-full"
                            src="https://www.youtube.com/embed/05UQh8NLM8I?autoplay=1&mute=1&playsinline=1&rel=0"
                            title="YouTube video" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <div class="mt-6">
                        <a href="https://www.youtube.com/" target="_blank"
                            class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm transition">
                            Subscribe YouTube
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Review Kami ⭐</h2>
            <div class="mt-4 mb-4 w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 mx-auto rounded-full"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <p class="text-gray-700 italic mb-4">"Pelayanan bagus, intinya act of service ❤️
                        Kebersihan bagus, fasilitas juga bagus. Nakes ramah. Makanan enak."</p>
                    <h4 class="text-gray-900 font-semibold">— Silvia Resti</h4>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <p class="text-gray-700 italic mb-4">"Selama di rawat di ruang sofa, ruangannya nyaman & bagus,
                        pelayanannya ramah enak, Dokter pelayanannya bagus, perawat juga pelayanannya bagus & tanggap."</p>
                    <h4 class="text-gray-900 font-semibold">— misdi hartono</h4>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">

            <!-- Title -->
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold">
                    <span class="border-b-4 border-blue-600 pb-2">
                        <span class="font-extrabold">LINK</span> TERKAIT
                    </span>
                </h2>
            </div>

            <!-- Grid Links -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">

                <!-- Item -->
                <a href="https://probolinggokota.go.id" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/pemkot.png" class="h-16 object-contain mb-3" alt="Pemkot">

                    <p class="text-sm font-semibold text-gray-700">
                        Website Resmi Pemerintah Kota Probolinggo
                    </p>
                </a>

                <!-- Item -->
                <a href="https://lapor.go.id" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/laporgoid.png" class="h-16 object-contain mb-3" alt="Lapor">

                    <p class="text-sm font-semibold text-gray-700">
                        LAPOR.GO.ID
                    </p>
                </a>

                <!-- Item -->
                <a href="https://diskominfo.probolinggokota.go.id" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/diskominfo.png" class="h-16 object-contain mb-3" alt="Diskominfo">

                    <p class="text-sm font-semibold text-gray-700">
                        Diskominfo Kota Probolinggo
                    </p>
                </a>

                <a href="https://www.kemendagri.go.id/" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/kemendagri.png" class="h-16 object-contain mb-3" alt="Diskominfo">

                    <p class="text-sm font-semibold text-gray-700">
                        Kemendagri
                    </p>
                </a>

                <a href="https://satudata.probolinggokota.go.id/" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/satudata.png" class="h-16 object-contain mb-3" alt="Diskominfo">

                    <p class="text-sm font-semibold text-gray-700">
                        Satu Data
                    </p>
                </a>
                <a href="https://probolinggokota.go.id/" target="_blank"
                    class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center text-center 
                       hover:shadow-xl hover:-translate-y-1 transition duration-300">

                    <img src="img/link_terkait/112.png" class="h-16 object-contain mb-3" alt="Diskominfo">

                    <p class="text-sm font-semibold text-gray-700">
                        Portal Probolinggo
                    </p>
                </a>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endsection
