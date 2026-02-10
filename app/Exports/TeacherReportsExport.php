<?php

namespace App\Exports;

use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\Materi;
use App\Models\User;
use App\Models\UserTugas;
use App\Models\UserUjian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeacherReportsExport implements WithMultipleSheets
{
    protected $assignedData;
    protected $filters;

    public function __construct($assignedData, $filters = [])
    {
        $this->assignedData = $assignedData;
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        return [
            'Tasks Report' => new TasksReportSheet($this->assignedData, $this->filters),
            'Exams Report' => new ExamsReportSheet($this->assignedData, $this->filters),
            'Materials Report' => new MaterialsReportSheet($this->assignedData, $this->filters),
            'Students Report' => new StudentsReportSheet($this->assignedData, $this->filters),
        ];
    }
}

class TasksReportSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $assignedData;
    protected $filters;

    public function __construct($assignedData, $filters = [])
    {
        $this->assignedData = $assignedData;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Tugas::whereIn('kelas_mapel_id', $this->assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->withCount(['userTugas as submitted_count' => function($q) {
                $q->whereIn('status', ['submitted', 'completed', 'graded']);
            }])
            ->withCount(['userTugas as total_assignments'])
            ->withAvg('userTugas', 'nilai');

        // Apply filters
        if (!empty($this->filters['kelas'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('kelas_id', $this->filters['kelas']);
            });
        }

        if (!empty($this->filters['mapel'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('mapel_id', $this->filters['mapel']);
            });
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $tasks = $query->get();

        return $tasks->map(function($task) {
            $completionRate = $task->total_assignments > 0 
                ? round(($task->submitted_count / $task->total_assignments) * 100, 1) 
                : 0;

            return [
                'Task Name' => $task->name,
                'Class' => $task->kelasMapel->kelas->nama_kelas ?? 'N/A',
                'Subject' => $task->kelasMapel->mapel->nama_mapel ?? 'N/A',
                'Deadline' => $task->due ? $task->due->format('d M Y H:i') : 'No deadline',
                'Submitted/Total' => $task->submitted_count . '/' . $task->total_assignments,
                'Completion Rate (%)' => $completionRate,
                'Average Score' => round($task->user_tugas_avg_nilai ?? 0, 1),
                'Status' => $task->isHidden ? 'Completed' : 'Active',
                'Created At' => $task->created_at->format('d M Y H:i')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Task Name',
            'Class',
            'Subject',
            'Deadline',
            'Submitted/Total',
            'Completion Rate (%)',
            'Average Score',
            'Status',
            'Created At'
        ];
    }

    public function title(): string
    {
        return 'Tasks Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3b82f6']
                ]
            ]
        ];
    }
}

class ExamsReportSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $assignedData;
    protected $filters;

    public function __construct($assignedData, $filters = [])
    {
        $this->assignedData = $assignedData;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Ujian::whereIn('kelas_mapel_id', $this->assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->withCount(['userUjian as participants_count' => function($q) {
                $q->whereIn('status', ['completed', 'graded']);
            }])
            ->withCount(['userUjian as total_participants'])
            ->withAvg('userUjian', 'nilai');

        // Apply filters
        if (!empty($this->filters['kelas'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('kelas_id', $this->filters['kelas']);
            });
        }

        if (!empty($this->filters['mapel'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('mapel_id', $this->filters['mapel']);
            });
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $exams = $query->get();

        return $exams->map(function($exam) {
            $participationRate = $exam->total_participants > 0 
                ? round(($exam->participants_count / $exam->total_participants) * 100, 1) 
                : 0;

            return [
                'Exam Name' => $exam->name,
                'Class' => $exam->kelasMapel->kelas->nama_kelas ?? 'N/A',
                'Subject' => $exam->kelasMapel->mapel->nama_mapel ?? 'N/A',
                'Date' => $exam->created_at->format('d M Y H:i'),
                'Participants' => $exam->participants_count . '/' . $exam->total_participants,
                'Participation Rate (%)' => $participationRate,
                'Average Score' => round($exam->user_ujian_avg_nilai ?? 0, 1),
                'Status' => $exam->isHidden ? 'Finished' : 'Ongoing',
                'Created At' => $exam->created_at->format('d M Y H:i')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Exam Name',
            'Class',
            'Subject',
            'Date',
            'Participants',
            'Participation Rate (%)',
            'Average Score',
            'Status',
            'Created At'
        ];
    }

    public function title(): string
    {
        return 'Exams Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10b981']
                ]
            ]
        ];
    }
}

class MaterialsReportSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $assignedData;
    protected $filters;

    public function __construct($assignedData, $filters = [])
    {
        $this->assignedData = $assignedData;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Materi::whereIn('kelas_mapel_id', $this->assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel']);

        // Apply filters
        if (!empty($this->filters['kelas'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('kelas_id', $this->filters['kelas']);
            });
        }

        if (!empty($this->filters['mapel'])) {
            $query->whereHas('kelasMapel', function($q) {
                $q->whereIn('mapel_id', $this->filters['mapel']);
            });
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        $materials = $query->get();

        return $materials->map(function($material) {
            return [
                'Material Name' => $material->name,
                'Class' => $material->kelasMapel->kelas->nama_kelas ?? 'N/A',
                'Subject' => $material->kelasMapel->mapel->nama_mapel ?? 'N/A',
                'Type' => ucfirst($material->file_type),
                'File Name' => $material->file_materi ?? 'Text Content',
                'Description' => $material->deskripsi ?? 'No description',
                'Status' => $material->isHidden ? 'Hidden' : 'Active',
                'Upload Date' => $material->created_at->format('d M Y H:i')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Material Name',
            'Class',
            'Subject',
            'Type',
            'File Name',
            'Description',
            'Status',
            'Upload Date'
        ];
    }

    public function title(): string
    {
        return 'Materials Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f59e0b']
                ]
            ]
        ];
    }
}

class StudentsReportSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $assignedData;
    protected $filters;

    public function __construct($assignedData, $filters = [])
    {
        $this->assignedData = $assignedData;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::whereIn('kelas_id', $this->assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->with('kelas');

        // Apply filters
        if (!empty($this->filters['kelas'])) {
            $query->whereIn('kelas_id', $this->filters['kelas']);
        }

        $students = $query->get();

        return $students->map(function($student) {
            $tasksCompleted = UserTugas::where('user_id', $student->id)
                ->whereIn('status', ['completed', 'graded'])
                ->whereHas('tugas.kelasMapel', function($q) {
                    $q->whereIn('id', $this->assignedData['kelas_mapel_ids']);
                })
                ->count();

            $examsCompleted = UserUjian::where('user_id', $student->id)
                ->whereIn('status', ['completed', 'graded'])
                ->whereHas('ujian.kelasMapel', function($q) {
                    $q->whereIn('id', $this->assignedData['kelas_mapel_ids']);
                })
                ->count();

            $avgScore = UserTugas::where('user_id', $student->id)
                ->whereIn('status', ['completed', 'graded'])
                ->whereHas('tugas.kelasMapel', function($q) {
                    $q->whereIn('id', $this->assignedData['kelas_mapel_ids']);
                })
                ->avg('nilai') ?? 0;

            $status = $avgScore >= 75 ? 'Excellent' : 
                     ($avgScore >= 60 ? 'Good' : 'Needs Improvement');

            return [
                'Student Name' => $student->name,
                'Class' => $student->kelas->nama_kelas ?? 'N/A',
                'Email' => $student->email,
                'Tasks Completed' => $tasksCompleted,
                'Exams Completed' => $examsCompleted,
                'Average Score' => round($avgScore, 1),
                'Status' => $status,
                'Last Activity' => $student->updated_at->format('d M Y H:i')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Class',
            'Email',
            'Tasks Completed',
            'Exams Completed',
            'Average Score',
            'Status',
            'Last Activity'
        ];
    }

    public function title(): string
    {
        return 'Students Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8b5cf6']
                ]
            ]
        ];
    }
}
