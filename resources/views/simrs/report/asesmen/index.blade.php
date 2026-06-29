<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: 21cm 33cm;
            /* F4 */
            margin: 10mm;
        }

        body {
            font-family: Tahoma, Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
            overflow: hidden;
        }

        .f4-container {
            width: 100%;
        }

        .main-table {
            page-break-inside: auto;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        table.main-table td,
        table.main-table th {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
        }

        .header-gray {
            background: #EFEFEF;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .fs10 {
            font-size: 9px;
        }

        .fs12 {
            font-size: 10px;
        }

        .fs14 {
            font-size: 14px;
        }

        .kop img {
            width: 100%;
        }
    </style>

</head>

<body>
    <div class="f4-container">
        {{-- ===================== --}}
        {{-- KOP SURAT --}}
        {{-- ===================== --}}

        <div class="kop">
            <img src="{{ public_path('img/kop_surat/kop-arrozy2.jpg') }}">
        </div>

        <hr>

        <h3 style="text-align:center">
            PENILAIAN AWAL MEDIS IGD
        </h3>

        {{-- ===================== --}}
        {{-- IDENTITAS PASIEN --}}
        {{-- ===================== --}}

        <table class="main-table" style="margin-top:-6px;">

            <tr>

                <td width="45%" style="padding:0;">

                    <table width="100%" style="border:none;">

                        <tr>

                            <td style="border:none;width:75px;">
                                No. RM
                            </td>

                            <td style="border:none;">
                                :
                                <b>{{ $data->no_rkm_medis }}</b>
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;">
                                Nama Pasien
                            </td>

                            <td style="border:none;">
                                :
                                {{ $data->nm_pasien }}
                            </td>

                        </tr>

                    </table>

                </td>

                <td width="23%" style="padding:0;">

                    <table width="100%" style="border:none;">

                        <tr>

                            <td style="border:none;">
                                Jenis Kelamin
                            </td>

                            <td style="border:none;">
                                :
                                {{ $data->jk == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;">
                                Tanggal Lahir
                            </td>

                            <td style="border:none;">
                                :
                                {{ \Carbon\Carbon::parse($data->tgl_lahir)->format('d/m/Y') }}
                            </td>

                        </tr>

                    </table>

                </td>

                <td width="32%" style="padding:0;">

                    <table width="100%" style="border:none;">

                        <tr>

                            <td style="border:none;">
                                Tanggal
                            </td>

                            <td style="border:none;">
                                :
                                {{ \Carbon\Carbon::parse($data->tanggal)->format('d/m/Y H:i:s') }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;">
                                Anamnesis
                            </td>

                            <td style="border:none;">
                                :
                                {{ $data->anamnesis }}
                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

        {{-- ===================== --}}
        {{-- I. RIWAYAT KESEHATAN --}}
        {{-- ===================== --}}

        <div class="header-gray">

            I. RIWAYAT KESEHATAN

        </div>

        <table class="main-table" style="margin-top:0;">

            <tr>

                <td colspan="2">

                    Keluhan Utama :
                    {!! nl2br(e($data->keluhan_utama)) !!}

                </td>

            </tr>

            <tr>

                <td colspan="2">

                    Riwayat Penyakit Sekarang :
                    {!! nl2br(e($data->rps)) !!}

                </td>

            </tr>

            <tr>

                <td width="50%">

                    Riwayat Penyakit Dahulu :
                    {!! nl2br(e($data->rpd)) !!}

                </td>

                <td width="50%">

                    Riwayat Penyakit Dalam Keluarga :
                    {!! nl2br(e($data->rpk)) !!}

                </td>

            </tr>

            <tr>

                <td>

                    Riwayat Pengobatan :
                    {!! nl2br(e($data->rpo)) !!}

                </td>

                <td>

                    Riwayat Alergi :
                    {!! nl2br(e($data->alergi)) !!}

                </td>

            </tr>

        </table>

        {{-- ===================== --}}
        {{-- II. PEMERIKSAAN FISIK --}}
        {{-- ===================== --}}

        <div class="header-gray" style="margin-top:-4px;">

            II. PEMERIKSAAN FISIK

        </div>

        <table class="main-table" style="margin-top:0;">

            <tr>

                <td width="33%">

                    Keadaan Umum :
                    {{ $data->keadaan }}

                </td>

                <td width="33%">

                    Kesadaran :
                    {{ $data->kesadaran }}

                </td>

                <td width="34%" class="text-center">

                    GCS (E,V,M) :
                    {{ $data->gcs }}

                </td>

            </tr>

            <tr>

                <td colspan="3" class="text-center" style="padding:6px;">

                    <span style="margin-right:15px;">

                        TD :
                        {{ $data->td }}
                        mmHg

                    </span>

                    <span style="margin-right:15px;">

                        N :
                        {{ $data->nadi }}
                        x/menit

                    </span>

                    <span style="margin-right:15px;">

                        RR :
                        {{ $data->rr }}
                        x/menit

                    </span>

                    <span style="margin-right:15px;">

                        Suhu :
                        {{ $data->suhu }}
                        °C

                    </span>

                    <span style="margin-right:15px;">

                        SpO₂ :
                        {{ $data->spo }}
                        %

                    </span>

                    <span style="margin-right:15px;">

                        BB :
                        {{ $data->bb }}
                        Kg

                    </span>

                    <span>

                        TB :
                        {{ $data->tb }}
                        cm

                    </span>

                </td>

            </tr>

        </table>


        <table class="main-table" style="margin-top:-6px;border-top:none;">

            <tr>

                <td width="25%" style="padding:0;border-top:none;">

                    <table width="100%" style="border:none;">

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Kepala
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->kepala }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Mata
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->mata }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Gigi &amp; Mulut
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->gigi }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;padding:2px;">
                                Leher
                            </td>

                            <td style="border:none;padding:2px;text-align:right;">
                                {{ $data->leher }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td width="25%" style="padding:0;border-top:none;">

                    <table width="100%" style="border:none;">

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Thoraks
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->thoraks }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Abdomen
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->abdomen }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;">
                                Genital &amp; Anus
                            </td>

                            <td style="border:none;border-bottom:1px solid #eee;padding:2px;text-align:right;">
                                {{ $data->genital }}
                            </td>

                        </tr>

                        <tr>

                            <td style="border:none;padding:2px;">
                                Ekstremitas
                            </td>

                            <td style="border:none;padding:2px;text-align:right;">
                                {{ $data->ekstremitas }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td width="50%" style="border-top:none;vertical-align:top;">

                    {!! nl2br(e($data->ket_fisik)) !!}

                </td>

            </tr>

        </table>
        {{-- ===================== --}}
        {{-- III. STATUS LOKALIS --}}
        {{-- ===================== --}}

        <div class="header-gray" style="margin-top:-4px;">
            III. STATUS LOKALIS
        </div>

        <table class="main-table" style="margin-top:0;">
            <tr>
                <td class="text-center" style="padding: 10px;">

                    @if ($gambar_lokalis)
                        <img src="{{ $gambar_lokalis }}" style="width: 100%; max-height: 150px; object-fit: contain;">
                    @else
                        <i>(Gambar Anatomi Tidak Tersedia)</i>
                    @endif

                </td>
            </tr>

            <tr>
                <td>
                    Keterangan :
                    <br>
                    {!! nl2br(e($data->ket_lokalis ?? '-')) !!}
                </td>
            </tr>
        </table>


        {{-- ===================== --}}
        {{-- IV. PEMERIKSAAN PENUNJANG --}}
        {{-- ===================== --}}

        <div class="header-gray">

            IV. PEMERIKSAAN PENUNJANG

        </div>

        <table class="main-table">

            <tr>

                <td width="50%">

                    EKG :
                    <br>
                    {!! nl2br(e($data->ekg)) !!}

                </td>

                <td width="50%">

                    Laboratorium :
                    <br>
                    {!! nl2br(e($data->lab)) !!}

                </td>

                <td width="50%">

                    Radiologi :
                    <br>
                    {!! nl2br(e($data->rad)) !!}

                </td>

            </tr>

        </table>


        {{-- ===================== --}}
        {{-- V. DIAGNOSIS KERJA --}}
        {{-- ===================== --}}

        <div class="header-gray">

            V. DIAGNOSIS

        </div>

        <table class="main-table">

            <tr>

                <td>

                    {!! nl2br(e($data->diagnosis)) !!}

                </td>

            </tr>

        </table>


        {{-- ===================== --}}
        {{-- VI. RENCANA / TATA LAKSANA --}}
        {{-- ===================== --}}

        <div class="header-gray">

            VI.TATA LAKSANA

        </div>

        <table class="main-table">

            <tr>

                <td>

                    {!! nl2br(e($data->tata)) !!}

                </td>

            </tr>

        </table>

        <style>
            .ttd-table {
                margin-top: 10px;
            }
        </style>

        <table class="main-table ttd-table" style="margin-top: auto;">
            <tr>
                <td width="50%" class="text-center" style="border-right: 1px solid #000; vertical-align: bottom;">
                    Tanggal dan Jam
                </td>
                <td width="50%" class="text-center" style="vertical-align: center;">
                    Nama Dokter dan Tanda Tangan
                </td>
            </tr>

            <tr>
                <td width="50%" class="text-center" style="border-right: 1px solid #000; vertical-align: center;">
                    {{ $data->tanggal ? date('d/m/Y H:i:s', strtotime($data->tanggal)) . ' WIB' : '-' }}
                </td>

                <td width="50%" class="text-center" style="vertical-align: top;">
                    @if (!empty($qr))
                        <img src="{{ $qr }}" style="width: 70px; height: 70px; margin-top: 10px;"><br>
                    @else
                        <br><br>
                    @endif

                    <span class="fs-10">
                        {{ $data->nm_dokter ?? '-' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
