<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
        }

        .kop img {
            width: 100%;
        }

        .judul {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 8px 0 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ===========================
           IDENTITAS PASIEN
        =========================== */

        .identitas {
            margin-bottom: 12px;
        }

        .identitas td {
            padding: 2px 3px;
            font-size: 11px;
        }

        .identitas .label {
            width: 95px;
            font-weight: bold;
        }

        .identitas .titik {
            width: 10px;
            text-align: center;
        }

        /* ===========================
           TABEL RESEP
        =========================== */

        .data thead th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 4px;
            text-align: left;
            font-weight: bold;
        }

        .data thead th.right {
            text-align: right;
        }

        .data thead th.center {
            text-align: center;
        }

        .data tbody td {
            border-bottom: 1px solid #999;
            padding: 4px;
            vertical-align: top;
        }

        .obat {
            white-space: normal;
            word-break: break-word;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* ===========================
           FOOTER
        =========================== */

        .footer {
            margin-top: 8px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Kop Surat -->
    <div class="kop">
        <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
    </div>

    <!-- Judul -->
    <div class="judul">
        RESEP PULANG
    </div>

    <!-- Identitas Pasien -->
    <table class="identitas">
        <tr>
            <td class="label">Nama Pasien</td>
            <td class="titik">:</td>
            <td>{{ $resep['pasien']['no_rm'] }} {{ $resep['pasien']['nama'] }}</td>
        </tr>
        <tr>
            <td class="label">No. Rawat</td>
            <td class="titik">:</td>
            <td>{{ $resep['pasien']['no_rawat'] }}</td>
        </tr>
    </table>

    <!-- Data Obat -->
    <table class="data">
        <thead>
            <tr>
                <th width="10%">Tgl.Resep</th>
                <th width="8%">Jam</th>
                <th width="30%">Obat</th>
                <th width="6%" class="center">Jml</th>
                <th width="10%" class="right">Harga</th>
                <th width="10%" class="right">Total</th>
                <th width="2%"></th>
                <th width="25%">Dosis</th>
            </tr>
        </thead>

        <tbody>
            @forelse($resep['obat'] as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>

                    <td class="obat">
                        {{ $item->obat }}
                    </td>

                    <td class="center">
                        {{ number_format($item->jml_barang, 1) }}
                    </td>

                    <td class="right">
                        Rp. {{ number_format($item->harga, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        Rp. {{ number_format($item->total, 0, ',', '.') }}
                    </td>
                    <td></td>

                    <td>
                        {{ $item->dosis }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">
                        Tidak ada data resep.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Total Resep : {{ count($resep['obat']) }}
    </div>

</body>

</html>
