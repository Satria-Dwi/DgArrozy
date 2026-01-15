@extends('layouts.app')
@section('content')
    <!-- Hero Section -->
    <section class="text-center py-28 bg-gray-100" style="background-image: url('/img/profile.png'); background-size: cover;">
        <div class="max-w-2xl mx-auto px-4 animate-fade-in-up">
            <img src="img/logo-pin-edit.png" alt="" class="mx-auto profile-company shadow"
                style="width: 180px; border-radius:50%">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 text-[#F7941D]"
                style="text-shadow: 0 2px 6px rgba(0,0,0,0.2)">RSUD <span class="text-[#0FA36B]"
                    style="text-shadow: 0 2px 6px rgba(0,0,0,0.2)">AR ROZY</span>
            </h2>
            <p class="text-lg text-white mb-6"
                style="text-shadow:
     0 1px 3px rgba(0,0,0,0.9),
     0 4px 12px rgba(0,0,0,0.8)">Melayani Sepenuh Hati
                Inti Kesuksesan Kami</p>
            <a href="/dashboard"
                class="bg-indigo-600 text-white px-6 py-3 rounded shadow hover:bg-indigo-700 transition">Dashboard</a>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Tentang Kami</h2>
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

    <!-- Services -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <div class="text-indigo-600 text-4xl mb-4">🌐</div>
                    <h3 class="text-xl font-semibold mb-2">Web Development</h3>
                    <p class="text-gray-600">Custom websites and web apps built with modern technologies to boost your
                        online presence.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <div class="text-indigo-600 text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold mb-2">Mobile Apps</h3>
                    <p class="text-gray-600">Beautiful iOS & Android apps designed for performance, usability, and growth.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <div class="text-indigo-600 text-4xl mb-4">☁️</div>
                    <h3 class="text-xl font-semibold mb-2">Cloud Solutions</h3>
                    <p class="text-gray-600">Deploy, scale, and secure your applications with our cloud expertise.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-10">What Our Clients Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <p class="text-gray-700 italic mb-4">"AnomSite transformed our online presence. The website they built
                        exceeded our expectations!"</p>
                    <h4 class="text-gray-900 font-semibold">— Jane Doe, CEO StartupX</h4>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow">
                    <p class="text-gray-700 italic mb-4">"Professional, reliable, and creative. Highly recommended for any
                        business looking to scale digitally."</p>
                    <h4 class="text-gray-900 font-semibold">— John Smith, Founder of InnovateHub</h4>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
