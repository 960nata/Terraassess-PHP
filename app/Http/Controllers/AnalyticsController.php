<?php

namespace App\Http\Controllers;

use App\Models\UserTugas;
use App\Models\Tugas;
use App\Models\User;
use App\Models\NilaiHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display grading analytics dashboard
     */
    public function gradingAnalytics()
    {
        $user = Auth::user();
        
        // Check if user has permission to view analytics
        if (!in_array($user->roles_id, [1, 2, 3])) { // Superadmin, Admin, Teacher
            abort(403, 'Unauthorized access');
        }

        $stats = [
            'avg_grading_time' => $this->getAverageGradingTime(),
            'nilai_distribution' => $this->getNilaiDistribution(),
            'teacher_performance' => $this->getTeacherPerformance(),
            'student_trends' => $this->getStudentTrends(),
            'grading_frequency' => $this->getGradingFrequency(),
            'feedback_quality' => $this->getFeedbackQuality(),
            'revision_stats' => $this->getRevisionStats(),
        ];

        return view('analytics.grading', compact('stats'));
    }

    /**
     * Get average grading time in hours
     */
    private function getAverageGradingTime()
    {
        $result = UserTugas::selectRaw('
            AVG(TIMESTAMPDIFF(HOUR, created_at, dinilai_pada)) as avg_hours
        ')->whereNotNull('dinilai_pada')->first();

        return round($result->avg_hours ?? 0, 2);
    }

    /**
     * Get nilai distribution by grade ranges
     */
    private function getNilaiDistribution()
    {
        return UserTugas::selectRaw('
            CASE 
                WHEN nilai >= 90 THEN "A (90-100)"
                WHEN nilai >= 80 THEN "B (80-89)"
                WHEN nilai >= 70 THEN "C (70-79)"
                WHEN nilai >= 60 THEN "D (60-69)"
                ELSE "E (0-59)"
            END as grade,
            COUNT(*) as count,
            ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM user_tugas WHERE nilai IS NOT NULL), 2) as percentage
        ')
        ->whereNotNull('nilai')
        ->groupBy('grade')
        ->orderByRaw('
            CASE 
                WHEN nilai >= 90 THEN 1
                WHEN nilai >= 80 THEN 2
                WHEN nilai >= 70 THEN 3
                WHEN nilai >= 60 THEN 4
                ELSE 5
            END
        ')
        ->get();
    }

    /**
     * Get teacher performance metrics
     */
    private function getTeacherPerformance()
    {
        return UserTugas::selectRaw('
            u.name as teacher_name,
            COUNT(*) as total_graded,
            AVG(ut.nilai) as avg_nilai,
            COUNT(CASE WHEN ut.komentar IS NOT NULL AND ut.komentar != "" THEN 1 END) as with_feedback,
            ROUND(COUNT(CASE WHEN ut.komentar IS NOT NULL AND ut.komentar != "" THEN 1 END) * 100.0 / COUNT(*), 2) as feedback_percentage
        ')
        ->from('user_tugas as ut')
        ->join('users as u', 'ut.dinilai_oleh', '=', 'u.id')
        ->whereNotNull('ut.nilai')
        ->whereNotNull('ut.dinilai_oleh')
        ->groupBy('ut.dinilai_oleh', 'u.name')
        ->orderBy('total_graded', 'desc')
        ->limit(10)
        ->get();
    }

    /**
     * Get student performance trends
     */
    private function getStudentTrends()
    {
        return UserTugas::selectRaw('
            DATE(dinilai_pada) as grading_date,
            COUNT(*) as total_graded,
            AVG(nilai) as avg_nilai,
            COUNT(CASE WHEN nilai >= 80 THEN 1 END) as high_scores,
            COUNT(CASE WHEN nilai < 60 THEN 1 END) as low_scores
        ')
        ->whereNotNull('dinilai_pada')
        ->whereNotNull('nilai')
        ->where('dinilai_pada', '>=', now()->subDays(30))
        ->groupBy('grading_date')
        ->orderBy('grading_date', 'desc')
        ->get();
    }

    /**
     * Get grading frequency by day of week
     */
    private function getGradingFrequency()
    {
        return UserTugas::selectRaw('
            DAYNAME(dinilai_pada) as day_name,
            DAYOFWEEK(dinilai_pada) as day_number,
            COUNT(*) as grading_count
        ')
        ->whereNotNull('dinilai_pada')
        ->where('dinilai_pada', '>=', now()->subDays(30))
        ->groupBy('day_name', 'day_number')
        ->orderBy('day_number')
        ->get();
    }

    /**
     * Get feedback quality metrics
     */
    private function getFeedbackQuality()
    {
        $totalWithFeedback = UserTugas::whereNotNull('komentar')
            ->where('komentar', '!=', '')
            ->count();

        $totalGraded = UserTugas::whereNotNull('nilai')->count();

        $avgFeedbackLength = UserTugas::whereNotNull('komentar')
            ->where('komentar', '!=', '')
            ->selectRaw('AVG(CHAR_LENGTH(komentar)) as avg_length')
            ->first();

        return [
            'total_with_feedback' => $totalWithFeedback,
            'total_graded' => $totalGraded,
            'feedback_percentage' => $totalGraded > 0 ? round(($totalWithFeedback / $totalGraded) * 100, 2) : 0,
            'avg_feedback_length' => round($avgFeedbackLength->avg_length ?? 0, 0)
        ];
    }

    /**
     * Get revision statistics
     */
    private function getRevisionStats()
    {
        $totalRevisions = NilaiHistory::count();
        $avgRevisionsPerTask = UserTugas::selectRaw('AVG(revisi_ke) as avg_revisions')
            ->where('revisi_ke', '>', 0)
            ->first();

        $revisionReasons = NilaiHistory::selectRaw('
            alasan_revisi,
            COUNT(*) as count
        ')
        ->whereNotNull('alasan_revisi')
        ->groupBy('alasan_revisi')
        ->orderBy('count', 'desc')
        ->limit(5)
        ->get();

        return [
            'total_revisions' => $totalRevisions,
            'avg_revisions_per_task' => round($avgRevisionsPerTask->avg_revisions ?? 0, 2),
            'top_revision_reasons' => $revisionReasons
        ];
    }

    /**
     * Get analytics data as JSON for charts
     */
    public function getAnalyticsData(Request $request)
    {
        $type = $request->get('type', 'distribution');
        
        switch ($type) {
            case 'distribution':
                return response()->json($this->getNilaiDistribution());
            case 'trends':
                return response()->json($this->getStudentTrends());
            case 'frequency':
                return response()->json($this->getGradingFrequency());
            case 'performance':
                return response()->json($this->getTeacherPerformance());
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }

    /**
     * Export analytics report
     */
    public function exportAnalytics(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // This would typically use Laravel Excel
        // For now, return a simple response
        return response()->json([
            'message' => 'Export functionality will be implemented with Laravel Excel',
            'format' => $format
        ]);
    }
}
