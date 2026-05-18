<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PasienExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithStyles,
    WithColumnFormatting
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function map($row): array
    {
        // =========================
        // EXPORT RALAN
        // =========================
        if ($this->type === 'Ralan') {

            $diagnosa =
                $row->nama_penyakit
                ?? '-';

            return [
                $row->tanggal_rawat ?? '-',
                $row->no_rawat ?? '-',
                $row->no_rkm_medis ?? '-',
                $row->nm_pasien ?? '-',
                $row->jk ?? '-',
                $row->umur ?? '-',

                // biar NIK tidak jadi scientific notation
                $row->nik ?? '',

                $row->status ?? '-',
                $row->kasus ?? '-',

                $row->nm_poli ?? '-',

                $row->nm_dokter ?? '-',
                $row->kode_penyakit ?? '-',
                $diagnosa,
            ];
        }

        // =========================
        // EXPORT RANAP
        // =========================
        $diagnosa =
            $row->diagnosa_final
            ?? $row->nm_penyakit
            ?? '-';

        return [
            $row->tanggal_rawat ?? '-',
            $row->no_rawat ?? '-',
            $row->no_rkm_medis ?? '-',
            $row->nm_pasien ?? '-',
            $row->jk ?? '-',
            $row->umur ?? '-',

            $row->nik ?? '',

            $row->status ?? '-',
            $row->kasus ?? '-',

            $row->nm_kamar ?? '-',
            $row->nm_poli ?? '-',

            $row->nm_dokter ?? '-',
            $row->kode_penyakit ?? '-',
            $diagnosa,
        ];
    }

    public function headings(): array
    {
        // =========================
        // HEADER RALAN
        // =========================
        if ($this->type === 'Ralan') {

            return [
                'Tanggal',
                'No Rawat',
                'No RM',
                'Nama',
                'JK',
                'Umur',
                'NIK',
                'Status',
                'Kasus',
                'Poli',
                'Dokter',
                'Kode Penyakit',
                'Diagnosa',
            ];
        }

        // =========================
        // HEADER RANAP
        // =========================
        return [
            'Tanggal',
            'No Rawat',
            'No RM',
            'Nama',
            'JK',
            'Umur',
            'NIK',
            'Status',
            'Kasus',
            'Kamar',
            'Asal Poli',
            'Dokter',
            'Kode Penyakit',
            'Diagnosa',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // kolom G = NIK
            'G' => '0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true
                ],
            ],
        ];
    }
}