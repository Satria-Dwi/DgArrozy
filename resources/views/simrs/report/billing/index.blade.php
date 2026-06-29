<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Tahoma, Geneva, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.15;
        }

        .kop img {
            width: 100%;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .box {
            margin-top: 10px;
        }

        .row {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        .col {
            flex: 1;
        }

        .label {
            display: inline-block;
            width: 130px;
            font-weight: bold;
        }

        .line {
            border-bottom: 1px solid #000;
            margin: 8px 0;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }

        .isi {
            margin-left: 10px;
            text-align: justify;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: right;
        }

        .qr {
            width: 80px;
            height: 80px;
        }

        .billing {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .billing th {
            border: 1.5px solid #000;
            background: #d9d9d9;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            font-size: 11px;
        }

        .billing td {
            vertical-align: top;
            padding: 2px 4px;
        }

        .billing td:nth-child(1) {
            white-space: nowrap;
        }

        .billing td:nth-child(2) {
            word-break: break-word;
        }

        .billing thead tr {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        table {
            border-collapse: collapse;
        }

        td {
            font-family: Tahoma, Geneva, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 1px 2px;
            vertical-align: top;
        }

        b,
        strong {
            font-weight: bold;
        }

        h3 {
            font-family: Tahoma, Geneva, sans-serif;
            font-size: 15px;
            font-weight: normal;
            letter-spacing: .3px;
        }
    </style>

</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop">
        <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
    </div>

    <hr>

    <h3 style="text-align:center">
        RINCIAN BILLING PASIEN
    </h3>

    <!-- IDENTITAS PASIEN -->
    {{-- <table width="100%" style="margin-top:8px; border:1px solid #000;" cellpadding="3">

        <tr style="background:#efefef;">
            <td colspan="4" style="font-weight:bold;">
                IDENTITAS PASIEN
            </td>
        </tr>

        <tr>

            <!-- KIRI -->
            <td width="50%" valign="top">

                <table width="100%">

                    <tr>
                        <td width="95">Nama Pasien</td>
                        <td width="10">:</td>
                        <td>{{ $pasien->nm_pasien }}</td>
                    </tr>

                    <tr>
                        <td>No. RM</td>
                        <td>:</td>
                        <td>{{ $pasien->no_rkm_medis }}</td>
                    </tr>

                    <tr>
                        <td>No. Rawat</td>
                        <td>:</td>
                        <td>{{ $pasien->no_rawat }}</td>
                    </tr>

                    <tr>
                        <td valign="top">Alamat</td>
                        <td valign="top">:</td>
                        <td>{{ $pasien->alamat_lengkap }}</td>
                    </tr>

                </table>

            </td>

            <!-- KANAN -->
            <td width="50%" valign="top">

                <table width="100%">

                    <tr>
                        <td width="95">Tanggal Masuk</td>
                        <td width="10">:</td>
                        <td>{{ $pasien->tgl_registrasi }}</td>
                    </tr>

                    <tr>
                        <td>Jam Masuk</td>
                        <td>:</td>
                        <td>{{ $pasien->jam_reg }}</td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{ $pasien->status_lanjut }}</td>
                    </tr>

                    <tr>
                        <td>Dokter</td>
                        <td>:</td>
                        <td>{{ $pasien->nm_dokter }}</td>
                    </tr>

                    <tr>
                        <td>Cara Bayar</td>
                        <td>:</td>
                        <td>{{ $pasien->png_jawab }}</td>
                    </tr>

                </table>

            </td>

        </tr>

    </table> --}}

    {{-- <table width="100%" class="billing">

        <thead>
            <tr style="background-color:#d9d9d9;">

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="12%">
                    Kategori
                </th>

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="46%">
                    Uraian
                </th>

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="12%">
                    Harga
                </th>

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="8%">
                    Qty
                </th>

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="10%">
                    Tambahan
                </th>

                <th style="border:1.5px solid #000; padding:6px; font-weight:bold; font-size:11px; text-align:center;"
                    width="12%">
                    Total
                </th>

            </tr>
        </thead>

        <tbody>

            @foreach ($billing as $row)
                @if (Str::startsWith($row->status, 'Ttl'))
                    <tr style="font-weight:bold;background:#f5f5f5;">

                        <td colspan="5" align="right" style="padding:6px;">
                            {{ trim(explode(':', $row->nm_perawatan)[0]) }}
                        </td>

                        <td align="right" style="padding:6px;">
                            {{ number_format((int) preg_replace('/\D/', '', explode(':', $row->nm_perawatan)[1]), 0, ',', '.') }}
                        </td>

                    </tr>
                @elseif($row->biaya == 0 && $row->jumlah == 0 && $row->totalbiaya == 0)
                    <tr style="background:#efefef;">

                        <td colspan="6" style="font-weight:bold;padding:5px;">
                            {{ trim($row->no) }}
                            {{ trim($row->nm_perawatan) }}
                        </td>

                    </tr>
                @else
                    <tr>

                        <td>
                            {{ trim($row->no) }}
                        </td>

                        <td>
                            {{ trim($row->nm_perawatan) }}
                        </td>

                        <td align="right">
                            {{ number_format($row->biaya, 0, ',', '.') }}
                        </td>

                        <td align="center">
                            {{ $row->jumlah }}
                        </td>

                        <td align="right">
                            {{ $row->tambahan ? number_format($row->tambahan, 0, ',', '.') : '-' }}
                        </td>

                        <td align="right">
                            {{ number_format($row->totalbiaya, 0, ',', '.') }}
                        </td>

                    </tr>
                @endif
            @endforeach

        </tbody>
        <tfoot>
            <tr style="font-weight:bold;background:#d9d9d9;">
                <td colspan="5" align="right">TOTAL TAGIHAN</td>
                <td align="right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>

    </table> --}}

    {{-- <br><br> --}}

    {{-- <table>

        <tr>

            <td width="60%"></td>

            <td width="40%" style="text-align:center">

                {{ date('d-m-Y') }}

                <br><br><br><br><br>

                <u>{{ $pasien->nm_dokter }}</u>

            </td>

        </tr>

    </table> --}}

    <table width="100%" style="margin-top:10px;font-size:10px;border-collapse:collapse;table-layout:fixed;">

        <colgroup>
            <col style="width:18%">
            <col style="width:44%">
            <col style="width:2%">
            <col style="width:12%">
            <col style="width:6%">
            <col style="width:8%">
            <col style="width:10%">
        </colgroup>
        <tbody>

            @foreach ($billing as $row)
                {{-- BARIS TOTAL --}}
                @if (Str::startsWith($row->status, 'Ttl'))
                    @php
                        $parts = explode(':', $row->nm_perawatan, 2);
                        $judul = trim($parts[0] ?? '');
                        $nominal = isset($parts[1]) ? (int) preg_replace('/\D/', '', $parts[1]) : $row->totalbiaya;
                    @endphp

                    {{-- <tr>
                        <td colspan="7" style="border-top:1px solid #000;"></td>
                    </tr> --}}

                    <tr>
                        <td colspan="6" align="right"
                            style="padding:4px 6px;border-top:1px solid #000;font-weight:bold;">
                            {{ $judul }}
                        </td>

                        <td align="right"
                            style="padding:4px 2px;border-top:1px solid #000;font-weight:bold;white-space:nowrap;">
                            {{ number_format($nominal, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- HEADER --}}
                @elseif($row->biaya == 0 && $row->jumlah == 0 && $row->totalbiaya == 0)
                    <tr>
                        <td colspan="6" style="padding-top:8px;font-weight:bold;">
                            {{ trim($row->no) }}
                            {{ trim($row->nm_perawatan) }}
                        </td>
                    </tr>

                    {{-- DETAIL --}}
                @else
                    <tr>

                        <td width="18%" valign="top">
                            &nbsp;
                        </td>

                        <td width="44%" valign="top" style="padding-left:10px;">
                            {{ trim($row->nm_perawatan) }}
                        </td>

                        <td width="2%" align="center">
                            {{ $row->pemisah }}
                        </td>

                        <td width="12%" align="right">
                            {{ $row->biaya > 0 ? number_format($row->biaya, 0, ',', '.') : '' }}
                        </td>

                        <td width="6%" align="center">
                            {{ $row->jumlah ?: '' }}
                        </td>

                        <td width="8%" align="right">
                            {{ $row->tambahan ? number_format($row->tambahan, 0, ',', '.') : '' }}
                        </td>

                        <td width="10%" align="right" valign="top" style="padding-right:2px;white-space:nowrap;">
                            {{ $row->totalbiaya > 0 ? number_format($row->totalbiaya, 0, ',', '.') : '' }}
                        </td>

                    </tr>
                @endif
            @endforeach

            {{-- <tr>
                <td colspan="6" style="border-top:2px solid #000;"></td>
                <td style="border-top:2px solid #000;"></td>
            </tr> --}}

            <tr style="font-size:11px;font-weight:bold;">

                <td colspan="6" align="right" style="padding:6px 6px;">
                    TOTAL TAGIHAN
                </td>

                <td align="right" style="padding:6px 2px;white-space:nowrap;">
                    {{ number_format($grandTotal, 0, ',', '.') }}
                </td>

            </tr>

        </tbody>

    </table>

    <br><br>

    <style>
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .signature-table td {
            vertical-align: top;
            text-align: center;
            font-size: 12px;
            /* line-height: 18px; */
            font-family: Tahoma, Geneva, sans-serif;
            font-size: 12px;
            line-height: 17px;
        }

        .signature-name {
            margin-top: 5px;
            font-weight: bold;
        }

        .signature-note {
            font-size: 11px;
            padding-top: 20px;
        }
    </style>
    <table class="signature-table" width="100%" style="margin-top:25px; border-collapse:collapse;">
        <tr>

            {{-- TTD KIRI --}}
            <td width="50%" align="center" valign="top">

                Mengetahui,<br>
                a/n Direktur<br>
                Kabid Umum &amp; Keuangan

                <br><br><br><br><br>

                <b>( .................................... )</b>

            </td>

            {{-- TTD KANAN --}}
            <td width="50%" align="center" valign="top">

                {{ $setting->kabupaten }},
                {{ \Carbon\Carbon::parse($tgl_bayar)->format('d-m-Y') }}
                {{ date('His') }}

                <br>

                {{ $jabatan ?? 'Kasir' }}

                <br>

                @if ($qr_api)
                    <img src="{{ $qr_api }}" width="80" height="80">
                @endif

                <br>

                <b>( {{ strtoupper($nama_petugas) }} )</b>

            </td>

        </tr>

        {{-- CATATAN --}}
        <tr>
            <td colspan="2" style="padding-top:25px; font-size:10px;">
                <b>NB :</b>
                Mohon maaf apabila ada tagihan yang belum tertagihkan dalam perincian ini akan
                ditagihkan kemudian, dan apabila berlebih akan dikembalikan.
            </td>
        </tr>
    </table>

</body>

</html>
