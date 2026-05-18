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
        return [
            $row->tanggal_rawat ?? '-',
            $row->no_rawat ?? '-',
            $row->no_rkm_medis ?? '-',
            $row->nm_pasien ?? '-',
            $row->jk ?? '-',
            $row->umur ?? '-',

            // ✅ jangan cast ke integer
            $row->nik ?? 0,

            $row->status ?? '-',
            $row->kasus ?? '-',

            $this->type === 'Ralan'
                ? ($row->nm_poli ?? '-')
                : ($row->nm_kamar ?? '-'),

            $row->nm_dokter ?? '-',
            $row->kode_penyakit ?? '-',

            $row->diagnosa_final
                ?? $row->nama_penyakit
                ?? '-',
        ];
    }

    public function headings(): array
    {
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
            'Poli/Kamar',
            'Dokter',
            'Kode Penyakit',
            'Diagnosa',
        ];
    }

    // 🔥 Format 16 digit number
    public function columnFormats(): array
    {
        return [
            'G' => '0000000000000000',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}