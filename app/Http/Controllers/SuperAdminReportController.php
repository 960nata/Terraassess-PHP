<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tugas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TugasProgress;

class SuperAdminReportController extends Controller
{
    /**
     * Menampilkan halaman laporan
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics
        $totalReports = 0; // Placeholder
        $completedReports = 0; // Placeholder
        $pendingReports = 0; // Placeholder
        $failedReports = 0; // Placeholder
        
        // Get user statistics
        $totalStudents = User::where('roles_id', 4)->count();
        $totalTeachers = User::where('roles_id', 3)->count();
        $totalAdmins = User::where('roles_id', 2)->count();
        
        // Get task statistics
        $completedTasks = TugasProgress::where('status', 'completed')->count();
        $pendingTasks = TugasProgress::where('status', 'in_progress')->count();
        $failedTasks = TugasProgress::where('status', 'failed')->count();
        
        // Get class data
        $classes = Kelas::with(['users' => function($query) {
            $query->where('roles_id', 4);
        }])->get();
        
        $classData = [];
        foreach ($classes as $class) {
            $totalStudents = $class->users->count();
            $totalTasks = Tugas::whereHas('KelasMapel', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->count();
            
            $totalExams = 0; // Placeholder for exams
            $completedTasks = TugasProgress::whereHas('user', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->where('status', 'completed')->count();
            
            $averageScore = TugasProgress::whereHas('user', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->where('status', 'completed')->avg('score') ?? 0;
            
            $classData[] = [
                'class' => $class,
                'total_students' => $totalStudents,
                'total_tasks' => $totalTasks,
                'total_exams' => $totalExams,
                'completed_tasks' => $completedTasks,
                'average_score' => $averageScore
            ];
        }
        
        // Get subject data
        $subjects = Mapel::with(['KelasMapel.Kelas'])->get();
        
        $subjectData = [];
        foreach ($subjects as $subject) {
            $totalClasses = $subject->KelasMapel->count();
            $totalTasks = Tugas::whereHas('KelasMapel', function($query) use ($subject) {
                $query->where('mapel_id', $subject->id);
            })->count();
            
            $totalExams = 0; // Placeholder for exams
            $averageScore = TugasProgress::whereHas('tugas.KelasMapel', function($query) use ($subject) {
                $query->where('mapel_id', $subject->id);
            })->where('status', 'completed')->avg('score') ?? 0;
            
            $subjectData[] = [
                'subject' => $subject,
                'total_classes' => $totalClasses,
                'total_tasks' => $totalTasks,
                'total_exams' => $totalExams,
                'average_score' => $averageScore
            ];
        }
        
        return view('superadmin.reports', [
            'title' => 'Laporan',
            'user' => $user,
            'totalReports' => $totalReports,
            'completedReports' => $completedReports,
            'pendingReports' => $pendingReports,
            'failedReports' => $failedReports,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalAdmins' => $totalAdmins,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'failedTasks' => $failedTasks,
            'classData' => $classData,
            'subjectData' => $subjectData,
            'classes' => $classes,
            'subjects' => $subjects,
            'filters' => []
        ]);
    }

    /**
     * Filter laporan
     */
    public function filter(Request $request)
    {
        $user = auth()->user();
        
        // Get statistics
        $totalReports = 0; // Placeholder
        $completedReports = 0; // Placeholder
        $pendingReports = 0; // Placeholder
        $failedReports = 0; // Placeholder
        
        // Get user statistics
        $totalStudents = User::where('roles_id', 4)->count();
        $totalTeachers = User::where('roles_id', 3)->count();
        $totalAdmins = User::where('roles_id', 2)->count();
        
        // Get task statistics
        $completedTasks = TugasProgress::where('status', 'completed')->count();
        $pendingTasks = TugasProgress::where('status', 'in_progress')->count();
        $failedTasks = TugasProgress::where('status', 'failed')->count();
        
        // Apply filters
        $classQuery = Kelas::with(['users' => function($query) {
            $query->where('roles_id', 4);
        }]);
        
        $subjectQuery = Mapel::with(['KelasMapel.Kelas']);
        
        if ($request->filled('filter_class')) {
            $classQuery->where('id', $request->filter_class);
        }
        
        if ($request->filled('filter_subject')) {
            $subjectQuery->where('id', $request->filter_subject);
        }
        
        $classes = $classQuery->get();
        $subjects = $subjectQuery->get();
        
        // Process class data
        $classData = [];
        foreach ($classes as $class) {
            $totalStudents = $class->users->count();
            $totalTasks = Tugas::whereHas('KelasMapel', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->count();
            
            $totalExams = 0; // Placeholder for exams
            $completedTasks = TugasProgress::whereHas('user', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->where('status', 'completed')->count();
            
            $averageScore = TugasProgress::whereHas('user', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->where('status', 'completed')->avg('score') ?? 0;
            
            $classData[] = [
                'class' => $class,
                'total_students' => $totalStudents,
                'total_tasks' => $totalTasks,
                'total_exams' => $totalExams,
                'completed_tasks' => $completedTasks,
                'average_score' => $averageScore
            ];
        }
        
        // Process subject data
        $subjectData = [];
        foreach ($subjects as $subject) {
            $totalClasses = $subject->KelasMapel->count();
            $totalTasks = Tugas::whereHas('KelasMapel', function($query) use ($subject) {
                $query->where('mapel_id', $subject->id);
            })->count();
            
            $totalExams = 0; // Placeholder for exams
            $averageScore = TugasProgress::whereHas('tugas.KelasMapel', function($query) use ($subject) {
                $query->where('mapel_id', $subject->id);
            })->where('status', 'completed')->avg('score') ?? 0;
            
            $subjectData[] = [
                'subject' => $subject,
                'total_classes' => $totalClasses,
                'total_tasks' => $totalTasks,
                'total_exams' => $totalExams,
                'average_score' => $averageScore
            ];
        }
        
        return view('superadmin.reports', [
            'title' => 'Laporan',
            'user' => $user,
            'totalReports' => $totalReports,
            'completedReports' => $completedReports,
            'pendingReports' => $pendingReports,
            'failedReports' => $failedReports,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalAdmins' => $totalAdmins,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'failedTasks' => $failedTasks,
            'classData' => $classData,
            'subjectData' => $subjectData,
            'classes' => $classes,
            'subjects' => $subjects,
            'filters' => $request->all()
        ]);
    }

    /**
     * Export laporan
     */
    public function export(Request $request, $type, $id = null)
    {
        // Implementation for export functionality
        return response()->json(['message' => 'Export feature coming soon']);
    }
}
