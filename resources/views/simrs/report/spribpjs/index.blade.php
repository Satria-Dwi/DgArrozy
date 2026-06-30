<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            size: A5 landscape;
            margin: 10px 12px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.15;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        .header {
            /* margin-bottom: 8px; */
            margin-top: 12px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 220px;
            display: block;
            padding: 20px;
        }

        .title {
            text-align: center;
            vertical-align: middle;
        }

        .title1 {
            font-size: 15px;
            font-weight: normal;
            margin: 0;
            padding: 0;
        }

        .title2 {
            font-size: 15px;
            margin-top: 2px;
        }

        .left {
            width: 100%;
        }

        .info td {
            padding: 2px 0;
            font-size: 16px;
        }

        .label {
            width: 150px;
        }

        .colon {
            width: 10px;
            text-align: center;
        }

        .barcode-box {
            text-align: center;
            margin-bottom: 10px;
        }

        .barcode-box img {
            width: 230px;
            height: 35px;
        }

        .ttd {
            text-align: center;
        }

        .qr-box {
            margin: 5px auto;
        }

        .footer-note {
            font-size: 9px;
            font-style: italic;
            line-height: 13px;
        }

        .footer-print {
            margin-top: 5px;
            font-size: 9px;
        }
    </style>

</head>

<body>
    <table class="header" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <!-- Logo -->
            <td width="15%" style="vertical-align:middle;">
                <img src="{{ $logo }}" class="logo">
            </td>

            <!-- Judul -->
            <td width="40%" class="title" style="text-align:left; vertical-align:middle;">
                <div class="title1">
                    SURAT PERINTAH RAWAT INAP
                </div>

                <div class="title2">
                    RSUD AR ROZY KOTA PROBOLINGGO
                </div>
            </td>

            <!-- Informasi SEP -->
            <td width="50%" style="text-align:left; font-size:14px;">
                <div style="display:inline-block; text-align:left;">
                    <table cellspacing="0" cellpadding="2">
                        <tr>
                            <td>No. {{ $data->no_surat }}</td>
                        </tr>
                        <tr>
                            <td>Tgl. {{ \Carbon\Carbon::parse($data->tgl_surat)->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-bottom:20px; border-collapse:collapse; padding-left:24px;">
        <tr>
            <td width="50%" style="vertical-align:top; font-size:16px;">
                <table width="100%" style="font-size:16px;">
                    <tr>
                        <td style="width:90px;">Kepada Yth.</td>
                        <td style="width:10px;"></td>
                        <td>{{ $data->nm_dokter_bpjs }}</td>
                    </tr>
                </table>
            </td>

            <td width="50%" align="center" style="vertical-align:top;">
                <img src="{{ $barcode }}" style="width:230px; height:25px; display:block;">
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-bottom:10px; border-collapse:collapse; padding-left:24px;">
        <tr>
            <td width="70%" style="font-size:16px;">
                Mohon Pemeriksaan dan Penanganan Lebih Lanjut :
            </td>
        </tr>
    </table>

    {{-- CONTENT --}}
    <table class="content" style="font-size:16px; padding-left:24px;">

        <tr>
            <td class="left">

                <table class="info" style="font-size:16px;">

                    <tr>
                        <td class="label">No Kartu</td>
                        <td class="colon">:</td>
                        <td>{{ $data->no_kartu }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pasien</td>
                        <td>:</td>
                        <td>{{ $data->nm_pasien }}</td>
                    </tr>
                    <tr>
                        <td>Tgl. Lahir</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data->tgl_lahir)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Diagnosa Awal</td>
                        <td>:</td>
                        <td>{{ $data->diagnosa }}</td>
                    </tr>
                    <tr>
                        <td>Tgl. Entri</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data->tgl_surat)->format('d F Y') }}</td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>
    <table width="100%" style="margin-bottom:10px; border-collapse:collapse; margin-top:6px; padding-left:24px;">
        <tr>
            <td width="70%" style="font-size:16px;">
                Demikian atas bantuannya diucapkan banyak terima kasih
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top:15px; padding-left:24px;">

        <tr>
            <td width="60%" valign="bottom">

                <div class="footer-print" style="padding-left: 20px;">
                    Tgl. Cetak {{ now()->format('d/m/Y H:i:s') }}
                </div>

            </td>
            <td width="40%" align="center" valign="top">

                <div class="ttd">

                    <div style="margin-bottom:5px; font-size:14px;">
                        Mengetahi,
                    </div>

                    <div class="qr-box">
                        <img src="{{ $qr_api }}" width="85" height="85">
                    </div>
                    <div style="margin-top:5px; font-size:15px;">
                        <span
                            style="display:inline-block; border-top:1px solid #000; padding-top:3px; min-width:220px;">
                            {{ $data->nm_dokter_bpjs }}
                        </span>
                    </div>

                </div>

            </td>

        </tr>

    </table>

</body>

</html>
