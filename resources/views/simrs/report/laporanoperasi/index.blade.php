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
            font-weight: bold;
            font-style: italic;
            font-size: 16px;
            margin: 4px 0 4px 0;
        }

        .identitas,
        .detail-operasi {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
            font-size: 11px;
        }

        .identitas {
            border-top: 1px solid #000;
        }

        /* .detail-operasi {
            border-bottom: 1px solid #000;
        } */

        .identitas td,
        .detail-operasi td {
            padding: 2px;
            vertical-align: top;
        }

        .label {
            width: 90px;
            font-weight: bold;
            white-space: nowrap;
        }

        .titik {
            width: 8px;
            text-align: center;
        }

        .section {
            margin-top: 6px;
        }

        .section-title {
            background: #d9d9d9;
            border: 1px solid #555;
            font-weight: bold;
            text-align: center;
            padding: 3px;
        }

        .border {
            border: 1px solid #555;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .post-op {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .post-op th {
            background: #d9d9d9;
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            font-weight: bold;
        }

        .post-op td {
            vertical-align: top;
            padding: 4px;
        }

        .left-area {
            width: 75%;
            border-right: 1px solid #000;
        }

        .right-area {
            width: 25%;
            text-align: center;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px;
            vertical-align: top;
        }

        .label {
            width: 110px;
        }

        .colon {
            width: 8px;
            text-align: center;
        }

        .value {
            font-style: italic;
        }

        .section-title {
            background: #d9d9d9;
            padding: 2px 4px;
            margin-top: 4px;
        }

        .section-value {
            padding: 3px 4px;
            font-style: italic;
            min-height: 16px;
        }

        .right-title {
            margin-top: 18px;
            margin-bottom: 6px;
        }

        .right-value {
            font-style: italic;
            margin-bottom: 18px;
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
        LAPORAN OPERASI
    </div>

    <table class="identitas">
        <tr>
            <td width="60%" valign="top">
                <table width="100%">
                    <tr>
                        <td class="label">Nama Pasien</td>
                        <td class="titik">:</td>
                        <td>{{ $data->nm_pasien }}</td>
                    </tr>
                    <tr>
                        <td class="label">Umur</td>
                        <td class="titik">:</td>
                        <td>{{ $data->umurdaftar }} {{ $data->sttsumur }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tgl Lahir</td>
                        <td class="titik">:</td>
                        <td>{{ date('d-m-Y', strtotime($data->tgl_lahir)) }}</td>
                    </tr>
                </table>
            </td>

            <td width="40%" valign="top">
                <table width="100%">
                    <tr>
                        <td class="label">No. Rekam Medis</td>
                        <td class="titik">:</td>
                        <td>{{ $data->no_rkm_medis }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ruang</td>
                        <td class="titik">:</td>
                        <td>{{ $ruang }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="titik">:</td>
                        <td>{{ $data->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">
        PRE SURGICAL ASSESSMENT
    </div>

    <table class="detail-operasi">
        <tr>
            <td width="60%" valign="top">
                <table width="100%">
                    <tr>
                        <td class="label">Tanggal</td>
                        <td class="titik">:</td>
                        <td>{{ date('d-m-Y', strtotime($data->tgl_operasi)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Dokter Bedah</td>
                        <td class="titik">:</td>
                        <td>{{ $data->operator1 }}</td>
                    </tr>
                </table>
            </td>

            <td width="40%" valign="top">
                <table width="100%">
                    <tr>
                        <td class="label">Alergi</td>
                        <td class="titik">:</td>
                        <td>{{ $alergi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Waktu</td>
                        <td class="titik">:</td>
                        <td>{{ date('H:i:s', strtotime($asesmen->jam_rawat)) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width:100%; border-collapse:collapse; border-top:1px solid #000;">
        <tr>
            <!-- Kolom Kiri -->
            <td width="68%" style="vertical-align:top; padding:8px; border-right:1px solid #000;">

                <b>Keluhan :</b><br>
                <div style="font-style:italic; text-decoration:underline; margin:6px 0 12px 14px;">
                    {!! nl2br(e($asesmen->keluhan ?? '-')) !!}
                </div>

                <b>Pemeriksaan :</b><br>
                <div style="font-style:italic; text-decoration:underline; margin:6px 0 12px 14px;">
                    {!! nl2br(e($pemeriksaanText ?? '-')) !!}
                </div>

                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td width="32%">Suhu Tubuh (°C)</td>
                        <td width="18%" style="text-align:center;">
                            <u>{{ $asesmen->suhu_tubuh ?? '-' }}</u>
                        </td>

                        <td width="32%">Nadi (/Mnt)</td>
                        <td width="18%" style="text-align:center;">
                            <u>{{ $asesmen->nadi ?? '-' }}</u>
                        </td>
                    </tr>

                    <tr>
                        <td>Tensi</td>
                        <td style="text-align:center;">
                            <u>{{ $asesmen->tensi ?? '-' }}</u>
                        </td>

                        <td>Respirasi (/Mnt)</td>
                        <td style="text-align:center;">
                            <u>{{ $asesmen->respirasi ?? '-' }}</u>
                        </td>
                    </tr>

                    <tr>
                        <td>Tinggi (Cm)</td>
                        <td style="text-align:center;">
                            <u>{{ $asesmen->tinggi ?? '-' }}</u>
                        </td>

                        <td>GCS (E,V,M)</td>
                        <td style="text-align:center;">
                            <u>{{ $asesmen->gcs ?? '-' }}</u>
                        </td>
                    </tr>

                    <tr>
                        <td>Berat (Kg)</td>
                        <td style="text-align:center;">
                            <u>{{ $asesmen->berat ?? '-' }}</u>
                        </td>

                        <td></td>
                        <td></td>
                    </tr>
                </table>

            </td>

            <!-- Kolom Kanan -->
            <td width="32%" style="vertical-align:top; padding:8px;">

                <b>Penilaian :</b><br>
                <div style="font-style:italic; text-decoration:underline; margin:6px 0 40px 14px;">
                    {!! nl2br(e($asesmen->penilaian ?? '-')) !!}
                </div>

                <b>Tindak Lanjut :</b><br>
                <div style="font-style:italic; text-decoration:underline; margin:6px 0 0 14px;">
                    {!! nl2br(e($asesmen->rtl ?? '-')) !!}
                </div>

            </td>
        </tr>
    </table>

    <table class="post-op">

        <tr>
            <th colspan="2">
                POST SURGICAL REPORT
            </th>
        </tr>

        <tr>

            <!-- KIRI -->
            <td class="left-area">

                <table class="info-table">

                    <tr>
                        <td class="label">Dokter Bedah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->operator1 ?: '-' }}</td>

                        <td class="label">Asisten Bedah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->asistenoperator1 ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Dokter Bedah 2</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->operator2 ?: '-' }}</td>

                        <td class="label">Asisten Bedah 2</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->asistenoperator2 ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Perawat Resusitas</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->perawatresusitas ?: '-' }}</td>

                        <td class="label">Dokter Anastesi</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->anastesi ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Instrumen</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->instrumen ?: '-' }}</td>

                        <td class="label">Asisten Anastesi</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->asistenanastesi ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Dokter Anak</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->pjanak ?: '-' }}</td>

                        <td class="label">Bidan</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->bidan1 ?: '-' }}</td>
                    </tr>

                    <tr>
                        <td class="label">Dokter Umum</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->dokumum ?: '-' }}</td>

                        <td class="label">Onloop</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $data->omloop ?: '-' }}</td>
                    </tr>

                </table>

                <div class="section-title">
                    Diagnosa Pre-Op / Pre Operation Diagnosis
                </div>

                <div class="section-value">
                    {!! nl2br(e($data->diagnosa_preop ?? '-')) !!}
                </div>

                <div class="section-title">
                    Jaringan Yang di-Eksisi/-Insisi
                </div>

                <div class="section-value">
                    {!! nl2br(e($data->jaringan_dieksekusi ?? '-')) !!}
                </div>

                <div class="section-title">
                    Diagnosa Post-Op / Post Operation Diagnosis
                </div>

                <div class="section-value">
                    {!! nl2br(e($data->diagnosa_postop ?? '-')) !!}
                </div>

            </td>

            <!-- KANAN -->
            <td class="right-area">

                <div class="right-title">
                    Tipe/Jenis Anastesi
                </div>

                <div class="right-value">
                    {{ $data->jenis_anasthesi ?: '-' }}
                </div>

                <div class="right-title">
                    Dikirim ke Pemeriksaan PA
                </div>

                <div class="right-value">
                    {{ $data->permintaan_pa ?: '-' }}
                </div>

                <div class="right-title">
                    Tipe/Kategori Operasi
                </div>

                <div class="right-value">
                    {{ $data->kategori ?: '-' }}
                </div>

                <div class="right-title">
                    Selesai Operasi
                </div>

                <div class="right-value">
                    {{ $data->selesaioperasi ?: '-' }}
                </div>

            </td>

        </tr>

    </table>

    <div class="section-title">
        REPORT ( PROCEDURES, SPECIFIC FINDINGS AND COMPLICATIONS )
    </div>

    @php
        $lines = preg_split("/\r\n|\n|\r/", trim($data->laporan_operasi));
    @endphp

    <div
        style="
            padding:6px;
            font-family:Tahoma;
            font-size:12px;
            font-style:italic;
            line-height:1.0;
        ">
        @foreach ($lines as $line)
            @if (Str::startsWith(trim($line), '-'))
                <div style="padding-left:18px;">{{ $line }}</div>
            @else
                <div>{{ $line }}</div>
            @endif
        @endforeach
    </div>

    <table style="width:100%; margin-top:20px;">
        <tr>
            <td></td>
            <td width="220" style="text-align:center;">
                {{ date('d/m/Y', strtotime($data->selesaioperasi)) }}
                <br>

                Dokter Bedah
                <br>

                <img src="data:image/png;base64,{{ $qrcode }}" width="90" height="90">
                <br>

                <u>{{ $data->operator1 }}</u>
            </td>
        </tr>
    </table>
</body>

</html>
