@extends('layouts.app')
@section('content')
    <div class="px-4">
        <div class="py-2">
            <div class="kpi-container">
                <div class="kpi-card green">
                    <div class="kpi-header">
                        <div id="rt-hari" class="rt-hari"></div>
                        <i class="fas fa-stopwatch fa-2x" style="color:#10b981;"></i>
                    </div>
                    <div class="clock">
                        <div id="rt-tanggal" class="rt-tanggal"></div>
                        <div id="rt-jam" class="rt-jam"></div>
                    </div>
                </div>
                <div class="kpi-card orange">
                    <div class="kpi-header">
                        <span class="kpi-title">Rawat Inap Saat Ini</span>
                        <i class="fas fa-bed kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="ranap">-</div>
                    <div class="kpi-trend">Pasien sedang dirawat</div>
                </div>
                <div class="kpi-card blue">
                    <div class="kpi-header">
                        <span class="kpi-title">IGD Hari Ini</span>
                        <i class="fas fa-ambulance kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="igd">-</div>
                    <div class="kpi-trend">Total Kunjungan IGD</div>
                </div>
                <div class="kpi-card red">
                    <div class="kpi-header">
                        <span class="kpi-title">Poliklinik Hari ini</span>
                        <i class="fas fa-clinic-medical kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="poli">-</div>
                    <div class="kpi-trend">Total Kunjungan Poli</div>
                </div>
                <div class="kpi-card green">
                    <div class="kpi-header">
                        <span class="kpi-title">Operasi Hari Ini</span>
                        <i class="fas fa-procedures kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="operasi">-</div>
                    <div class="kpi-trend">Operasi Terjadwal</div>
                </div>
                <div class="kpi-card purple">
                    <div class="kpi-header">
                        <span class="kpi-title">Bayi Lahir Hari ini</span>
                        <i class="fas fa-baby kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="lahir">-</div>
                    <div class="kpi-trend">Kelahiran Hari Ini</div>
                </div>
                <div class="kpi-card teal">
                    <div class="kpi-header">
                        <span class="kpi-title">Total Pasien Terdaftar</span>
                        <i class="fas fa-users kpi-icon"></i>
                    </div>
                    <div class="kpi-value" id="pasien">-</div>
                    <div class="kpi-trend">Rekam Medis Tersedia</div>
                </div>
            </div>
        </div>
        <div class="kpi-head">
            <div class="kpi-container">
                <div class="kpi-card-line crypto">
                    <div class="kpi-header-line">
                        <span class="kpi-title-line">Trend 10 Penyakit Bulan Ini</span>
                        <i class="fas fa-chart-line kpi-icon-line"></i>
                    </div>
                    <div class="chart-wrapper-line">
                        <canvas id="chartPenyakitBulanIni"></canvas>
                    </div>
                </div>
                <div class="kpi-container">
                    <div class="kpi-card green">
                        <div class="kpi-header">
                            <span class="kpi-title">IKM TW1 2026</span>
                            <i class="fas fa-bed kpi-icon"></i>
                        </div>
                        <div class="kpi-value">84,42</div>
                        <div class="kpi-trend">(Baik)</div>
                    </div>
                    <div class="kpi-card orange">
                        <div class="kpi-header">
                            <span class="kpi-title">Indeks Mutu</span>
                            <i class="fas fa-bed kpi-icon"></i>
                        </div>
                        <div class="kpi-value">---</div>
                        <div class="kpi-trend">(Baik)</div>
                    </div>
                    <div class="kpi-card blue">
                        <div class="kpi-header">
                            <span class="kpi-title">Rata2 Lama Layanan</span>
                            <i class="fas fa-bed kpi-icon"></i>
                        </div>
                        <div class="kpi-value">---</div>
                        <div class="kpi-trend">Menit</div>
                    </div>
                    <div class="kpi-card purple">
                        <div class="kpi-header">
                            <span class="kpi-title">Google Review</span>
                            <i class="fas fa-bed kpi-icon"></i>
                        </div>
                        <div class="kpi-value">4.6</div>
                        <div class="kpi-trend">⭐⭐⭐⭐⭐</div>
                    </div>
                    <div class="kpi-card purple">
                        
                    </div>
                    <div class="kpi-card purple">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="kpi-head">
            <div class="kpi-container">
                <div class="kpi-card purple">
                    <div class="slider-wrapper">
                        <div class="slider">
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts"> Trend Kunjungan Pasien Rawat Jalan dan Rawat Inap (7
                                        Hari
                                        Terakhir)</span>
                                    <i class="fas fa-chart-bar kpi-icon"></i>
                                </div>
                                <div>
                                    <canvas id="chartHarian"></canvas>
                                </div>
                            </div>
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts">Trend Kunjungan Hari Ini
                                        Poliklinik({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})
                                    </span>
                                    <i class="fas fa-chart-bar kpi-icon"></i>
                                </div>
                                <div>
                                    <canvas id="chartPolihari"></canvas>
                                </div>
                            </div>
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts">
                                        Distribusi Tempat Tidur
                                    </span>
                                    <i class="fas fa-chart-pie kpi-icon"></i>
                                </div>
                                <div class="chart-container">
                                    <canvas id="chartStatusKamar"></canvas>
                                </div>
                            </div>
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts">
                                        Distribusi Penjamin/Asuransi
                                    </span>
                                    <i class="fas fa-chart-bar kpi-icon"></i>
                                </div>
                                <div>
                                    <canvas id="penjaminChart"></canvas>
                                </div>
                            </div>
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts">
                                        Distribusi Jenis Kelamin Pasien
                                    </span>
                                    <i class="fas fa-chart-pie kpi-icon"></i>
                                </div>
                                <div class="chart-container">
                                    <canvas id="chartJK"></canvas>
                                </div>
                            </div>
                            <div class="slide">
                                <div class="kpi-header">
                                    <span class="kpi-title-charts">
                                        Trend Pasien per Tahun
                                    </span>
                                    <i class="fas fa-chart-pie kpi-icon"></i>
                                </div>
                                <div class="chart-container">
                                    <canvas id="chartTahunPasien"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kpi-card teal">
                    <img src="img/profile.png" class="kpi-img" alt="profile">
                </div>
            </div>
        </div>
        <div class="kpi-head">
            <div class="kpi-container">
                <div class="kpi-card teal">
                    <!-- ===== TEMPAT TIDUR PER BANGSAL ===== -->
                    <div class="kpi-header">
                        <span class="kpi-title-table">Detail Tempat Tidur per Hari Ini
                            ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})</span>
                        <i class="fas fa-table kpi-icon"></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Bangsal</th>
                                    <th class="px-4 py-2 text-center">Jumlah Bed</th>
                                    <th class="px-4 py-2 text-center">Bed Terisi</th>
                                    <th class="px-4 py-2 text-center">Bed Kosong</th>
                                    <th class="px-4 py-2 text-center">Persentase BOR</th>
                                </tr>
                            </thead>
                            <tbody id="tableTempatTidur" class="divide-y divide-gray-100">
                                <!-- Akan diisi JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="kpi-card red">

                </div> --}}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
