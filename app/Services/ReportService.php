<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserTugas;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Mapel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportService
{
    /**
     * Generate transcript PDF for a specific student
     */
    public function generateTranskrip($userId, $semester = null)
    {
        $student = User::with(['kelas', 'userTugas.tugas.kelasMapel.mapel', 'userTugas.penilai'])
            ->findOrFail($userId);

        $query = UserTugas::with(['tugas.kelasMapel.mapel', 'penilai'])
            ->where('user_id', $userId)
            ->whereNotNull('nilai');

        if ($semester) {
            // Filter by semester if provided
            $query->whereHas('tugas', function($q) use ($semester) {
                $q->where('created_at', '>=', $semester['start'])
                  ->where('created_at', '<=', $semester['end']);
            });
        }

        $nilai = $query->orderBy('dinilai_pada', 'desc')->get();

        // Calculate statistics
        $stats = $this->calculateStudentStats($nilai);

        $data = [
            'student' => $student,
            'nilai' => $nilai,
            'stats' => $stats,
            'semester' => $semester,
            'generated_at' => now(),
            'generated_by' => auth()->user()->name ?? 'System'
        ];

        $pdf = Pdf::loadView('reports.transkrip', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Generate class performance report
     */
    public function generateClassReport($kelasId, $mapelId = null)
    {
        $kelas = Kelas::findOrFail($kelasId);
        
        $query = UserTugas::with(['user', 'tugas.kelasMapel.mapel', 'penilai'])
            ->whereHas('user', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->whereNotNull('nilai');

        if ($mapelId) {
            $query->whereHas('tugas.kelasMapel', function($q) use ($mapelId) {
                $q->where('mapel_id', $mapelId);
            });
        }

        $nilai = $query->orderBy('dinilai_pada', 'desc')->get();

        // Group by student
        $studentData = $nilai->groupBy('user_id')->map(function($userTugas) {
            $user = $userTugas->first()->user;
            return [
                'user' => $user,
                'tugas' => $userTugas,
                'stats' => $this->calculateStudentStats($userTugas)
            ];
        });

        $data = [
            'kelas' => $kelas,
            'mapel' => $mapelId ? Mapel::find($mapelId) : null,
            'studentData' => $studentData,
            'classStats' => $this->calculateClassStats($nilai),
            'generated_at' => now(),
            'generated_by' => auth()->user()->name ?? 'System'
        ];

        $pdf = Pdf::loadView('reports.class-performance', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }

    /**
     * Generate teacher grading report
     */
    public function generateTeacherReport($teacherId, $dateFrom = null, $dateTo = null)
    {
        $teacher = User::findOrFail($teacherId);
        
        $query = UserTugas::with(['user', 'tugas.kelasMapel.mapel'])
            ->where('dinilai_oleh', $teacherId)
            ->whereNotNull('nilai');

        if ($dateFrom) {
            $query->where('dinilai_pada', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('dinilai_pada', '<=', $dateTo);
        }

        $nilai = $query->orderBy('dinilai_pada', 'desc')->get();

        $data = [
            'teacher' => $teacher,
            'nilai' => $nilai,
            'stats' => $this->calculateTeacherStats($nilai),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generated_at' => now(),
            'generated_by' => auth()->user()->name ?? 'System'
        ];

        $pdf = Pdf::loadView('reports.teacher-grading', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf;
    }

    /**
     * Calculate student statistics
     */
    private function calculateStudentStats($nilai)
    {
        if ($nilai->isEmpty()) {
            return [
                'total_tugas' => 0,
                'avg_nilai' => 0,
                'highest_nilai' => 0,
                'lowest_nilai' => 0,
                'grade_distribution' => [],
                'completion_rate' => 0
            ];
        }

        $nilaiValues = $nilai->pluck('nilai');
        
        return [
            'total_tugas' => $nilai->count(),
            'avg_nilai' => round($nilaiValues->avg(), 2),
            'highest_nilai' => $nilaiValues->max(),
            'lowest_nilai' => $nilaiValues->min(),
            'grade_distribution' => $this->getGradeDistribution($nilaiValues),
            'completion_rate' => 100, // Since we only count graded tasks
            'with_feedback' => $nilai->where('komentar', '!=', null)->count(),
            'feedback_percentage' => round(($nilai->where('komentar', '!=', null)->count() / $nilai->count()) * 100, 2)
        ];
    }

    /**
     * Calculate class statistics
     */
    private function calculateClassStats($nilai)
    {
        if ($nilai->isEmpty()) {
            return [
                'total_students' => 0,
                'total_tugas' => 0,
                'avg_nilai' => 0,
                'grade_distribution' => []
            ];
        }

        $nilaiValues = $nilai->pluck('nilai');
        $uniqueStudents = $nilai->pluck('user_id')->unique()->count();
        
        return [
            'total_students' => $uniqueStudents,
            'total_tugas' => $nilai->count(),
            'avg_nilai' => round($nilaiValues->avg(), 2),
            'highest_nilai' => $nilaiValues->max(),
            'lowest_nilai' => $nilaiValues->min(),
            'grade_distribution' => $this->getGradeDistribution($nilaiValues),
            'completion_rate' => round(($nilai->count() / ($uniqueStudents * $nilai->pluck('tugas_id')->unique()->count())) * 100, 2)
        ];
    }

    /**
     * Calculate teacher statistics
     */
    private function calculateTeacherStats($nilai)
    {
        if ($nilai->isEmpty()) {
            return [
                'total_graded' => 0,
                'avg_grading_time' => 0,
                'feedback_percentage' => 0,
                'grade_distribution' => []
            ];
        }

        $nilaiValues = $nilai->pluck('nilai');
        
        // Calculate average grading time
        $avgGradingTime = $nilai->map(function($item) {
            if ($item->created_at && $item->dinilai_pada) {
                return $item->created_at->diffInHours($item->dinilai_pada);
            }
            return 0;
        })->avg();

        return [
            'total_graded' => $nilai->count(),
            'avg_nilai' => round($nilaiValues->avg(), 2),
            'avg_grading_time' => round($avgGradingTime, 2),
            'feedback_percentage' => round(($nilai->where('komentar', '!=', null)->count() / $nilai->count()) * 100, 2),
            'grade_distribution' => $this->getGradeDistribution($nilaiValues),
            'unique_students' => $nilai->pluck('user_id')->unique()->count(),
            'unique_tugas' => $nilai->pluck('tugas_id')->unique()->count()
        ];
    }

    /**
     * Get grade distribution
     */
    private function getGradeDistribution($nilaiValues)
    {
        $distribution = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0
        ];

        foreach ($nilaiValues as $nilai) {
            if ($nilai >= 90) $distribution['A']++;
            elseif ($nilai >= 80) $distribution['B']++;
            elseif ($nilai >= 70) $distribution['C']++;
            elseif ($nilai >= 60) $distribution['D']++;
            else $distribution['E']++;
        }

        return $distribution;
    }

    /**
     * Save PDF to storage and return path
     */
    public function savePdfToStorage($pdf, $filename)
    {
        $path = 'reports/' . $filename;
        Storage::put($path, $pdf->output());
        return $path;
    }

    /**
     * Generate filename for report
     */
    public function generateFilename($type, $identifier, $extension = 'pdf')
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        return "{$type}_{$identifier}_{$timestamp}.{$extension}";
    }
}
