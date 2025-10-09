<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\UjianProgress;
use App\Models\UjianFeedback;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EnhancedExamController extends Controller
{
    /**
     * Halaman utama manajemen ujian dengan progress tracking
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil ujian berdasarkan kelas yang diajar oleh guru
        $exams = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel', 'progress', 'feedback'])
            ->whereHas('kelasMapel.editorAccess', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $totalExams = $exams->count();
        $activeExams = $exams->where('isHidden', 0)->count();
        $completedExams = $exams->where('due', '<', now())->count();
        
        // Hitung total peserta dan progress
        $totalParticipants = 0;
        $examsWithProgress = [];
        
        foreach ($exams as $exam) {
            $participants = $exam->progress->count();
            $totalParticipants += $participants;
            
            $completed = $exam->progress->where('status', 'completed')->count();
            $inProgress = $exam->progress->where('status', 'in_progress')->count();
            $graded = $exam->progress->where('status', 'graded')->count();
            
            $examsWithProgress[] = [
                'exam' => $exam,
                'participants' => $participants,
                'completed' => $completed,
                'in_progress' => $in_progress,
                'graded' => $graded,
                'completion_rate' => $participants > 0 ? round(($completed / $participants) * 100, 2) : 0
            ];
        }

        // Ambil data kelas dan mata pelajaran untuk form
        $classes = Kelas::whereHas('kelasMapel.editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $subjects = Mapel::whereHas('kelasMapel.editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('teacher.enhanced-exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'exams' => $exams,
            'examsWithProgress' => $examsWithProgress,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalExams' => $totalExams,
            'activeExams' => $activeExams,
            'completedExams' => $completedExams,
            'totalParticipants' => $totalParticipants,
            'filters' => []
        ]);
    }

    /**
     * Buat ujian baru
     */
    public function create(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'exam_description' => 'nullable|string',
            'exam_type' => 'required|string|in:multiple_choice,essay,mixed',
            'class_id' => 'required|exists:kelas,id',
            'subject_id' => 'required|exists:mapels,id',
            'duration' => 'required|integer|min:1|max:300',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date|after:now',
            'is_hidden' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Cari atau buat KelasMapel
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Tentukan tipe ujian
            $tipe = 1; // Default multiple choice
            switch ($request->exam_type) {
                case 'essay':
                    $tipe = 2;
                    break;
                case 'mixed':
                    $tipe = 3;
                    break;
            }

            // Buat ujian
            $ujian = Ujian::create([
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'kelas_mapel_id' => $kelasMapel->id,
                'tipe' => $tipe,
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden ? 1 : 0,
                'max_score' => $request->max_score,
            ]);

            DB::commit();

            return redirect()->route('teacher.enhanced-exam-management.index')
                ->with('success', 'Ujian berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Lihat detail ujian dengan progress dan feedback
     */
    public function show($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel', 'progress.user', 'feedback.teacher'])
            ->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil progress siswa
        $progressData = $ujian->progress()->with('user')->get();
        
        // Hitung statistik progress
        $totalStudents = $progressData->count();
        $notStarted = $progressData->where('status', 'not_started')->count();
        $inProgress = $progressData->where('status', 'in_progress')->count();
        $completed = $progressData->where('status', 'completed')->count();
        $submitted = $progressData->where('status', 'submitted')->count();
        $graded = $progressData->where('status', 'graded')->count();

        // Ambil feedback yang sudah diberikan
        $feedbackData = $ujian->feedback()->with(['user', 'teacher'])->get();

        return view('teacher.enhanced-exam-detail', [
            'title' => 'Detail Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian,
            'progressData' => $progressData,
            'feedbackData' => $feedbackData,
            'stats' => [
                'total_students' => $totalStudents,
                'not_started' => $notStarted,
                'in_progress' => $inProgress,
                'completed' => $completed,
                'submitted' => $submitted,
                'graded' => $graded
            ]
        ]);
    }

    /**
     * Lihat progress siswa untuk ujian tertentu
     */
    public function progress($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil progress semua siswa
        $progressData = UjianProgress::with(['user'])
            ->where('ujian_id', $ujian->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('teacher.exam-progress', [
            'title' => 'Progress Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian,
            'progressData' => $progressData
        ]);
    }

    /**
     * Lihat hasil ujian dan berikan feedback
     */
    public function results($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil hasil ujian yang sudah selesai
        $results = UjianProgress::with(['user', 'ujian'])
            ->where('ujian_id', $ujian->id)
            ->whereIn('status', ['completed', 'submitted', 'graded'])
            ->orderBy('completed_at', 'desc')
            ->get();

        // Ambil feedback yang sudah diberikan
        $feedbackData = UjianFeedback::with(['user', 'teacher'])
            ->where('ujian_id', $ujian->id)
            ->get()
            ->keyBy('user_id');

        // Hitung rata-rata nilai
        $averageScore = $feedbackData->where('score', '>', 0)->avg('score');
        $averageScore = $averageScore ? round($averageScore, 2) : 0;

        return view('teacher.exam-results', [
            'title' => 'Hasil Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian,
            'results' => $results,
            'feedbackData' => $feedbackData,
            'averageScore' => $averageScore
        ]);
    }

    /**
     * Berikan feedback untuk siswa
     */
    public function giveFeedback(Request $request, $ujianId, $userId)
    {
        $user = Auth::user();
        $ujian = Ujian::findOrFail($ujianId);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:1',
            'feedback_text' => 'nullable|string|max:1000',
            'strengths' => 'nullable|string|max:500',
            'weaknesses' => 'nullable|string|max:500',
            'suggestions' => 'nullable|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        try {
            DB::beginTransaction();

            // Cari atau buat feedback
            $feedback = UjianFeedback::updateOrCreate(
                [
                    'ujian_id' => $ujianId,
                    'user_id' => $userId
                ],
                [
                    'teacher_id' => $user->id,
                    'score' => $request->score,
                    'max_score' => $request->max_score,
                    'feedback_text' => $request->feedback_text,
                    'strengths' => $request->strengths,
                    'weaknesses' => $request->weaknesses,
                    'suggestions' => $request->suggestions,
                    'rating' => $request->rating,
                    'status' => 'graded',
                    'graded_at' => now()
                ]
            );

            // Update progress siswa menjadi graded
            UjianProgress::where('ujian_id', $ujianId)
                ->where('user_id', $userId)
                ->update(['status' => 'graded']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil diberikan!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memberikan feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lihat detail progress siswa individual
     */
    public function studentProgress($ujianId, $userId)
    {
        $user = Auth::user();
        $ujian = Ujian::findOrFail($ujianId);
        $student = User::findOrFail($userId);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil progress siswa
        $progress = UjianProgress::with(['user', 'ujian'])
            ->where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->first();

        if (!$progress) {
            abort(404, 'Progress siswa tidak ditemukan');
        }

        // Ambil feedback jika ada
        $feedback = UjianFeedback::with(['teacher'])
            ->where('ujian_id', $ujianId)
            ->where('user_id', $userId)
            ->first();

        return view('teacher.student-progress-detail', [
            'title' => 'Progress Siswa: ' . $student->name,
            'user' => $user,
            'ujian' => $ujian,
            'student' => $student,
            'progress' => $progress,
            'feedback' => $feedback
        ]);
    }

    /**
     * Update status ujian (publish/unpublish)
     */
    public function updateStatus(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        $request->validate([
            'isHidden' => 'required|boolean'
        ]);

        $ujian->update([
            'isHidden' => $request->isHidden
        ]);

        $status = $request->isHidden ? 'disembunyikan' : 'dipublikasikan';
        
        return redirect()->back()
            ->with('success', "Ujian berhasil {$status}!");
    }

    /**
     * Hapus ujian
     */
    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        try {
            DB::beginTransaction();

            // Hapus data terkait
            $ujian->progress()->delete();
            $ujian->feedback()->delete();
            $ujian->soalMultiples()->delete();
            $ujian->soalEssays()->delete();
            $ujian->userUjian()->delete();

            // Hapus ujian
            $ujian->delete();

            DB::commit();

            return redirect()->route('teacher.enhanced-exam-management')
                ->with('success', 'Ujian berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus ujian: ' . $e->getMessage());
        }
    }

    /**
     * Export hasil ujian
     */
    public function exportResults($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->editorAccess()
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil data untuk export
        $results = UjianProgress::with(['user', 'ujian'])
            ->where('ujian_id', $ujian->id)
            ->whereIn('status', ['completed', 'submitted', 'graded'])
            ->get();

        $feedbackData = UjianFeedback::with(['user', 'teacher'])
            ->where('ujian_id', $ujian->id)
            ->get()
            ->keyBy('user_id');

        // TODO: Implement export functionality (Excel/PDF)
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $results
        ]);
    }
}
