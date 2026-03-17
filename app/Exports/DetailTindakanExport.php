<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class DetailTindakanExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{

    protected $start;
    protected $end;
    protected $jenis;
    protected $columns = [];

    public function __construct($start, $end, $jenis)
    {
        $this->start = $start;
        $this->end = $end;
        $this->jenis = $jenis;

        // Set columns sesuai jenis di sini
        switch ($jenis) {
            case 'ranap':
                $this->columns = [
                    'No Rawat',
                    'No RM',
                    'Nama Pasien',
                    'Kode',
                    'Perawatan',
                    'Dokter',
                    'Tanggal',
                    'Jam',
                    'Penjamin',
                    'Ruangan'
                ];
                break;
            case 'ralan':
                $this->columns = [
                    'No Rawat',
                    'No RM',
                    'Nama Pasien',
                    'Kode',
                    'Perawatan',
                    'Dokter',
                    'Tanggal',
                    'Jam',
                    'Penjamin',
                    'Poli'
                ];
                break;
            case 'operasi':
                $this->columns = [
                    'No Rawat',
                    'No RM',
                    'Nama Pasien',
                    'Kode Paket',
                    'Nama Paket',
                    'Operator',
                    'Tanggal',
                    'Jam',
                    'Penjamin',
                    'Ruangan'
                ];
                break;
            case 'radiologi':
            case 'lab':
                $this->columns = [
                    'No Rawat',
                    'No RM',
                    'Nama Pasien',
                    'Kode',
                    'Perawatan',
                    'Dokter',
                    'Tanggal',
                    'Jam',
                    'Penjamin',
                    'Ruangan'
                ];
                break;
            case 'mcu':
                $this->columns = [
                    'No Rawat',
                    'No RM',
                    'Tanggal Registrasi',
                    'Nama Pasien',
                    'Dokter MCU',
                    'Ada Lab?',
                    'Dokter Lab',
                    'Ada Radiologi?',
                    'Dokter Radiologi'
                ];
                break;
        }
    }

    public function collection()
    {
        $start = $this->start;
        $end   = $this->end;
        $jenis = $this->jenis;

        /* ===============================
           RAWAT INAP
        =============================== */
        if ($jenis === 'ranap') {

            $query = DB::table('rawat_inap_dr')
                ->join('reg_periksa', 'rawat_inap_dr.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_inap', 'rawat_inap_dr.kd_jenis_prw', '=', 'jns_perawatan_inap.kd_jenis_prw')
                ->join('dokter', 'rawat_inap_dr.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->join('kamar_inap', 'rawat_inap_dr.no_rawat', '=', 'kamar_inap.no_rawat')
                ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'rawat_inap_dr.kd_jenis_prw',
                    'jns_perawatan_inap.nm_perawatan',
                    'dokter.nm_dokter',
                    'rawat_inap_dr.tgl_perawatan as tanggal',
                    'rawat_inap_dr.jam_rawat as jam',
                    'penjab.png_jawab',
                    'bangsal.nm_bangsal as ruangan'
                );

            if ($start && $end) {
                $query->whereBetween('rawat_inap_dr.tgl_perawatan', [$start, $end]);
            }

            $this->columns = [
                'No Rawat',
                'No RM',
                'Nama Pasien',
                'Kode',
                'Perawatan',
                'Dokter',
                'Tanggal',
                'Jam',
                'Penjamin',
                'Ruangan'
            ];
            $query->orderBy('reg_periksa.no_rawat', 'desc');
        }

        /* ===============================
           RAWAT JALAN
        =============================== */ elseif ($jenis === 'ralan') {

            $query = DB::table('rawat_jl_dr')
                ->join('reg_periksa', 'rawat_jl_dr.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan', 'rawat_jl_dr.kd_jenis_prw', '=', 'jns_perawatan.kd_jenis_prw')
                ->join('dokter', 'rawat_jl_dr.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'rawat_jl_dr.kd_jenis_prw',
                    'jns_perawatan.nm_perawatan',
                    'dokter.nm_dokter',
                    'rawat_jl_dr.tgl_perawatan as tanggal',
                    'rawat_jl_dr.jam_rawat as jam',
                    'penjab.png_jawab',
                    'poliklinik.nm_poli as ruangan'
                );

            if ($start && $end) {
                $query->whereBetween('rawat_jl_dr.tgl_perawatan', [$start, $end]);
            }

            $this->columns = [
                'No Rawat',
                'No RM',
                'Nama Pasien',
                'Kode',
                'Perawatan',
                'Dokter',
                'Tanggal',
                'Jam',
                'Penjamin',
                'Poli'
            ];
            $query->orderBy('reg_periksa.no_rawat', 'desc');
        }

        /* ===============================
           OPERASI
        =============================== */ elseif ($jenis === 'operasi') {

            $query = DB::table('reg_periksa')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('laporan_operasi', 'reg_periksa.no_rawat', '=', 'laporan_operasi.no_rawat')
                ->leftJoin('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->leftJoin('operasi', 'reg_periksa.no_rawat', '=', 'operasi.no_rawat')
                ->leftJoin('paket_operasi', 'operasi.kode_paket', '=', 'paket_operasi.kode_paket')
                ->leftJoin('dokter as d1', 'operasi.operator1', '=', 'd1.kd_dokter')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'operasi.kode_paket',
                    'paket_operasi.nm_perawatan',
                    'd1.nm_dokter',
                    DB::raw('DATE(laporan_operasi.tanggal) as tanggal'),
                    DB::raw('TIME(laporan_operasi.tanggal) as jam'),
                    'penjab.png_jawab',
                    DB::raw("'OK' as ruangan")
                );

            if ($start && $end) {
                $query->whereBetween(DB::raw('DATE(laporan_operasi.tanggal)'), [$start, $end]);
            }

            $this->columns = [
                'No Rawat',
                'No RM',
                'Nama Pasien',
                'Kode Paket',
                'Nama Paket',
                'Operator',
                'Tanggal',
                'Jam',
                'Penjamin',
                'Ruangan'
            ];
            $query->orderBy('reg_periksa.no_rawat', 'desc');
        }

        /* ===============================
           RADIOLOGI
        =============================== */ elseif ($jenis === 'radiologi') {

            $query = DB::table('periksa_radiologi')
                ->join('reg_periksa', 'periksa_radiologi.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_radiologi', 'periksa_radiologi.kd_jenis_prw', '=', 'jns_perawatan_radiologi.kd_jenis_prw')
                ->join('dokter', 'periksa_radiologi.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
                ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
                ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'periksa_radiologi.kd_jenis_prw',
                    'jns_perawatan_radiologi.nm_perawatan',
                    'dokter.nm_dokter',
                    'periksa_radiologi.tgl_periksa as tanggal',
                    'periksa_radiologi.jam',
                    'penjab.png_jawab',
                    DB::raw("
                        CASE
                            WHEN reg_periksa.status_lanjut='Ralan' THEN poliklinik.nm_poli
                            WHEN reg_periksa.status_lanjut='Ranap' THEN bangsal.nm_bangsal
                        END
                    ")
                );

            if ($start && $end) {
                $query->whereBetween('periksa_radiologi.tgl_periksa', [$start, $end]);
            }
            $this->columns = [
                'No Rawat',
                'No RM',
                'Nama Pasien',
                'Kode',
                'Perawatan',
                'Dokter',
                'Tanggal',
                'Jam',
                'Penjamin',
                'Ruangan'
            ];
            $query->orderBy('reg_periksa.no_rawat', 'desc');
        }

        /* ===============================
           LAB
        =============================== */ elseif ($jenis === 'lab') {

            $query = DB::table('periksa_lab')
                ->join('reg_periksa', 'periksa_lab.no_rawat', '=', 'reg_periksa.no_rawat')
                ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->join('jns_perawatan_lab', 'periksa_lab.kd_jenis_prw', '=', 'jns_perawatan_lab.kd_jenis_prw')
                ->join('dokter', 'periksa_lab.kd_dokter', '=', 'dokter.kd_dokter')
                ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
                ->leftJoin('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
                ->leftJoin('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
                ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
                ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
                ->select(
                    'reg_periksa.no_rawat',
                    'pasien.no_rkm_medis',
                    'pasien.nm_pasien',
                    'periksa_lab.kd_jenis_prw',
                    'jns_perawatan_lab.nm_perawatan',
                    'dokter.nm_dokter',
                    'periksa_lab.tgl_periksa as tanggal',
                    'periksa_lab.jam',
                    'penjab.png_jawab',
                    DB::raw("
                        CASE
                            WHEN reg_periksa.status_lanjut='Ralan' THEN poliklinik.nm_poli
                            WHEN reg_periksa.status_lanjut='Ranap' THEN bangsal.nm_bangsal
                        END
                    ")
                );

            if ($start && $end) {
                $query->whereBetween('periksa_lab.tgl_periksa', [$start, $end]);
            }
            $this->columns = [
                'No Rawat',
                'No RM',
                'Nama Pasien',
                'Kode',
                'Perawatan',
                'Dokter',
                'Tanggal',
                'Jam',
                'Penjamin',
                'Ruangan'
            ];
            $query->orderBy('reg_periksa.no_rawat', 'desc');
        } elseif ($jenis === 'mcu') {
            $query = DB::table('reg_periksa')
                ->join('penilaian_mcu', 'reg_periksa.no_rawat', '=', 'penilaian_mcu.no_rawat')
                ->leftJoin('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
                ->leftJoin('dokter as dokter_mcu', 'penilaian_mcu.kd_dokter', '=', 'dokter_mcu.kd_dokter')
                ->leftJoin('periksa_lab', 'reg_periksa.no_rawat', '=', 'periksa_lab.no_rawat')
                ->leftJoin('dokter as dokter_lab', 'periksa_lab.kd_dokter', '=', 'dokter_lab.kd_dokter')
                ->leftJoin('periksa_radiologi', 'reg_periksa.no_rawat', '=', 'periksa_radiologi.no_rawat')
                ->leftJoin('dokter as dokter_radio', 'periksa_radiologi.kd_dokter', '=', 'dokter_radio.kd_dokter')
                ->select(
                    'reg_periksa.no_rawat',
                    'reg_periksa.no_rkm_medis',
                    'reg_periksa.tgl_registrasi',
                    'pasien.nm_pasien',
                    DB::raw('MAX(dokter_mcu.nm_dokter) as dokter_mcu'),
                    DB::raw('CASE WHEN COUNT(periksa_lab.no_rawat) > 0 THEN "Ya" ELSE "Tidak" END as ada_lab'),
                    DB::raw('MAX(dokter_lab.nm_dokter) as dokter_lab'),
                    DB::raw('CASE WHEN COUNT(periksa_radiologi.no_rawat) > 0 THEN "Ya" ELSE "Tidak" END as ada_radiologi'),
                    DB::raw('MAX(dokter_radio.nm_dokter) as dokter_radiologi')
                );

            if ($start && $end) {
                $query->whereBetween('reg_periksa.tgl_registrasi', [$start, $end]);
            }
            $this->columns = [
                'No Rawat',
                'No RM',
                'Tanggal Registrasi',
                'Nama Pasien',
                'Dokter MCU',
                'Ada Lab?',
                'Dokter Lab',
                'Ada Radiologi?',
                'Dokter Radiologi'
            ];

            // Tambahkan GROUP BY semua kolom non-aggregat
            $query->groupBy(
                'reg_periksa.no_rawat',
                'reg_periksa.no_rkm_medis',
                'reg_periksa.tgl_registrasi',
                'pasien.nm_pasien'
            )->orderBy('reg_periksa.no_rawat', 'desc');
        }


        return $query->get();
    }

    public function headings(): array
    {
        return $this->getColumns();
    }

    protected function getColumns(): array
    {
        switch ($this->jenis) {
            case 'ranap':
                return ['No Rawat', 'No RM', 'Nama Pasien', 'Kode', 'Perawatan', 'Dokter', 'Tanggal', 'Jam', 'Penjamin', 'Ruangan'];
            case 'ralan':
                return ['No Rawat', 'No RM', 'Nama Pasien', 'Kode', 'Perawatan', 'Dokter', 'Tanggal', 'Jam', 'Penjamin', 'Poli'];
            case 'operasi':
                return ['No Rawat', 'No RM', 'Nama Pasien', 'Kode Paket', 'Nama Paket', 'Operator', 'Tanggal', 'Jam', 'Penjamin', 'Ruangan'];
            case 'radiologi':
            case 'lab':
                return ['No Rawat', 'No RM', 'Nama Pasien', 'Kode', 'Perawatan', 'Dokter', 'Tanggal', 'Jam', 'Penjamin', 'Ruangan'];
            case 'mcu':
                return ['No Rawat', 'No RM', 'Tanggal Registrasi', 'Nama Pasien', 'Dokter MCU', 'Ada Lab?', 'Dokter Lab', 'Ada Radiologi?', 'Dokter Radiologi'];
            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Hitung kolom terakhir berdasarkan jumlah heading
                $colCount = count($this->columns);
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

                /* Freeze header */
                $sheet->freezePane('A2');

                /* Auto filter */
                $sheet->setAutoFilter("A1:{$lastColumn}1");

                /* Header background sesuai jumlah heading */
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => [
                            'rgb' => 'D9E1F2'
                        ]
                    ]
                ]);

                /* Border tabel */
                $sheet->getStyle("A1:{$lastColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin'
                            ]
                        ]
                    ]);
            }
        ];
    }
}
