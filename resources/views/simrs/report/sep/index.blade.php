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
            font-size: 18px;
            font-weight: normal;
            margin: 0;
            padding: 0;
        }

        .title2 {
            font-size: 18px;
            margin-top: 2px;
        }

        .left {
            width: 56%;
            padding-left: 20px;
            padding-right: 12px;
        }

        .right {
            width: 44%;
        }

        .info td {
            padding: 1px 0;
            font-size: 14px;
        }

        .label {
            width: 105px;
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
    <table class="header">

        <tr>

            <td width="34%" style="vertical-align:middle;">
                <img src="{{ $logo }}" class="logo">
            </td>

            <td width="66%" class="title" style="vertical-align:middle;">

                <div class="title1">
                    SURAT ELEGIBILITAS PESERTA
                </div>

                <div class="title2">
                    RSUD AR ROZY KOTA PROBOLINGGO
                </div>

            </td>

        </tr>

    </table>

    <table width="100%" style="margin-bottom:10px;">
        <tr>
            <td width="50%"></td>

            <td width="50%" align="center">
                <img src="{{ $barcode }}" style="width:230px;height:25px;">
            </td>
        </tr>
    </table>

    {{-- CONTENT --}}
    <table class="content">

        <tr>

            {{-- KOLOM KIRI --}}
            <td class="left">

                <table class="info">

                    <tr>
                        <td class="label">No. SEP</td>
                        <td class="colon">:</td>
                        <td>{{ $data->no_sep }}</td>
                    </tr>

                    <tr>
                        <td>Tgl. SEP</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data->tglsep)->format('d/m/Y') }}</td>
                    </tr>

                    <tr>
                        <td>No. Kartu</td>
                        <td>:</td>
                        <td>{{ $data->kartu_mr }}</td>
                    </tr>

                    <tr>
                        <td>Nama Peserta</td>
                        <td>:</td>
                        <td>{{ $data->nama_pasien }}</td>
                    </tr>

                    <tr>
                        <td>Tgl. Lahir</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d/m/Y') }}</td>
                    </tr>

                    <tr>
                        <td>No.Telepon</td>
                        <td>:</td>
                        <td>{{ $data->notelep }}</td>
                    </tr>

                    <tr>
                        <td>Sub/Spesialis</td>
                        <td>:</td>
                        <td>{{ $data->nmpolitujuan }}</td>
                    </tr>

                    <tr>
                        <td>Dokter</td>
                        <td>:</td>
                        <td>{{ $data->nmdpdjp }}</td>
                    </tr>

                    <tr>
                        <td>Faskes Perujuk</td>
                        <td>:</td>
                        <td>{{ $data->nmppkrujukan }}</td>
                    </tr>

                    <tr>
                        <td>Diagnosa Awal</td>
                        <td>:</td>
                        <td>{{ $data->nmdiagnosaawal }}</td>
                    </tr>

                    <tr>
                        <td>Catatan</td>
                        <td>:</td>
                        <td>{{ $data->catatan ?: '-' }}</td>
                    </tr>

                </table>

            </td>

            {{-- KOLOM KANAN --}}
            <td class="right">

                <table class="info">

                    <tr>
                        <td class="label">No. Rawat</td>
                        <td class="colon">:</td>
                        <td>{{ $data->no_rawat }}</td>
                    </tr>

                    <tr>
                        <td>No. Reg</td>
                        <td>:</td>
                        <td>{{ $data->no_reg }}</td>
                    </tr>

                    <tr>
                        <td>Peserta</td>
                        <td>:</td>
                        <td>{{ $data->peserta }}</td>
                    </tr>

                    <tr>
                        <td>Jns. Rawat</td>
                        <td>:</td>
                        <td>{{ $data->pelayanan }}</td>
                    </tr>

                    <tr>
                        <td>Jns.Kunjungan</td>
                        <td>:</td>
                        <td>
                            {{ $data->tujuan_kunjungan }}<br>
                            {{ $data->flag_prosedur }}
                        </td>
                    </tr>

                    <tr>
                        <td>Poli Perujuk</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td>Kls. Hak</td>
                        <td>:</td>
                        <td>{{ $data->kelas_rawat }}</td>
                    </tr>

                    <tr>
                        <td>Kls. Rawat</td>
                        <td>:</td>
                        <td>{{ $data->kelas_naik }}</td>
                    </tr>

                    <tr>
                        <td>Penjamin</td>
                        <td>:</td>
                        <td>{{ $data->penjamin_lakalantas }}</td>
                    </tr>

                </table>

            </td>

        </tr>

    </table>
    <table width="100%" style="margin-top:15px;">

        <tr>

            {{-- FOOTER --}}
            <td width="60%" valign="bottom">

                <div class="footer-note" style="padding-left: 20px;">
                    *Saya Menyetujui BPJS Kesehatan menggunakan informasi Medis Pasien jika diperlukan.<br>
                    *SEP bukan sebagai bukti penjaminan peserta
                </div>

                <div class="footer-print" style="padding-left: 20px;">
                    Cetakan ke 1 &nbsp;&nbsp;
                    {{ now()->format('d/m/Y H:i:s') }}
                </div>

            </td>

            {{-- QR --}}
            <td width="40%" align="center" valign="top">

                <div class="ttd">

                    <div style="margin-bottom:5px; font-size:14px;">
                        Pasien/Keluarga Pasien
                    </div>

                    <div class="qr-box">
                        <img src="{{ $qr_api }}" width="85" height="85">
                    </div>

                    <div style="margin-top:5px; font-size:15px;">
                        {{ $data->nama_pasien }}
                    </div>

                </div>

            </td>

        </tr>

    </table>

</body>

</html>
