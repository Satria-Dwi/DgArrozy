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
                            <td>{{ $data->nm_bangsal }}</td>
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
                <td width="30%" style="padding:4px 0; vertical-align:top;">
                    Diagnosa Awal Masuk
                </td>
                <td width="2%" style="padding:4px 0; vertical-align:top;">:</td>
                <td style="vertical-align:top;">
                    {{ trim($data->diagnosa_awal) }}
                </td>
            </tr>

            <tr>
                <td style="padding:4px 0; vertical-align: top;">Alasan Masuk Dirawat</td>
                <td style="padding:4px 0; vertical-align: top;">:</td>
                <td style="padding:-4px 0; white-space: pre-line; vertical-align: top;">{{ trim($data->alasan) }}</td>
            </tr>

            <tr>
                <td style="padding:4px 0; vertical-align: top;">Keluhan Utama Riwayat Penyakit</td>
                <td style="padding:4px 0; vertical-align: top;">:</td>
                <td style="white-space: pre-line; vertical-align: top;">{{ trim($data->keluhan_utama) }}</td>
            </tr>

            <tr>
                <td style="padding:4px 0; vertical-align: top;">Pemeriksaan Fisik</td>
                <td style="padding:4px 0; vertical-align: top;">:</td>
                <td style="white-space: pre-line; vertical-align: top;">{{ trim($data->pemeriksaan_fisik) }}</td>
            </tr>

            <tr>
                <td style="padding:4px 0; vertical-align: top;">Jalannya Penyakit Selama Perawatan</td>
                <td style="padding:4px 0; vertical-align: top;">:</td>
                <td style=" white-space: pre-line; vertical-align: top;">{{ trim($data->jalannya_penyakit) }}</td>
            </tr>
        </table>

        <div style="position:relative; padding:8px 0;">

            <div style="position:absolute; left:0; width:30%;">
                Pemeriksaan Penunjang Radiologi Terpenting
            </div>

            <div style="position:absolute; left:30%; width:2%;">
                :
            </div>

            <div style="padding-left:32%; white-space:pre-line;">{{ $data->penunjang1 }}</div>

        </div>

        @if ($data->penunjang2)
            <div style="page-break-before:always; "></div>

            <div style="padding-left:32%; white-space:pre-line;">
                {{ $data->penunjang2 }}
            </div>
        @endif

        <div style="position:relative; padding:8px 0;">

            <div style="position:absolute; left:0; width:30%;">
                Tindakan/Operasi Selama Perawatan
            </div>

            <div style="position:absolute; left:30%; width:2%;">
                :
            </div>

            <div style="padding-left:32%; white-space:pre-line;">{{ $data->tindakan1 }}</div>

        </div>

        @if ($data->tindakan2)
            <div style="page-break-before:always; "></div>

            <div style="padding-left:32%; white-space:pre-line;">
                {{ $data->tindakan2 }}
            </div>
        @endif

        <div style="position:relative; padding:8px 0;">

            <div style="position:absolute; left:0; width:30%;">
                Obat-obatan Selama Perawatan
            </div>

            <div style="position:absolute; left:30%; width:2%;">
                :
            </div>

            <div style="padding-left:32%; white-space:pre-line;">{{ $data->obat1 }}</div>

        </div>

        @if ($data->obat2)
            <div style="page-break-before:always; "></div>

            <div style="padding-left:32%; white-space:pre-line;">
                {{ $data->obat2 }}
            </div>
        @endif

        <div style="position:relative; padding:8px 0;">

            <div style="position:absolute; left:0; width:30%;">
                Pemeriksaan Penunjang Laboratorium Terpenting
            </div>

            <div style="position:absolute; left:30%; width:2%;">
                :
            </div>

            <div style="padding-left:32%; white-space:pre-line;">{{ $data->laborat1 }}</div>

        </div>

        @if ($data->laborat2)
            <div style="page-break-before:always; "></div>

            <div style="padding-left:32%; white-space:pre-line;">
                {{ $data->laborat2 }}
            </div>
        @endif

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
                <td width="30%" style="padding-left:15px; vertical-align:top;">- Diagnosa Utama</td>
                <td width="2%" style="vertical-align:top;">:</td>
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

        <table width="100%" class="content-table" cellspacing="0" cellpadding="2">

            <tr>
                <td width="30%" style="padding:2px 0; vertical-align:top;">
                    Alergi / Reaksi Obat
                </td>
                <td width="2%" style="padding:2px 0; vertical-align:top;">:</td>
                <td style="padding:2px 0; vertical-align:top;">
                    {{ trim($data->alergi) }}
                </td>
            </tr>

            <tr>
                <td width="30%" style="padding:2px 0; vertical-align:top;">
                    Diet Selama Perawatan
                </td>
                <td width="2%" style="padding:2px 0; vertical-align:top;">:</td>
                <td style="padding:2px 0; vertical-align:top;">
                    {{ trim($data->diet) }}
                </td>
            </tr>

            <tr>
                <td width="30%" style="padding:2px 0; vertical-align:top;">
                    Hasil Lab Yang Belum Selesai (Pending)
                </td>
                <td width="2%" style="padding:2px 0; vertical-align:top;">:</td>
                <td style="padding:2px 0; vertical-align:top;">
                    {{ trim($data->lab_belum) }}
                </td>
            </tr>

            <tr>
                <td width="30%" style="padding:2px 0; vertical-align:top;">
                    Instruksi / Anjuran dan Edukasi (Follow Up)
                </td>
                <td width="2%" style="padding:2px 0; vertical-align:top;">:</td>
                <td style="padding:2px 0; vertical-align:top;">
                    {{ trim($data->edukasi) }}
                </td>
            </tr>
        </table>

        <!-- KONDISI PULANG -->
        <table width="100%" class="content-table" cellspacing="0" cellpadding="2" style="margin-top:10px;">
            <tr>
                <td width="18%" style="padding:2px 0; padding-left: 30px;">Keadaan Pulang</td>
                <td width="2%" style="padding:2px 0;">:</td>
                <td width="25%" style="padding:2px 0;">{{ trim($data->keadaan) }}</td>

                <td width="18%" style="padding:2px 0;">Cara Keluar</td>
                <td width="2%" style="padding:2px 0;">:</td>
                <td width="35%" style="padding:2px 0;">{{ trim($data->cara_keluar) }}</td>
            </tr>

            <tr>
                <td style="padding:2px 0; padding-left: 30px;">Dilanjutkan</td>
                <td style="padding:2px 0;">:</td>
                <td style="padding:2px 0;">{{ trim($data->dilanjutkan) }}</td>

                <td style="padding:2px 0;">Tanggal Kontrol</td>
                <td style="padding:2px 0;">:</td>
                <td style="padding:2px 0;">{{ trim($data->kontrol) }}</td>
            </tr>
        </table>

        <table width="100%" style="border-collapse:collapse; margin-top:10px;">
            <tr>
                <td colspan="3" style="font-weight:bold; padding-bottom:3px; border-bottom:1px solid #000;">
                    Obat-obatan Waktu Pulang
                </td>
            </tr>

            <tr>
                <td width="30%" style="padding:2px 0; vertical-align:top;">
                    Terapi / Obat Pulang
                </td>
                <td width="2%" style="padding:2px 0; vertical-align:top;">
                    :
                </td>
                <td style="padding:2px 0; vertical-align:top;">
                    {!! nl2br(e(trim($data->obat_pulang))) !!}
                </td>
            </tr>
        </table>

    </div>

    @php
        $dokterTtd = collect();

        if ($data->pj2) {
            $dokterTtd->push(
                (object) [
                    'nm_dokter' => $data->pj2->nm_dokter,
                    'label' => 'Dokter Penanggung Jawab 2',
                ],
            );
        }

        if ($data->pj3) {
            $dokterTtd->push(
                (object) [
                    'nm_dokter' => $data->pj3->nm_dokter,
                    'label' => 'Dokter Penanggung Jawab 3',
                ],
            );
        }

        if ($data->pj4) {
            $dokterTtd->push(
                (object) [
                    'nm_dokter' => $data->pj4->nm_dokter,
                    'label' => 'Dokter Penanggung Jawab 4',
                ],
            );
        }

        // Balik urutan dokter tambahan
        $dokterTtd = $dokterTtd->reverse()->values();

        // Tambahkan PJ utama terakhir (paling kanan)
        $dokterTtd->push(
            (object) [
                'nm_dokter' => $data->nm_dokter,
                'label' => 'Dokter Penanggung Jawab',
            ],
        );

        $jumlahDokter = $dokterTtd->count();
    @endphp
    <style>
        .footer-ttd {
            width: 100%;
            margin-top: 20px;
        }

        .footer-ttd td {
            text-align: center;
            vertical-align: top;
        }
    </style>

    <div class="footer-ttd">
        <table width="100%">
            <tr>

                {{-- Jika hanya PJ utama --}}
                @if ($jumlahDokter == 1)
                    <td width="60%"></td>
                    <td width="40%">
                        @php
                            $dokter = $dokterTtd->last();
                            $qrData = 'Dokter: ' . $dokter->nm_dokter;
                        @endphp
                        <br>
                        {{ $dokter->label }}
                        <br><br>

                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qrData) }}"
                            style="width:80px;">

                        <br><br>

                        <b style="text-decoration:underline;">
                            {{ $dokter->nm_dokter }}
                        </b>
                    </td>
                @else
                    {{-- Jika ada lebih dari 1 dokter --}}
                    @foreach ($dokterTtd as $dokter)
                        <td width="{{ 100 / $jumlahDokter }}%">

                            @php
                                $qrData = 'Dokter: ' . $dokter->nm_dokter;
                            @endphp
                            <br>
                            {{ $dokter->label }}
                            <br><br>

                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($qrData) }}"
                                style="width:80px;">

                            <br><br>

                            <b style="text-decoration:underline;">
                                {{ $dokter->nm_dokter }}
                            </b>

                        </td>
                    @endforeach

                @endif

            </tr>
        </table>
    </div>

</body>

</html>
