<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
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
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop">
        <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
    </div>

    <div class="judul">
        RESUME MEDIS PASIEN
    </div>

    <div class="box">

        <!-- IDENTITAS -->
        <table width="100%" style="margin-top:10px;">
            <tr>
                <!-- KIRI -->
                <td width="50%" valign="top">
                    <table width="100%">
                        <tr>
                            <td width="90">Nama Pasien</td>
                            <td width="10">:</td>
                            <td>{{ $data->nm_pasien }}</td>
                        </tr>
                        <tr>
                            <td>Umur</td>
                            <td>:</td>
                            <td>{{ $data->umurdaftar }} {{ $data->sttsumur }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Lahir</td>
                            <td>:</td>
                            <td>{{ $data->tgl_lahir }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>:</td>
                            <td>{{ $data->pekerjaan }}</td>
                        </tr>
                        <tr>
                            <td valign="top">Alamat</td>
                            <td valign="top">:</td>
                            <td>{{ $data->alamat_lengkap }}</td>
                        </tr>
                    </table>
                </td>

                <!-- KANAN -->
                <td width="50%" valign="top">
                    <table width="100%">
                        <tr>
                            <td width="90">No. RM</td>
                            <td width="10">:</td>
                            <td>{{ $data->no_rkm_medis }}</td>
                        </tr>
                        <tr>
                            <td>Ruang</td>
                            <td>:</td>
                            <td>{{ $data->nm_poli }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{ $data->jk }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Masuk</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($data->tgl_masuk)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Keluar</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($data->tgl_keluar)->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="line"></div>

        <table width="100%" class="content-table" cellspacing="0" cellpadding="2">

            <tr>
                <td width="30%" style="padding:6px 0; vertical-align:top;">
                    Keluhan utama dari riwayat penyakit yang positif
                </td>
                <td width="2%" style="padding:6px 0; vertical-align:top;">:</td>
                <td style="padding:6px 0; vertical-align:top;">
                    {{ trim($data->keluhan_utama) }}
                </td>
            </tr>

            <tr>
                <td style="padding:6px 0; vertical-align: top;">Jalannya Penyakit Selama Perawatan</td>
                <td style="padding:6px 0; vertical-align: top;">:</td>
                <td style="padding:6px 0;">{{ $data->jalannya_penyakit }}</td>
            </tr>

            <tr>
                <td style="padding:20px 0; vertical-align: top;">Pemeriksaan Penunjang</td>
                <td style="padding:20px 0; vertical-align: top;">:</td>
                <td style=" padding:6px 0; white-space: pre-line; vertical-align: top;">
                    {{ $data->pemeriksaan_penunjang }}
                </td>
            </tr>

            <tr>
                <td style="padding:6px 0;">Hasil Laboratorium</td>
                <td style="padding:6px 0;">:</td>
                <td style="padding:6px 0;">{{ $data->hasil_laborat }}</td>
            </tr>

        </table>

        <table width="100%" style="border-collapse:collapse; margin-top:10px;">
            <tr>
                <td colspan="4" style="font-weight:bold; padding-bottom:3px; border-bottom:1px solid #000;">
                    DIAGNOSA AKHIR
                </td>
            </tr>

            <tr>
                <td colspan="3"></td>
                <td style="text-align:right;">
                    <b>Kode ICD</b>
                </td>
            </tr>

            <!-- Diagnosa Utama -->
            <tr>
                <td width="30%" style="padding-left:15px;">- Diagnosa Utama</td>
                <td width="2%">:</td>
                <td>{{ $data->diagnosa_utama }}</td>
                <td width="120" align="right">
                    ( {{ $data->kd_diagnosa_utama }} )
                </td>
            </tr>

            <!-- Diagnosa Sekunder -->
            <tr>
                <td style="padding-left:15px; vertical-align:top;">- Diagnosa Sekunder</td>
                <td style="vertical-align:top;">:</td>
                <td>1. {{ $data->diagnosa_sekunder }}</td>
                <td align="right">( {{ $data->kd_diagnosa_sekunder }} )</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>2. {{ $data->diagnosa_sekunder2 }}</td>
                <td align="right">( {{ $data->kd_diagnosa_sekunder2 }} )</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>3. {{ $data->diagnosa_sekunder3 }}</td>
                <td align="right">( {{ $data->kd_diagnosa_sekunder3 }} )</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>4. {{ $data->diagnosa_sekunder4 }}</td>
                <td align="right">( {{ $data->kd_diagnosa_sekunder4 }} )</td>
            </tr>

            <!-- Prosedur Utama -->
            <tr>
                <td style="padding-left:15px; padding-top:10px;">- Prosedur/Tindakan Utama</td>
                <td style="padding-top:10px;">:</td>
                <td style="padding-top:10px;">
                    {{ $data->prosedur_utama }}
                </td>
                <td align="right" style="padding-top:10px;">
                    ( {{ $data->kd_prosedur_utama }} )
                </td>
            </tr>

            <!-- Prosedur Sekunder -->
            <tr>
                <td style="padding-left:15px; vertical-align:top;">
                    - Prosedur/Tindakan Sekunder
                </td>
                <td style="vertical-align:top;">:</td>
                <td>1. {{ $data->prosedur_sekunder }}</td>
                <td align="right">
                    ( {{ $data->kd_prosedur_sekunder }} )
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>2. {{ $data->prosedur_sekunder2 }}</td>
                <td align="right">
                    ( {{ $data->kd_prosedur_sekunder2 }} )
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>3. {{ $data->prosedur_sekunder3 }}</td>
                <td align="right">
                    ( {{ $data->kd_prosedur_sekunder3 }} )
                </td>
            </tr>
        </table>

        <!-- KONDISI PULANG -->
        <table width="100%" style="margin-top:10px;">
            <tr>
                <td width="30%">Kondisi Pasien Pulang</td>
                <td width="2%">:</td>
                <td><b>{{ $data->kondisi_pulang }}</b></td>
            </tr>
        </table>

        <table width="100%" style="border-collapse:collapse; margin-top:10px;">
            <tr>
                <td colspan="4" style="padding:0;">
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                        <tr>
                            <td style="font-weight:bold; padding-bottom:3px; border-bottom:1px solid #000;">
                                Obat-obatan Waktu Pulang
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <table width="100%" class="content-table" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="41.6%" style="padding:4px 0; vertical-align:top;">
                        Terapi / Obat Pulang
                    </td>
                    <td width="2%" style="padding:4px 0; vertical-align:top;">
                        :
                    </td>
                    <td style="vertical-align:top; padding-left: 5px;">
                        {!! nl2br(e(trim($data->obat_pulang))) !!}
                    </td>
                </tr>
            </table>
        </table>

    </div>

    <style>
        .footer-ttd {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            width: 100%;
        }
    </style>

    <div class="footer-ttd">
        <table width="100%">
            <tr>
                <td width="60%"></td>
                <td align="center">
                    {{-- Probolinggo, --}}
                    {{-- {{ \Carbon\Carbon::parse($data->tgl_registrasi)->translatedFormat('d F Y') }} --}}
                    <br>
                    Dokter Penanggung Jawab
                    <br><br>

                    @php
                        // $qrData = "Probolinggo\n";
                        // $qrData .=
                        //     'Tanggal Registrasi: ' .
                        //     \Carbon\Carbon::parse($data->tgl_registrasi)->format('d-m-Y') .
                        //     "\n";
                        $qrData = 'Dokter: ' . $data->nm_dokter;
                    @endphp

                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qrData) }}"
                        style="width:90px;">

                    <br><br>

                    <b style="text-decoration:underline;">
                        {{ $data->nm_dokter }}
                    </b>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
