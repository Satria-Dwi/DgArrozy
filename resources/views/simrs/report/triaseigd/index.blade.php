<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }

        .kop img {
            width: 100%;
        }

        .judul {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 15px;
            text-transform: uppercase;
        }

        .box {
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .box-title {
            background: #e5e5e5;
            font-weight: bold;
            padding: 6px 10px;
            border-bottom: 1px solid #000;
            font-size: 12px;
        }

        .box-body {
            padding: 8px 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .label {
            width: 170px;
            font-weight: bold;
        }

        .separator {
            width: 10px;
        }

        .line {
            border-top: 1px dashed #999;
            margin: 8px 0;
        }

        .section {
            margin-top: 8px;
        }

        .footer {
            margin-top: 30px;
        }

        .qr {
            width: 70px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            table-layout: fixed;
            font-size: 11px;
        }

        .main-table td,
        .main-table th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .bg-triase {
            background: {{ $config['warna_bg'] }};
            color: {{ $config['warna_txt'] }};
            font-weight: bold;
            text-align: center;
        }

        .bg-section {
            background: #E8E8AD;
            font-weight: bold;
            text-align: center;
        }
    </style>

</head>

<body>

    <div class="kop">
        <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
    </div>

    <hr>

    <h3 style="text-align:center">
        TRIASE PASIEN GAWAT DARURAT
    </h3>

    <table class="main-table">

        {{-- <tr>

            <td class="bg-triase">
                TRIASE PASIEN GAWAT DARURAT
            </td>

        </tr> --}}

        <tr>

            <td class="text-center fs-10">
                Triase dilakukan segera setelah pasien datang dan sebelum pasien / keluarga mendaftar di TPP IGD
            </td>

        </tr>

    </table>

    <table class="main-table">

        <tr>

            <td width="50%">
                Tanggal Kunjungan :
                {{ \Carbon\Carbon::parse($tanggal_triase)->format('d-m-Y') }}
            </td>

            <td width="50%">
                Pukul :
                {{ \Carbon\Carbon::parse($tanggal_triase)->format('H:i:s') }}
            </td>

        </tr>

    </table>

    <table class="main-table">

        <tr>

            <td width="30%">
                Cara Datang
            </td>

            <td width="70%">
                {{ $pasien['cara_masuk'] ?? '-' }}
            </td>

        </tr>

        <tr>

            <td>
                Macam Kasus
            </td>

            <td>
                {{ $pasien['macam_kasus'] ?? '-' }}
            </td>

        </tr>

    </table>

    <table class="main-table">

        <tr class="bg-section">

            <td width="30%">
                KETERANGAN
            </td>

            <td width="70%">
                {{ $config['sub_judul'] }}
            </td>

        </tr>

        <tr>

            <td height="60">
                <b>ANAMNESA SINGKAT</b>
            </td>

            <td>
                {!! nl2br($pasien['keluhan'] ?? '-') !!}
            </td>

        </tr>

        <tr>

            <td>
                <b>TANDA VITAL</b>
            </td>

            <td>

                Suhu :
                {{ $pasien['suhu'] ?? '-' }}

                &nbsp;&nbsp;|

                Nyeri :
                {{ $pasien['nyeri'] ?? '-' }}

                &nbsp;&nbsp;|

                TD :
                {{ $pasien['tekanan_darah'] ?? '-' }}

                &nbsp;&nbsp;|

                Nadi :
                {{ $pasien['nadi'] ?? '-' }}

                &nbsp;&nbsp;|

                Saturasi :
                {{ $pasien['saturasi_o2'] ?? '-' }}

                &nbsp;&nbsp;|

                Respirasi :
                {{ $pasien['pernapasan'] ?? '-' }}

            </td>

        </tr>

    </table>

    <table class="main-table">

        <tr class="bg-section">

            <td width="30%">
                PEMERIKSAAN
            </td>

            <td width="70%" class="bg-triase">
                URGENSI
            </td>

        </tr>

        @forelse($checklist as $item)
            <tr>

                <td>
                    {{ strtoupper($item->nama_pemeriksaan) }}
                </td>

                <td style="background:{{ $config['warna_bg'] }};
                color:{{ $config['warna_txt'] }}">

                    {{ $item->hasil }}

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="2" align="center">
                    Tidak ada data checklist
                </td>

            </tr>
        @endforelse

        <tr>

            <td>
                PLAN
            </td>

            <td style="background:{{ $config['warna_bg'] }};
            color:{{ $config['warna_txt'] }}">

                {{ $triase->plan ?? '-' }}

            </td>

        </tr>

    </table>

    <table class="main-table">

        <tr class="bg-section">

            <td width="30%">
                &nbsp;
            </td>

            <td width="70%">
                Petugas Triase
            </td>

        </tr>

        <tr>

            <td>
                Tanggal & Jam
            </td>

            <td>
                {{ \Carbon\Carbon::parse($tanggal_triase)->format('d-m-Y H:i:s') }} WIB
            </td>

        </tr>

        <tr>

            <td>
                Catatan
            </td>

            <td>
                {!! nl2br($triase->catatan ?? '-') !!}
            </td>

        </tr>

        <tr>

            <td>
                Dokter / Petugas Jaga IGD
            </td>

            <td style="height:70px;">

                <br><br>

                <strong>
                    {{ $perawat['nama'] }}
                </strong>

            </td>

        </tr>

    </table>
    <table width="100%" style="border-collapse:collapse; margin-top:10px;">
        <tr>
            <td width="55%"></td>

            <td width="45%" style="text-align:center; vertical-align:top;">

                <div style="font-size:10px; font-weight:bold;">
                    Ditandatangani Secara Elektronik
                </div>

                <div style="font-size:9px;">
                    Petugas Triase IGD
                </div>

                <div style="font-size:9px; margin-top:3px;">
                    {{ \Carbon\Carbon::parse($tanggal_triase)->translatedFormat('d F Y H:i') }} WIB
                </div>

                @if (!empty($qrCode))
                    <div style="margin:8px 0;">
                        <img src="{{ $qrCode }}" width="80">
                    </div>
                @endif

                <div style="border-top:1px solid #000; width:180px; margin:0 auto; padding-top:4px;">
                    <div style="font-size:11px; font-weight:bold;">
                        {{ strtoupper($perawat['nama']) }}
                    </div>

                    <div style="font-size:9px;">
                        Petugas Triase
                    </div>
                </div>

            </td>
        </tr>
    </table>

</body>

</html>
