<?php

namespace App\Exports;

use App\Models\EditorAccess;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherAssignmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return EditorAccess::with(['user', 'kelasMapel.kelas', 'kelasMapel.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Penugasan',
            'Nama Guru',
            'Email Guru',
            'Nama Kelas',
            'Tingkat Kelas',
            'Mata Pelajaran',
            'Kode Mata Pelajaran',
            'Tanggal Ditugaskan',
            'Status',
        ];
    }

    /**
     * @param EditorAccess $assignment
     * @return array
     */
    public function map($assignment): array
    {
        return [
            $assignment->id,
            $assignment->user->name ?? 'N/A',
            $assignment->user->email ?? 'N/A',
            $assignment->kelasMapel->kelas->name ?? 'N/A',
            $assignment->kelasMapel->kelas->level ?? 'N/A',
            $assignment->kelasMapel->mapel->name ?? 'N/A',
            $assignment->kelasMapel->mapel->code ?? 'N/A',
            $assignment->created_at->format('d/m/Y H:i:s'),
            'Aktif',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1e293b']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // ID Penugasan
            'B' => 25, // Nama Guru
            'C' => 30, // Email Guru
            'D' => 20, // Nama Kelas
            'E' => 15, // Tingkat Kelas
            'F' => 25, // Mata Pelajaran
            'G' => 20, // Kode Mata Pelajaran
            'H' => 20, // Tanggal Ditugaskan
            'I' => 10, // Status
        ];
    }
}
