<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .kop img {
            width: 100%;
        }

        .judul {
            text-align: center;
            font-size: 14px;
            margin: 6px 0 10px;
            border-bottom: 2px solid #000;
            font-weight: bold;
            padding-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header td {
            font-size: 11px;
            font-weight: normal;
            padding: 6px 4px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            background: #f6f3dc;
        }

        .item td {
            padding: 2px 4px;
            vertical-align: top;
        }

        td {
            font-size: 11px;
            line-height: 1.2;
            vertical-align: top;
        }

        .nama-obat {
            white-space: normal;
            word-break: break-word;
            padding-left: 4px;
            line-height: 1.15;
        }

        .space td {
            height: 12px;
        }

        .jumlah {
            text-align: left;
            white-space: nowrap;
        }

        .aturan {
            white-space: nowrap;
        }
    </style>
</head>

<body>

    <div class="kop">
        <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
    </div>

    <div class="judul">
        REKAP DATA PEMBERIAN OBAT RESEP
    </div>

    <table>

        <tr class="header">
            <td width="9%">No.Resep</td>
            <td width="21%">Tgl.Resep</td>
            <td width="40%">Pasien</td>
            <td width="4%"></td>
            <td width="35%">Dokter Peresep</td>
        </tr>

        @foreach ($data as $resep)
            <tr class="item">
                <td>{{ $resep['no_resep'] }}</td>
                <td>{{ $resep['tanggal'] }} {{ $resep['jam'] }}</td>
                <td style="white-space: nowrap;">
                    {{ $resep['pasien']['no_rawat'] }}
                    {{ $resep['pasien']['no_rm'] }}
                    {{ $resep['pasien']['nama'] }}
                </td>
                <td></td>
                <td>
                    {{ $resep['dokter']['nama'] }}
                </td>
            </tr>

            <tr>
                <td></td>
                <td><b>Nama Obat</b></td>
                <td style="padding:0; padding-right:4px;">
                    <table width="100%" style="border-collapse:collapse;">
                        <tr>
                            <td align="center" width="10%"><b>Jumlah</b></td>
                            <td align="center" width="2%"></td>
                            <td align="center" width="19%"><b>Harga</b></td>
                            <td align="center" width="2%"></td>
                            <td align="center" width="16%"><b>Embalase</b></td>
                            <td align="center" width="2%"></td>
                            <td align="center" width="18%"><b>Tuslah</b></td>
                            <td align="center" width="2%"></td>
                            <td align="center" width="22%"><b>Total</b></td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td><b>Aturan Pakai</b></td>
            </tr>

            {{-- NON RACIKAN --}}
            @foreach ($resep['obat'] as $obat)
                <tr>
                    <td></td>
                    <td class="nama-obat">{{ $obat['nama_brng'] }}</td>
                    <td style="padding:0;">
                        <table width="100%" style="border-collapse:collapse;">
                            <tr>
                                <td align="center" width="19%">{{ $obat['jumlah'] }}</td>
                                <td align="center" width="2%">×</td>
                                <td align="center" width="19%">{{ number_format($obat['biaya_obat'], 0, ',', '.') }}
                                </td>
                                <td align="center" width="5%">+</td>
                                <td align="center" width="18%">{{ number_format($obat['embalase'], 0, ',', '.') }}
                                </td>
                                <td align="center" width="5%">+</td>
                                <td align="center" width="24%">{{ number_format($obat['tuslah'], 0, ',', '.') }}
                                </td>
                                <td align="center" width="5%">=</td>
                                <td align="right" width="22%">
                                    <b>{{ number_format($obat['biaya_obat'] * $obat['jumlah'] + $obat['embalase'] + $obat['tuslah'], 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        </table>
                    </td>
                    {{-- <td>
                        {{ $obat['jumlah'] }}
                        x
                        {{ number_format($obat['biaya_obat'], 0, ',', '.') }}
                        +
                        {{ number_format($obat['embalase'], 0, ',', '.') }}
                        +
                        {{ number_format($obat['tuslah'], 0, ',', '.') }}
                        =
                        <b>
                            {{ number_format($obat['biaya_obat'] * $obat['jumlah'] + $obat['embalase'] + $obat['tuslah'], 0, ',', '.') }}
                        </b>
                    </td> --}}
                    <td></td>
                    <td>{{ $obat['aturan_pakai'] }}</td>
                </tr>
            @endforeach

            {{-- RACIKAN --}}
            @foreach ($resep['racikan'] as $racik)
                <tr>
                    <td></td>
                    <td colspan="3">
                        <b>Racikan {{ $racik['no_racik'] }}</b> - {{ $racik['nama_racik'] }}
                    </td>
                </tr>

                @foreach ($racik['detail'] as $detail)
                    <tr>
                        <td></td>
                        <td class="nama-obat">- {{ $detail['nama_brng'] }}</td>
                        <td style="padding:0;">
                            <table width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <td align="right" width="12%">{{ $detail['jumlah'] }}</td>
                                    <td align="center" width="5%">×</td>
                                    <td align="right" width="20%">
                                        {{ number_format($detail['biaya_obat'], 0, ',', '.') }}
                                    </td>
                                    <td align="center" width="5%">+</td>
                                    <td align="right" width="18%">
                                        {{ number_format($detail['embalase'], 0, ',', '.') }}</td>
                                    <td align="center" width="5%">+</td>
                                    <td align="right" width="18%">
                                        {{ number_format($detail['tuslah'], 0, ',', '.') }}
                                    </td>
                                    <td align="center" width="5%">=</td>
                                    <td align="right" width="22%">
                                        <b>{{ number_format($detail['biaya_obat'] * $detail['jumlah'] + $detail['embalase'] + $detail['tuslah'], 0, ',', '.') }}</b>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $racik['aturan_pakai'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td colspan="2" style="text-align:right; font-weight:bold; padding-top:4px;">
                    Total Biaya Resep =
                    {{ number_format($resep['total_resep'], 0, ',', '.') }}
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr class="space">
                <td colspan="4"></td>
            </tr>
        @endforeach

    </table>

</body>

</html>
