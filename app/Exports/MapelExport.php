<?php

namespace App\Exports;

use App\Models\Mapel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Fill as Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MapelExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function Collection()
    {
        return Mapel::withCount(['kelasMapel', 'materi', 'tugas', 'ujian'])
            ->get(['id', 'name', 'deskripsi', 'gambar', 'created_at', 'updated_at']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Mata Pelajaran',
            'Deskripsi',
            'Gambar',
            'Total Kelas',
            'Total Materi',
            'Total Tugas',
            'Total Ujian',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($mapel): array
    {
        return [
            $mapel->id,
            $mapel->name,
            $mapel->deskripsi ?? '-',
            $mapel->gambar ? 'Ada' : 'Tidak Ada',
            $mapel->kelas_mapel_count,
            $mapel->materi_count,
            $mapel->tugas_count,
            $mapel->ujian_count,
            $mapel->created_at->format('d/m/Y H:i'),
            $mapel->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama Mata Pelajaran
            'C' => 40,  // Deskripsi
            'D' => 12,  // Gambar
            'E' => 12,  // Total Kelas
            'F' => 12,  // Total Materi
            'G' => 12,  // Total Tugas
            'H' => 12,  // Total Ujian
            'I' => 18,  // Tanggal Dibuat
            'J' => 18,  // Tanggal Diperbarui
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            'A1:J1' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E86AB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Data rows styling
            'A2:J1000' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
            // Alternating row colors
            'A2:J2' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8F9FA'],
                ],
            ],
        ];
    }
}
