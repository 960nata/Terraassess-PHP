<?php

namespace App\Exports;

use App\Models\UserTugas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NilaiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $tugasId;
    protected $kelasId;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($tugasId = null, $kelasId = null, $dateFrom = null, $dateTo = null)
    {
        $this->tugasId = $tugasId;
        $this->kelasId = $kelasId;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = UserTugas::with(['user', 'tugas', 'penilai'])
            ->whereNotNull('nilai');

        if ($this->tugasId) {
            $query->where('tugas_id', $this->tugasId);
        }

        if ($this->kelasId) {
            $query->whereHas('user', function($q) {
                $q->where('kelas_id', $this->kelasId);
            });
        }

        if ($this->dateFrom) {
            $query->where('dinilai_pada', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('dinilai_pada', '<=', $this->dateTo);
        }

        return $query->orderBy('dinilai_pada', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Email',
            'Kelas',
            'Tugas',
            'Nilai',
            'Grade',
            'Feedback/Komentar',
            'Dinilai Oleh',
            'Tanggal Dinilai',
            'Revisi Ke',
            'Status'
        ];
    }

    public function map($userTugas): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $userTugas->user->name,
            $userTugas->user->email,
            $userTugas->user->kelas->name ?? 'Tidak ada kelas',
            $userTugas->tugas->name,
            $userTugas->nilai,
            $this->getGrade($userTugas->nilai),
            $userTugas->komentar ?? 'Tidak ada feedback',
            $userTugas->penilai->name ?? 'Guru',
            $userTugas->dinilai_pada ? $userTugas->dinilai_pada->format('d/m/Y H:i') : '-',
            $userTugas->revisi_ke ?? 0,
            $userTugas->status
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 25,  // Nama Siswa
            'C' => 30,  // Email
            'D' => 15,  // Kelas
            'E' => 30,  // Tugas
            'F' => 8,   // Nilai
            'G' => 8,   // Grade
            'H' => 50,  // Feedback
            'I' => 20,  // Dinilai Oleh
            'J' => 20,  // Tanggal
            'K' => 10,  // Revisi
            'L' => 15,  // Status
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set borders for all cells with data
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Auto-filter
                $sheet->setAutoFilter('A1:' . $lastColumn . $lastRow);

                // Freeze first row
                $sheet->freezePane('A2');

                // Add conditional formatting for grades
                $this->addConditionalFormatting($sheet, $lastRow);
            },
        ];
    }

    private function addConditionalFormatting($sheet, $lastRow)
    {
        // Green for A grade (90-100)
        $sheet->getStyle('F2:F' . $lastRow)->getConditionalStyles()->addConditionalStyle(
            new \PhpOffice\PhpSpreadsheet\Style\Conditional(
                \PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS,
                '>=90',
                null,
                [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C6EFCE']
                    ]
                ]
            )
        );

        // Yellow for B grade (80-89)
        $sheet->getStyle('F2:F' . $lastRow)->getConditionalStyles()->addConditionalStyle(
            new \PhpOffice\PhpSpreadsheet\Style\Conditional(
                \PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS,
                '>=80',
                null,
                [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFEB9C']
                    ]
                ]
            )
        );

        // Red for failing grades (<60)
        $sheet->getStyle('F2:F' . $lastRow)->getConditionalStyles()->addConditionalStyle(
            new \PhpOffice\PhpSpreadsheet\Style\Conditional(
                \PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS,
                '<60',
                null,
                [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC7CE']
                    ]
                ]
            )
        );
    }

    private function getGrade($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }
}
