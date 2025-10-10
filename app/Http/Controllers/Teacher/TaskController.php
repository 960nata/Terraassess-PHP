<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\TugasProgress;
use App\Models\TugasFeedback;
use App\Models\TugasKelompok;
use App\Models\AnggotaTugasKelompok;
use App\Models\TugasMultiple;
use App\Models\TugasQuiz;
use App\Models\EditorAccess;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Dashboard pembuatan tugas - menggunakan view superadmin dengan data terbatas untuk teacher
     */
    public function dashboard()
    {
        $user = Auth::user();
        $userRole = $user->roles_id;
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData(request());
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            // Jika teacher tidak memiliki kelas yang ditugaskan, tampilkan data kosong
            return view('superadmin.task-management', [
                'title' => 'Manajemen Tugas',
                'user' => $user,
                'totalTasks' => 0,
                'activeTasks' => 0,
                'completedTasks' => 0,
                'activeClasses' => 0,
                'totalTugas' => 0,
                'tugasPilihanGanda' => 0,
                'tugasEssay' => 0,
                'tugasMandiri' => 0,
                'tugasKelompok' => 0,
                'tugasTerbaru' => collect(),
                'progressSiswa' => collect(),
                'subjects' => collect(),
                'classes' => collect(),
                'tasks' => collect(),
            ]);
        }
        
        // Statistik tugas - hanya untuk kelas yang diajar teacher
        $totalTugas = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', 0)->count();
        $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('due', '<', now())->count();
        $activeClasses = Kelas::whereIn('id', $assignedData['kelas_ids'])->count();
        
        $tugasPilihanGanda = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 1)->count();
        $tugasEssay = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 2)->count();
        $tugasMandiri = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 3)->count();
        $tugasKelompok = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 4)->count();
        
        // Tugas terbaru - hanya dari kelas yang diajar teacher
        $tugasTerbaru = Tugas::with(['KelasMapel.Mapel', 'KelasMapel.Kelas'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Progress siswa - hanya dari tugas yang diajar teacher
        $progressSiswa = TugasProgress::with(['user', 'tugas'])
            ->whereHas('tugas', function($query) use ($assignedData) {
                $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
            })
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();
        
        // Data untuk form - hanya kelas dan mata pelajaran yang diajar teacher
        $subjects = Mapel::whereIn('id', $assignedData['mapel_ids'])->get();
        $classes = Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
        
        // Ambil semua tugas untuk tabel - hanya dari kelas yang diajar teacher
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Menggunakan view superadmin dengan data terbatas untuk teacher
        return view('superadmin.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'totalTasks' => $totalTugas,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'activeClasses' => $activeClasses,
            'totalTugas' => $totalTugas,
            'tugasPilihanGanda' => $tugasPilihanGanda,
            'tugasEssay' => $tugasEssay,
            'tugasMandiri' => $tugasMandiri,
            'tugasKelompok' => $tugasKelompok,
            'tugasTerbaru' => $tugasTerbaru,
            'progressSiswa' => $progressSiswa,
            'subjects' => $subjects,
            'classes' => $classes,
            'tasks' => $tasks,
        ]);
    }

    /**
     * Halaman utama manajemen tugas
     */
    public function management(Request $request)
    {
        $user = Auth::user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.task-management-main', [
                'title' => 'Manajemen Tugas',
                'user' => $user,
                'stats' => ['total' => 0, 'active' => 0, 'completed' => 0, 'multiple_choice' => 0, 'essay' => 0, 'individual' => 0, 'group' => 0],
                'tasks' => collect(),
                'classes' => collect(),
                'subjects' => collect(),
                'activeClasses' => 0,
                'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
            ]);
        }
        
        // Get all tasks for the teacher (for the table) from assigned classes only
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get additional data needed for the shared component - only assigned classes/subjects
        $classes = $this->getTeacherAvailableClasses($request);
        $subjects = $this->getTeacherAvailableSubjects($request);
        
        // Calculate stats from assigned classes only
        $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', false)->count();
        
        $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', true)->count();
        
        $activeClasses = $classes->count();
        
        $stats = [
            'total' => $totalTasks,
            'active' => $activeTasks,
            'completed' => $completedTasks,
            'multiple_choice' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 1)->count(),
            'essay' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 2)->count(),
            'individual' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 3)->count(),
            'group' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 4)->count(),
        ];

        return view('teacher.task-management-main', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'stats' => $stats,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'activeClasses' => $activeClasses,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
        ]);
    }

    /**
     * Filter tasks
     */
    public function filter(Request $request)
    {
        $user = Auth::user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.task-management-main', [
                'title' => 'Manajemen Tugas',
                'user' => $user,
                'stats' => ['total' => 0, 'active' => 0, 'completed' => 0, 'multiple_choice' => 0, 'essay' => 0, 'individual' => 0, 'group' => 0],
                'tasks' => collect(),
                'classes' => collect(),
                'subjects' => collect(),
                'activeClasses' => 0,
                'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
            ]);
        }
        
        // Build query with filters
        $query = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        
        // Apply filters
        if ($request->filled('filter_class')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'active') {
                $query->where('isHidden', false);
            } elseif ($request->filter_status === 'draft') {
                $query->where('isHidden', true);
            } elseif ($request->filter_status === 'completed') {
                $query->where('due', '<', now());
            }
        }
        
        if ($request->filled('filter_difficulty')) {
            $difficultyMap = ['easy' => 1, 'medium' => 2, 'hard' => 3];
            if (isset($difficultyMap[$request->filter_difficulty])) {
                $query->where('tipe', $difficultyMap[$request->filter_difficulty]);
            }
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->get();
        
        // Get additional data needed for the shared component
        $classes = $this->getTeacherAvailableClasses($request);
        $subjects = $this->getTeacherAvailableSubjects($request);
        
        // Calculate stats from assigned classes only
        $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', false)->count();
        
        $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', true)->count();
        
        $activeClasses = $classes->count();
        
        $stats = [
            'total' => $totalTasks,
            'active' => $activeTasks,
            'completed' => $completedTasks,
            'multiple_choice' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 1)->count(),
            'essay' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 2)->count(),
            'individual' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 3)->count(),
            'group' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 4)->count(),
        ];

        return view('teacher.task-management-main', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'stats' => $stats,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'activeClasses' => $activeClasses,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
        ]);
    }

    /**
     * Halaman list semua tugas
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        
        $query = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel', 'TugasProgress'])
            ->whereHas('KelasMapel', function($query) use ($user) {
                $query->whereHas('EditorAccess', function($editorQuery) use ($user) {
                    $editorQuery->where('user_id', $user->id);
                });
            });

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('tipe', $request->type);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->where('isHidden', 0);
                    break;
                case 'completed':
                    $query->where('due', '<', now());
                    break;
                case 'draft':
                    $query->where('isHidden', 1);
                    break;
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

        // Gunakan cache untuk statistik
        $stats = CacheService::getTaskStats($user->id);

        // Ambil data kelas dan mata pelajaran untuk form dengan cache
        $classes = CacheService::getUserClasses($user->id);
        $subjects = CacheService::getUserSubjects($user->id);

        // Hitung kelas aktif
        $activeClasses = $classes->count();

        return view('teacher.task-management', [
            'title' => 'Daftar Tugas',
            'user' => $user,
            'tasks' => $tasks,
            'stats' => $stats,
            'classes' => $classes,
            'subjects' => $subjects,
            'activeClasses' => $activeClasses,
            'filters' => $request->only(['type', 'status', 'search'])
        ]);
    }


    /**
     * Form pembuatan tugas berdasarkan tipe
     */
    public function create($tipe)
    {
        $user = Auth::user();
        
        $tipeTugas = [
            1 => 'Pilihan Ganda',
            2 => 'Esai',
            3 => 'Mandiri',
            4 => 'Kelompok'
        ];

        if (!isset($tipeTugas[$tipe])) {
            return redirect()->route('teacher.tasks.management')
                ->with('error', 'Tipe tugas tidak valid');
        }

        // Ambil kelas dan mata pelajaran yang diajar oleh guru
        $kelas = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return view('teacher.task-create', [
            'title' => 'Buat Tugas ' . $tipeTugas[$tipe],
            'user' => $user,
            'tipe' => $tipe,
            'tipeTugas' => $tipeTugas[$tipe],
            'kelas' => $kelas,
            'mapel' => $mapel
        ]);
    }

    /**
     * Form pembuatan tugas pilihan ganda
     */
    public function createMultipleChoice()
    {
        $user = Auth::user();
        
        // Get class subjects that the teacher has access to
        $classSubjects = KelasMapel::with(['Kelas', 'Mapel'])
            ->whereHas('EditorAccess', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
        
        return view('teacher.create-multiple-choice-questions', [
            'title' => 'Buat Soal Pilihan Ganda',
            'user' => $user,
            'classSubjects' => $classSubjects
        ]);
    }

    /**
     * Form pembuatan tugas esai
     */
    public function createEssay()
    {
        $user = Auth::user();
        
        // Check if user is super admin
        if ($user->roles_id == 1) {
            // Super admin can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
            return view('superadmin.task-create-essay', [
                'title' => 'Buat Tugas Esai',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        } else {
            // Regular teacher access
            $kelas = Kelas::with(['KelasMapel.Mapel'])->whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('teacher.task-create-essay', [
                'title' => 'Buat Tugas Esai',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        }
    }

    /**
     * Form pembuatan tugas mandiri
     */
    public function createIndividual()
    {
        $user = Auth::user();
        
        // Check if user is super admin
        if ($user->roles_id == 1) {
            // Super admin can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
            return view('superadmin.task-create-individual', [
                'title' => 'Buat Tugas Mandiri',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        } else {
            // Regular teacher access
            $kelas = Kelas::whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->with(['KelasMapel.Mapel'])->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('teacher.task-create-individual', [
                'title' => 'Buat Tugas Mandiri',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        }
    }

    /**
     * Form pembuatan tugas kelompok
     */
    public function createGroup()
    {
        $user = Auth::user();
        
        // Check if user is super admin
        if ($user->roles_id == 1) {
            // Super admin can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
            return view('superadmin.task-create-group', [
                'title' => 'Buat Tugas Kelompok',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        } else {
            // Regular teacher access
            $kelas = Kelas::with(['KelasMapel.Mapel'])->whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('teacher.task-create-group', [
                'title' => 'Buat Tugas Kelompok',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        }
    }

    /**
     * Simpan tugas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'tipe' => 'required|integer|in:1,2,3,4',
            'due' => 'nullable|date|after:now',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
            'allow_file_upload' => 'boolean',
            'allow_text_input' => 'boolean',
            'questions' => 'array',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'array|min:2',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
            'questions.*.points' => 'required|integer|min:1',
            'groups' => 'array',
            'groups.*.name' => 'required|string',
            'groups.*.members' => 'array|min:1',
            'groups.*.leader' => 'required|integer',
            'peer_assessment_due' => 'nullable|date|after:due',
            'rubric_items' => 'array',
            'rubric_items.*.item' => 'required|string',
            'rubric_items.*.type' => 'required|in:yes_no,scale,text',
            'rubric_items.*.points' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Cari atau buat KelasMapel
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
            ]);

            // Buat tugas
            $tugas = Tugas::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->name,
                'content' => $request->content,
                'due' => $request->due,
                'tipe' => $request->tipe,
                'isHidden' => 0,
            ]);

            // Buat progress untuk semua siswa di kelas
            $this->createProgressForStudents($tugas, $request->kelas_id);

            // Handle berdasarkan tipe tugas
            switch ($request->tipe) {
                case 1: // Pilihan Ganda
                    $this->createMultipleChoiceQuestions($tugas, $request->questions);
                    break;
                case 2: // Esai
                case 3: // Mandiri
                    $this->createEssayTask($tugas, $request);
                    break;
                case 4: // Kelompok
                    $this->createGroupTask($tugas, $request);
                    break;
            }

            DB::commit();

            // Clear caches after creating task
            CacheService::clearUserTaskCaches($user->id);
            CacheService::clearTaskTypeStatsCache();

            return redirect()->route('teacher.tasks.management')
                ->with('success', 'Tugas berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store multiple choice questions task
     */
    public function storeMultipleChoice(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'class_subject' => 'required|exists:kelas_mapels,id',
            'due_date' => 'required|date|after:now',
            'time_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'is_hidden' => 'boolean',
            'shuffle_questions' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2|max:6',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct_answer' => 'required|string|in:A,B,C,D,E,F',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.category' => 'nullable|string|in:easy,medium,hard'
        ]);

        try {
            DB::beginTransaction();

            // Get the class subject
            $kelasMapel = KelasMapel::findOrFail($request->class_subject);
            
            // Verify teacher has access to this class subject
            $hasAccess = $kelasMapel->EditorAccess()
                ->where('user_id', $user->id)
                ->exists();
                
            if (!$hasAccess) {
                throw new \Exception('Anda tidak memiliki akses ke kelas dan mata pelajaran ini');
            }

            // Create the task
            $tugas = Tugas::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->title,
                'content' => $request->description ?? '',
                'due' => $request->due_date,
                'tipe' => 1, // Multiple choice
                'isHidden' => $request->has('is_hidden') ? 1 : 0,
                'time_limit' => $request->time_limit,
                'shuffle_questions' => $request->has('shuffle_questions') ? 1 : 0,
            ]);

            // Create progress for all students in the class
            $this->createProgressForStudents($tugas, $kelasMapel->kelas_id);

            // Create multiple choice questions
            foreach ($request->questions as $questionData) {
                $tugasMultiple = TugasMultiple::create([
                    'tugas_id' => $tugas->id,
                    'soal' => $questionData['question'],
                    'a' => $questionData['options']['A'] ?? '',
                    'b' => $questionData['options']['B'] ?? '',
                    'c' => $questionData['options']['C'] ?? '',
                    'd' => $questionData['options']['D'] ?? '',
                    'e' => $questionData['options']['E'] ?? '',
                    'jawaban' => $questionData['correct_answer'],
                    'poin' => $questionData['points'],
                    'kategori' => $questionData['category'] ?? 'medium',
                ]);
            }

            DB::commit();

            // Clear caches
            CacheService::clearUserTaskCaches($user->id);
            CacheService::clearTaskTypeStatsCache();

            return redirect()->route('teacher.tasks.management')
                ->with('success', 'Tugas pilihan ganda berhasil dibuat dengan ' . count($request->questions) . ' soal!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail tugas dan penilaian
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $tugas = Tugas::with([
            'KelasMapel.Kelas.users' => function($query) {
                $query->where('roles_id', 3); // Siswa saja
            },
            'TugasProgress.user',
            'TugasFeedback',
            'TugasKelompok.AnggotaTugasKelompok.user',
            'TugasMultiple',
            'TugasQuiz'
        ])->findOrFail($id);

        // Verifikasi bahwa guru memiliki akses ke tugas ini
        $hasAccess = $tugas->KelasMapel->Kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        return view('teacher.task-detail', [
            'title' => 'Detail Tugas: ' . $tugas->name,
            'user' => $user,
            'tugas' => $tugas
        ]);
    }

    /**
     * Simpan nilai dan feedback
     */
    public function grade(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
            'is_group' => 'boolean',
            'group_id' => 'nullable|exists:tugas_kelompoks,id'
        ]);

        try {
            DB::beginTransaction();

            $tugas = Tugas::findOrFail($id);

            if ($request->is_group) {
                // Penilaian tugas kelompok
                $group = TugasKelompok::findOrFail($request->group_id);
                $group->update(['nilai' => $request->score]);
            } else {
                // Penilaian individual
                $progress = TugasProgress::where('tugas_id', $id)
                    ->where('user_id', $request->user_id)
                    ->first();

                if ($progress) {
                    $progress->update([
                        'final_score' => $request->score,
                        'status' => 'graded',
                        'graded_at' => now()
                    ]);
                }
            }

            // Simpan feedback
            if ($request->feedback) {
                TugasFeedback::updateOrCreate(
                    [
                        'tugas_id' => $id,
                        'user_id' => $request->user_id,
                        'group_id' => $request->group_id
                    ],
                    [
                        'feedback' => $request->feedback,
                        'created_by' => Auth::id()
                    ]
                );
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Buat progress untuk semua siswa di kelas
     */
    private function createProgressForStudents($tugas, $kelasId)
    {
        $students = User::where('kelas_id', $kelasId)
            ->where('roles_id', 3)
            ->get();

        foreach ($students as $student) {
            TugasProgress::create([
                'tugas_id' => $tugas->id,
                'user_id' => $student->id,
                'status' => 'not_started',
                'progress_percentage' => 0
            ]);
        }
    }

    /**
     * Buat soal pilihan ganda
     */
    private function createMultipleChoiceQuestions($tugas, $questions)
    {
        foreach ($questions as $questionData) {
            $question = TugasQuiz::create([
                'tugas_id' => $tugas->id,
                'question' => $questionData['question'],
                'points' => $questionData['points']
            ]);

            foreach ($questionData['options'] as $index => $option) {
                TugasMultiple::create([
                    'tugas_quiz_id' => $question->id,
                    'option' => $option,
                    'is_correct' => $index == $questionData['correct_answer']
                ]);
            }
        }
    }

    /**
     * Buat tugas esai/mandiri
     */
    private function createEssayTask($tugas, $request)
    {
        // Simpan konfigurasi tugas esai/mandiri
        $tugas->update([
            'content' => json_encode([
                'allow_file_upload' => $request->allow_file_upload ?? false,
                'allow_text_input' => $request->allow_text_input ?? true,
                'file_types' => $request->file_types ?? ['pdf', 'docx', 'jpg', 'png']
            ])
        ]);
    }

    /**
     * Buat tugas kelompok
     */
    private function createGroupTask($tugas, $request)
    {
        // Simpan konfigurasi tugas kelompok
        $tugas->update([
            'content' => json_encode([
                'peer_assessment_due' => $request->peer_assessment_due,
                'rubric_items' => $request->rubric_items ?? []
            ])
        ]);

        // Buat kelompok
        foreach ($request->groups as $groupData) {
            $group = TugasKelompok::create([
                'tugas_id' => $tugas->id,
                'name' => $groupData['name'],
                'status' => 'active'
            ]);

            // Tambahkan anggota kelompok
            foreach ($groupData['members'] as $memberId) {
                AnggotaTugasKelompok::create([
                    'tugas_kelompok_id' => $group->id,
                    'user_id' => $memberId,
                    'is_leader' => $memberId == $groupData['leader']
                ]);
            }
        }
    }

    /**
     * Edit tugas
     */
    public function edit($id)
    {
        $user = Auth::user();
        $tugas = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])->findOrFail($id);

        // Verifikasi akses
        $hasAccess = $tugas->KelasMapel->Kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $tipeTugas = [
            1 => 'Pilihan Ganda',
            2 => 'Esai',
            3 => 'Mandiri',
            4 => 'Kelompok'
        ];

        $kelas = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return view('teacher.task-edit', [
            'title' => 'Edit Tugas: ' . $tugas->name,
            'user' => $user,
            'tugas' => $tugas,
            'tipe' => $tugas->tipe,
            'tipeTugas' => $tipeTugas[$tugas->tipe],
            'kelas' => $kelas,
            'mapel' => $mapel
        ]);
    }

    /**
     * Update tugas
     */
    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $tugas->KelasMapel->Kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'due' => 'nullable|date|after:now',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            // Update tugas
            $tugas->update([
                'name' => $request->name,
                'content' => $request->content,
                'due' => $request->due,
            ]);

            // Update KelasMapel jika berubah
            if ($tugas->KelasMapel->kelas_id != $request->kelas_id || 
                $tugas->KelasMapel->mapel_id != $request->mapel_id) {
                
                $kelasMapel = KelasMapel::firstOrCreate([
                    'kelas_id' => $request->kelas_id,
                    'mapel_id' => $request->mapel_id,
                ]);

                $tugas->update(['kelas_mapel_id' => $kelasMapel->id]);
            }

            DB::commit();

            // Clear caches after updating task
            CacheService::clearUserTaskCaches($user->id);
            CacheService::clearTaskTypeStatsCache();

            return redirect()->route('teacher.tasks.show', $tugas->id)
                ->with('success', 'Tugas berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tugas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        $hasAccess = $tugas->KelasMapel->Kelas->users()
            ->where('id', $user->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        try {
            DB::beginTransaction();

            // Hapus data terkait
            $tugas->TugasProgress()->delete();
            $tugas->TugasFeedback()->delete();
            $tugas->TugasMultiple()->delete();
            $tugas->TugasQuiz()->delete();
            $tugas->TugasKelompok()->delete();

            // Hapus tugas
            $tugas->delete();

            DB::commit();

            // Clear caches after deleting task
            CacheService::clearUserTaskCaches($user->id);
            CacheService::clearTaskTypeStatsCache();

            return redirect()->route('teacher.tasks.management')
                ->with('success', 'Tugas berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus tugas: ' . $e->getMessage());
        }
    }

    /**
     * Get student work for grading
     */
    public function getStudentWork($tugasId, $studentId)
    {
        $tugas = Tugas::findOrFail($tugasId);
        $student = User::findOrFail($studentId);
        $progress = TugasProgress::where('tugas_id', $tugasId)
            ->where('user_id', $studentId)
            ->first();

        $html = view('teacher.partials.student-work', [
            'tugas' => $tugas,
            'student' => $student,
            'progress' => $progress
        ])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Show task detail with student submissions and management
     */
    public function showTaskDetail($taskId)
    {
        $user = Auth::user();
        
        // Get task with related data
        $task = Tugas::with([
            'KelasMapel.Kelas.users' => function($query) {
                $query->where('roles_id', 3); // Only students
            },
            'KelasMapel.Mapel',
            'TugasProgress' => function($query) {
                $query->with('user');
            },
            'TugasFeedback' => function($query) {
                $query->with('user');
            }
        ])->findOrFail($taskId);

        // Verify teacher has access to this task
        $hasAccess = $task->KelasMapel->Kelas->users->contains('id', $user->id);
        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Get all students in the class
        $students = $task->KelasMapel->Kelas->users->where('roles_id', 3);
        $totalStudents = $students->count();

        // Get submissions data
        $submissions = [];
        foreach ($students as $student) {
            $progress = $task->TugasProgress->where('user_id', $student->id)->first();
            $feedback = $task->TugasFeedback->where('user_id', $student->id)->first();
            
            $submissions[] = [
                'id' => $student->id,
                'student_name' => $student->name,
                'student_id' => $student->student_id ?? 'N/A',
                'status' => $progress ? $progress->status : 'pending',
                'submitted_at' => $progress ? $progress->updated_at : null,
                'score' => $progress ? $progress->score : null,
                'feedback' => $feedback ? $feedback->feedback : null,
            ];
        }

        // Calculate statistics
        $submittedCount = collect($submissions)->where('status', 'submitted')->count();
        $pendingCount = $totalStudents - $submittedCount;
        $gradedCount = collect($submissions)->whereNotNull('score')->count();
        $averageScore = $gradedCount > 0 ? 
            round(collect($submissions)->whereNotNull('score')->avg('score'), 1) : 0;

        return view('teacher.task-detail-management', compact(
            'task',
            'submissions',
            'totalStudents',
            'submittedCount',
            'pendingCount',
            'gradedCount',
            'averageScore'
        ));
    }

    /**
     * Get submission details for grading
     */
    public function getSubmissionDetails($taskId, $studentId)
    {
        $user = Auth::user();
        
        $task = Tugas::with([
            'KelasMapel.Kelas.users' => function($query) {
                $query->where('roles_id', 3);
            }
        ])->findOrFail($taskId);

        // Verify access
        $hasAccess = $task->KelasMapel->Kelas->users->contains('id', $user->id);
        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $student = $task->KelasMapel->Kelas->users->find($studentId);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Get submission data based on task type
        $submissionData = [];
        
        if ($task->tipe == 1) { // Multiple choice
            $submissionData = $this->getMultipleChoiceSubmission($task, $studentId);
        } elseif ($task->tipe == 2) { // Essay
            $submissionData = $this->getEssaySubmission($task, $studentId);
        } elseif ($task->tipe == 3) { // Mandiri
            $submissionData = $this->getMandiriSubmission($task, $studentId);
        }

        return response()->json([
            'student' => $student,
            'submission' => $submissionData,
            'task_type' => $task->tipe
        ]);
    }

    /**
     * Save grade and feedback
     */
    public function saveGrade(Request $request, $taskId, $studentId)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        
        $task = Tugas::with('KelasMapel.Kelas.users')->findOrFail($taskId);

        // Verify access
        $hasAccess = $task->KelasMapel->Kelas->users->contains('id', $user->id);
        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            DB::beginTransaction();

            // Update or create progress record
            $progress = TugasProgress::updateOrCreate(
                [
                    'tugas_id' => $taskId,
                    'user_id' => $studentId,
                ],
                [
                    'status' => 'graded',
                    'score' => $request->score,
                    'updated_at' => now(),
                ]
            );

            // Save feedback if provided
            if ($request->feedback) {
                TugasFeedback::updateOrCreate(
                    [
                        'tugas_id' => $taskId,
                        'user_id' => $studentId,
                    ],
                    [
                        'feedback' => $request->feedback,
                        'teacher_id' => $user->id,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get multiple choice submission data
     */
    private function getMultipleChoiceSubmission($task, $studentId)
    {
        // This would get the student's answers for multiple choice questions
        // Implementation depends on your database structure
        return [
            'type' => 'multiple_choice',
            'answers' => [], // Student's answers
            'questions' => $task->TugasMultiple ?? []
        ];
    }

    /**
     * Get essay submission data
     */
    private function getEssaySubmission($task, $studentId)
    {
        // This would get the student's essay answers
        return [
            'type' => 'essay',
            'answers' => [], // Student's essay answers
            'questions' => $task->TugasQuiz ?? []
        ];
    }

    /**
     * Get mandiri submission data
     */
    private function getMandiriSubmission($task, $studentId)
    {
        // This would get the student's mandiri answers
        return [
            'type' => 'mandiri',
            'answers' => [], // Student's text answers
            'questions' => $task->TugasQuiz ?? []
        ];
    }

    /**
     * Get teacher's assigned data (classes and subjects)
     */
    private function getTeacherAssignedData(Request $request)
    {
        $user = Auth::user();
        
        // Get classes where teacher is assigned
        $kelasMapelIds = KelasMapel::whereHas('Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->pluck('id')->toArray();
        
        return [
            'kelas_mapel_ids' => $kelasMapelIds
        ];
    }

    /**
     * Get teacher's available classes
     */
    private function getTeacherAvailableClasses(Request $request)
    {
        $user = Auth::user();
        
        return Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
    }

    /**
     * Get teacher's available subjects
     */
    private function getTeacherAvailableSubjects(Request $request)
    {
        $user = Auth::user();
        
        return Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
    }
}
