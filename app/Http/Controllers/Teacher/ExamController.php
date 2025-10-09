<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\UserCommit;
use App\Models\UserJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Halaman utama manajemen ujian
     */
    public function index()
    {
        $user = Auth::user();
        
        // Guru memiliki akses penuh ke semua ujian
        $exams = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik
        $totalExams = $exams->count();
        $activeExams = $exams->where('isHidden', 0)->count();
        $completedExams = $exams->where('due', '<', now())->count();
        
        // Hitung total peserta (semua siswa)
        $totalParticipants = \App\Models\User::where('roles_id', 4)->count();

        // Ambil semua kelas dan mata pelajaran
        $classes = Kelas::all();
        $subjects = Mapel::all();

        return view('teacher.exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'exams' => $exams,
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
     * Filter ujian
     */
    public function filter(Request $request)
    {
        $user = Auth::user();
        
        // Guru memiliki akses penuh ke semua ujian
        $query = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel']);

        // Filter berdasarkan kelas
        if ($request->has('filter_class') && $request->filter_class) {
            $query->whereHas('kelasMapel', function($query) use ($request) {
                $query->where('kelas_id', $request->filter_class);
            });
        }

        // Filter berdasarkan mata pelajaran
        if ($request->has('filter_subject') && $request->filter_subject) {
            $query->whereHas('kelasMapel', function($query) use ($request) {
                $query->where('mapel_id', $request->filter_subject);
            });
        }

        // Filter berdasarkan status
        if ($request->has('filter_status') && $request->filter_status) {
            switch ($request->filter_status) {
                case 'active':
                    $query->where('isHidden', 0);
                    break;
                case 'draft':
                    $query->where('isHidden', 1);
                    break;
                case 'completed':
                    $query->where('due', '<', now());
                    break;
            }
        }

        // Filter berdasarkan tipe
        if ($request->has('filter_type') && $request->filter_type) {
            $query->where('tipe', $request->filter_type);
        }

        $exams = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik (guru memiliki akses penuh)
        $totalExams = Ujian::count();
        $activeExams = Ujian::where('isHidden', 0)->count();
        $completedExams = Ujian::where('due', '<', now())->count();
        $totalParticipants = \App\Models\User::where('roles_id', 4)->count();

        // Ambil semua kelas dan mata pelajaran (guru memiliki akses penuh)
        $classes = Kelas::all();
        $subjects = Mapel::all();

        return view('teacher.exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'exams' => $exams,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalExams' => $totalExams,
            'activeExams' => $activeExams,
            'completedExams' => $completedExams,
            'totalParticipants' => $totalParticipants,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_type'])
        ]);
    }

    /**
     * Buat ujian baru
     */
    public function create(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'exam_description' => 'required|string',
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
            ]);

            DB::commit();

            return redirect()->route('teacher.exam-management')
                ->with('success', 'Ujian berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Lihat detail ujian
     */
    public function show($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi bahwa guru memiliki akses ke ujian ini
        $hasAccess = $ujian->kelasMapel->kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        return view('teacher.exam-detail', [
            'title' => 'Detail Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian
        ]);
    }

    /**
     * Edit ujian
     */
    public function edit($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil data kelas dan mata pelajaran
        $classes = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        $subjects = Mapel::whereHas('kelasMapel.kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return view('teacher.exam-edit', [
            'title' => 'Edit Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Update ujian
     */
    public function update(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        $request->validate([
            'exam_title' => 'required|string|max:255',
            'exam_description' => 'required|string',
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

            // Update ujian
            $ujian->update([
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => $tipe,
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden ? 1 : 0,
            ]);

            // Update KelasMapel jika berubah
            if ($ujian->kelasMapel->kelas_id != $request->class_id || 
                $ujian->kelasMapel->mapel_id != $request->subject_id) {
                
                $kelasMapel = KelasMapel::firstOrCreate([
                    'kelas_id' => $request->class_id,
                    'mapel_id' => $request->subject_id,
                ]);

                $ujian->update(['kelas_mapel_id' => $kelasMapel->id]);
            }

            DB::commit();

            return redirect()->route('teacher.exam-management')
                ->with('success', 'Ujian berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus ujian
     */
    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        try {
            DB::beginTransaction();

            // Hapus data terkait
            $ujian->soalUjianMultiple()->delete();
            $ujian->soalUjianEssay()->delete();
            UserCommit::where('ujian_id', $ujian->id)->delete();
            UserJawaban::where('multiple_id', $ujian->id)->orWhere('essay_id', $ujian->id)->delete();

            // Hapus ujian
            $ujian->delete();

            DB::commit();

            return redirect()->route('teacher.exam-management')
                ->with('success', 'Ujian berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus ujian: ' . $e->getMessage());
        }
    }

    /**
     * Lihat hasil ujian
     */
    public function results($id)
    {
        $user = Auth::user();
        $ujian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $ujian->kelasMapel->kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke ujian ini');
        }

        // Ambil hasil ujian
        $results = UserCommit::with(['user'])
            ->where('ujian_id', $ujian->id)
            ->where('status', 'selesai')
            ->get();

        return view('teacher.exam-results', [
            'title' => 'Hasil Ujian: ' . $ujian->name,
            'user' => $user,
            'ujian' => $ujian,
            'results' => $results
        ]);
    }
}
