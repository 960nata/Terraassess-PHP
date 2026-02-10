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
use App\Models\TugasMandiri;
use App\Models\DataSiswa;
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
        
        // Untuk teacher (roles_id = 3), berikan akses penuh meskipun tidak ada EditorAccess
        if ($user->roles_id == 3 && (!$assignedData || empty($assignedData['kelas_mapel_ids']))) {
            // Teacher tanpa EditorAccess tetap bisa akses, tapi dengan data terbatas
            $assignedData = [
                'kelas_mapel_ids' => [],
                'kelas_ids' => [],
                'mapel_ids' => []
            ];
        }
        
        if ($user->roles_id != 3 && (!$assignedData || empty($assignedData['kelas_mapel_ids']))) {
            // Jika non-teacher tidak memiliki kelas yang ditugaskan, tampilkan data kosong
            return view('admin.task-management', [
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
        
        // Statistik tugas - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            // Teacher: show all tasks created by them
            $totalTugas = Tugas::where('created_by', $user->id)->count();
            $activeTasks = Tugas::where('created_by', $user->id)->where('isHidden', 0)->count();
            $completedTasks = Tugas::where('created_by', $user->id)->where('due', '<', now())->count();
            $activeClasses = Kelas::count(); // All classes for teacher
            $tugasPilihanGanda = Tugas::where('created_by', $user->id)->where('tipe', 1)->count();
            $tugasEssay = Tugas::where('created_by', $user->id)->where('tipe', 2)->count();
            $tugasMandiri = Tugas::where('created_by', $user->id)->where('tipe', 3)->count();
            $tugasKelompok = Tugas::where('created_by', $user->id)->where('tipe', 4)->count();
        } else {
            // Other roles: use assigned data
            $totalTugas = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', 0)->count();
            $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('due', '<', now())->count();
            $activeClasses = Kelas::whereIn('id', $assignedData['kelas_ids'])->count();
            $tugasPilihanGanda = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 1)->count();
            $tugasEssay = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 2)->count();
            $tugasMandiri = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 3)->count();
            $tugasKelompok = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 4)->count();
        }
        
        // Tugas terbaru - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            $tugasTerbaru = Tugas::with(['kelasMapel.mapel', 'kelasMapel.kelas'])
                ->where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $tugasTerbaru = Tugas::with(['kelasMapel.mapel', 'kelasMapel.kelas'])
                ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Progress siswa - untuk guru: dari semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            $progressSiswa = TugasProgress::with(['user', 'tugas'])
                ->whereHas('tugas', function($query) use ($user) {
                    $query->where('created_by', $user->id);
                })
                ->where('status', 'submitted')
                ->orderBy('submitted_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $progressSiswa = TugasProgress::with(['user', 'tugas'])
                ->whereHas('tugas', function($query) use ($assignedData) {
                    $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
                })
                ->where('status', 'submitted')
                ->orderBy('submitted_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        // Data untuk form - untuk guru: semua kelas dan mata pelajaran, untuk role lain: berdasarkan yang ditugaskan
        if ($user->roles_id == 3) {
            $subjects = Mapel::all();
            $classes = Kelas::all();
        } else {
            $subjects = Mapel::whereIn('id', $assignedData['mapel_ids'])->get();
            $classes = Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
        }
        
        // Ambil semua tugas untuk tabel - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            $tasks = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $tasks = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Menggunakan view admin (shared) untuk semua role
        return view('admin.task-management', [
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
            'filters' => request()->only(['filter_class', 'filter_subject', 'filter_status']),
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
        
        // Untuk teacher (roles_id = 3), berikan akses penuh meskipun tidak ada EditorAccess
        if ($user->roles_id == 3 && (!$assignedData || empty($assignedData['kelas_mapel_ids']))) {
            // Teacher tanpa EditorAccess tetap bisa akses, tapi dengan data terbatas
            $assignedData = [
                'kelas_mapel_ids' => [],
                'kelas_ids' => [],
                'mapel_ids' => []
            ];
        }
        
        if ($user->roles_id != 3 && (!$assignedData || empty($assignedData['kelas_mapel_ids']))) {
            return view('admin.task-management', [
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
                'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
            ]);
        }
        
        // Build query with filters - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            $query = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->where('created_by', $user->id);
        } else {
            $query = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        }
        
        // Apply filters
        if ($request->filled('filter_class')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
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
        
        // Get additional data needed for the shared component - untuk guru: semua data, untuk role lain: berdasarkan yang ditugaskan
        if ($user->roles_id == 3) {
            $classes = Kelas::all();
            $subjects = Mapel::all();
        } else {
            $classes = $this->getTeacherAvailableClasses($request);
            $subjects = $this->getTeacherAvailableSubjects($request);
        }
        
        // Calculate stats - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan kelas yang ditugaskan
        if ($user->roles_id == 3) {
            $totalTasks = Tugas::where('created_by', $user->id)->count();
            $activeTasks = Tugas::where('created_by', $user->id)->where('isHidden', false)->count();
            $completedTasks = Tugas::where('created_by', $user->id)->where('isHidden', true)->count();
            $activeClasses = $classes->count();
            
            $stats = [
                'total' => $totalTasks,
                'active' => $activeTasks,
                'completed' => $completedTasks,
                'multiple_choice' => Tugas::where('created_by', $user->id)->where('tipe', 1)->count(),
                'essay' => Tugas::where('created_by', $user->id)->where('tipe', 2)->count(),
                'individual' => Tugas::where('created_by', $user->id)->where('tipe', 3)->count(),
                'group' => Tugas::where('created_by', $user->id)->where('tipe', 4)->count(),
            ];
        } else {
            $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', false)->count();
            $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', true)->count();
            $activeClasses = $classes->count();
            
            $stats = [
                'total' => $totalTasks,
                'active' => $activeTasks,
                'completed' => $completedTasks,
                'multiple_choice' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 1)->count(),
                'essay' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 2)->count(),
                'individual' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 3)->count(),
                'group' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('tipe', 4)->count(),
            ];
        }

        return view('admin.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'totalTasks' => $stats['total'],
            'activeTasks' => $stats['active'],
            'completedTasks' => $stats['completed'],
            'activeClasses' => $activeClasses,
            'totalTugas' => $stats['total'],
            'tugasPilihanGanda' => $stats['multiple_choice'],
            'tugasEssay' => $stats['essay'],
            'tugasMandiri' => $stats['individual'],
            'tugasKelompok' => $stats['group'],
            'tugasTerbaru' => collect(),
            'progressSiswa' => collect(),
            'subjects' => $subjects,
            'classes' => $classes,
            'tasks' => $tasks,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
        ]);
    }

    /**
     * Halaman list semua tugas
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        
        // Build query - untuk guru: semua tugas yang dibuat, untuk role lain: berdasarkan EditorAccess
        if ($user->roles_id == 3) {
            $query = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel', 'tugasProgress'])
                ->where('created_by', $user->id);
        } else {
            $query = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel', 'tugasProgress'])
                ->whereHas('kelasMapel', function($query) use ($user) {
                    $query->whereHas('editorAccess', function($editorQuery) use ($user) {
                        $editorQuery->where('user_id', $user->id);
                    });
                });
        }

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

        $tasks = $query->orderBy('created_at', 'desc')->paginate(50);

        // Gunakan cache untuk statistik
        $stats = CacheService::getTaskStats($user->id);

        // Ambil data kelas dan mata pelajaran untuk form - untuk guru: semua data, untuk role lain: dengan cache
        if ($user->roles_id == 3) {
            $classes = Kelas::all();
            $subjects = Mapel::all();
        } else {
            $classes = CacheService::getUserClasses($user->id);
            $subjects = CacheService::getUserSubjects($user->id);
        }

        // Hitung kelas aktif
        $activeClasses = $classes->count();

        return view('superadmin.task-management', [
            'title' => 'Daftar Tugas',
            'user' => $user,
            'totalTasks' => $stats['total'] ?? 0,
            'activeTasks' => $stats['active'] ?? 0,
            'completedTasks' => $stats['completed'] ?? 0,
            'activeClasses' => $activeClasses,
            'totalTugas' => $stats['total'] ?? 0,
            'tugasPilihanGanda' => $stats['multiple_choice'] ?? 0,
            'tugasEssay' => $stats['essay'] ?? 0,
            'tugasMandiri' => $stats['individual'] ?? 0,
            'tugasKelompok' => $stats['group'] ?? 0,
            'tugasTerbaru' => collect(),
            'progressSiswa' => collect(),
            'subjects' => $subjects,
            'classes' => $classes,
            'tasks' => $tasks,
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
            // Determine redirect route based on role
            if ($user->roles_id == 1) {
                $route = 'superadmin.task-management';
            } elseif ($user->roles_id == 2) {
                $route = 'admin.task-management';
            } else {
                $route = 'teacher.tasks';
            }
            
            return redirect()->route($route)
                ->with('error', 'Tipe tugas tidak valid');
        }

        // Get all necessary data
        $kelas = Kelas::orderBy('name')->get();
        $mapels = Mapel::orderBy('name')->get();
        
        // Get KelasMapel based on role - teachers now get same access as superadmin
        if ($user->roles_id == 1 || $user->roles_id == 2 || $user->roles_id == 3) {
            // Superadmin, Admin, and Teachers: all KelasMapel
            $kelasMapels = KelasMapel::with(['kelas', 'mapel', 'pengajar'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Other roles: only their KelasMapel
            $kelasMapels = KelasMapel::with(['kelas', 'mapel', 'pengajar'])
                ->where('pengajar_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Check if kelasMapels is empty
        if ($kelasMapels->isEmpty()) {
            // Determine redirect route based on role
            if ($user->roles_id == 1) {
                $route = 'superadmin.task-management';
            } elseif ($user->roles_id == 2) {
                $route = 'admin.task-management';
            } else {
                $route = 'teacher.tasks';
            }
            
            return redirect()->route($route)
                ->with('error', 'Anda belum memiliki kelas mapel. Silakan hubungi admin untuk assign kelas mapel terlebih dahulu.');
        }

        // Routes are already mapped directly to specific methods, no need to redirect
        // This method is only used for /create/{tipe} route, not the specific create routes
        // Determine redirect route based on role
        if ($user->roles_id == 1) {
            $route = 'superadmin.task-management';
        } elseif ($user->roles_id == 2) {
            $route = 'admin.task-management';
        } else {
            $route = 'teacher.tasks';
        }
        
        return redirect()->route($route)
            ->with('error', 'Please use the specific create task buttons');
    }

    /**
     * Form pembuatan tugas pilihan ganda
     */
    public function createMultipleChoice()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user is super admin, admin, or teacher - all get same access
        if ($user->roles_id == 1 || $user->roles_id == 2 || $user->roles_id == 3) {
            // Super admin, admin, and teachers can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
            return view('superadmin.task-create-multiple-choice', [
                'title' => 'Buat Tugas Pilihan Ganda',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        } else {
            // Fallback for other roles
            $kelas = Kelas::whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->with(['KelasMapel.Mapel'])->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('teacher.task-create-multiple-choice', [
                'title' => 'Buat Tugas Pilihan Ganda',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        }
    }

    /**
     * Form pembuatan tugas esai
     */
    public function createEssay()
    {
        $user = Auth::user();
        
        $tipeTugas = [
            1 => 'Pilihan Ganda',
            2 => 'Esai',
            3 => 'Mandiri',
            4 => 'Kelompok'
        ];

        // Check if user is super admin, admin, or teacher - all get same access
        if ($user->roles_id == 1 || $user->roles_id == 2 || $user->roles_id == 3) {
            // Super admin, admin, and teachers can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
            return view('superadmin.task-create-essay', [
                'title' => 'Buat Tugas Esai',
                'user' => $user,
                'tipe' => 2,  // 2 = Essay
                'tipeTugas' => $tipeTugas[2],  // 'Esai'
                'kelas' => $kelas,
                'mapel' => $mapel
            ]);
        } else {
            // Fallback for other roles
            $kelas = Kelas::whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->with(['KelasMapel.Mapel'])->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('teacher.task-create-essay', [
                'title' => 'Buat Tugas Esai',
                'user' => $user,
                'tipe' => 2,  // 2 = Essay
                'tipeTugas' => $tipeTugas[2],  // 'Esai'
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
        
        // Check if user is super admin, admin, or teacher - all get same access
        if ($user->roles_id == 1 || $user->roles_id == 2 || $user->roles_id == 3) {
            // Super admin, admin, and teachers can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel'])->get();
            $mapel = Mapel::all();
            
        return view('shared.task-create-individual', [
            'title' => 'Buat Tugas Mandiri',
            'user' => $user,
            'kelas' => $kelas,
            'mapel' => $mapel
        ]);
        } else {
            // Fallback for other roles
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
        
        // Check if user is super admin, admin, or teacher - all get same access
        if ($user->roles_id == 1 || $user->roles_id == 2 || $user->roles_id == 3) {
            // Super admin, admin, and teachers can access all classes and subjects
            $kelas = Kelas::with(['KelasMapel.Mapel', 'tugasKelompoks' => function($query) {
                $query->where('is_template', true)
                      ->with(['anggotaTugasKelompok.user']);
            }])->get();
            $mapel = Mapel::all();
            
            // Get existing groups for dropdown
            $groups = TugasKelompok::with('AnggotaTugasKelompok')->get();
            
            return view('shared.task-create-group', [
                'title' => 'Buat Tugas Kelompok',
                'user' => $user,
                'kelas' => $kelas,
                'mapel' => $mapel,
                'groups' => $groups
            ]);
        } else {
            // Fallback for other roles
            $kelas = Kelas::with(['KelasMapel.Mapel', 'tugasKelompoks' => function($query) {
                $query->where('is_template', true)
                      ->with(['anggotaTugasKelompok.user']);
            }])->whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            $mapel = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

            return view('shared.task-create-group', [
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
        $user = auth()->user();

        // Validasi field yang dikirim langsung dari form
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
            'tipe' => 'required|integer|in:1,2,3,4',
            'due' => 'required|date|after:now',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'isHidden' => 'nullable|boolean',
            // Group task validation
            'is_new_group' => 'required_if:tipe,4|boolean',
            'group_names' => 'nullable|array',
            'group_names.*' => 'required|string|min:2|max:255',
            'group_members' => 'nullable|array',
            'group_members.*' => 'required|array|min:2',
            'group_members.*.*' => 'required|exists:users,id',
            'group_leaders' => 'nullable|array',
            'group_leaders.*' => 'required|exists:users,id',
            'existing_group_id' => 'required_if:is_new_group,0|nullable|exists:tugas_kelompoks,id',
            // Peer assessment validation
            'enable_peer_assessment' => 'nullable|boolean',
            'peer_assessment_due' => 'required_if:enable_peer_assessment,1|nullable|date|after:due',
            'scale_labels' => 'required_if:enable_peer_assessment,1|array|min:2',
            'scale_labels.*' => 'required|string|max:50',
            'scale_points' => 'required_if:enable_peer_assessment,1|array|min:2',
            'scale_points.*' => 'required|integer|min:0'
        ], [
            'name.required' => 'Judul tugas wajib diisi',
            'name.min' => 'Judul tugas minimal 3 karakter',
            'kelas_id.required' => 'Kelas Tujuan wajib dipilih',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid',
            'mapel_id.required' => 'Mata Pelajaran wajib dipilih',
            'mapel_id.exists' => 'Mata Pelajaran yang dipilih tidak valid',
            'content.required' => 'Konten tugas wajib diisi',
            'content.min' => 'Konten tugas minimal 10 karakter',
            'due.required' => 'Deadline wajib diisi',
            'due.after' => 'Deadline harus di masa depan',
            'tipe.required' => 'Tipe tugas wajib dipilih',
            // Group task error messages
            'group_names.*.required' => 'Nama kelompok wajib diisi',
            'group_members.*.required' => 'Minimal 2 anggota kelompok diperlukan',
            'group_members.*.min' => 'Minimal 2 anggota kelompok diperlukan',
            'group_leaders.*.required' => 'Ketua kelompok wajib dipilih',
            'existing_group_id.required_if' => 'Pilih kelompok existing atau buat kelompok baru',
            // Peer assessment error messages
            'peer_assessment_due.required_if' => 'Deadline penilaian antar kelompok wajib diisi',
            'peer_assessment_due.after' => 'Deadline penilaian harus setelah deadline tugas',
            'scale_labels.required_if' => 'Skala penilaian wajib diisi',
            'scale_labels.min' => 'Minimal 2 skala penilaian diperlukan',
            'scale_points.required_if' => 'Point penilaian wajib diisi',
            'scale_points.min' => 'Minimal 2 skala penilaian diperlukan'
        ]);

        // Proses kelas_mapel_id dari kelas_id dan mapel_id
        $kelasMapel = KelasMapel::firstOrCreate([
            'kelas_id' => $validated['kelas_id'],
            'mapel_id' => $validated['mapel_id'],
        ]);

        // Update validated data dengan kelas_mapel_id yang sudah diproses
        $validated['kelas_mapel_id'] = $kelasMapel->id;

        // Additional validation for group tasks (only if selecting existing groups)
        if ($request->tipe == 4 && $request->has('selected_groups')) {
            $this->validateGroupTask($request);
        }

        try {
            DB::beginTransaction();
            
            // Handle file upload if any
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = $file->store('tugas', 'public');
            }
            
            // Buat tugas
            $tugas = Tugas::create([
                'kelas_mapel_id' => $validated['kelas_mapel_id'],
                'name' => $validated['name'],
                'content' => $validated['content'],
                'due' => $validated['due'],
                'tipe' => $validated['tipe'],
                'isHidden' => $request->has('isHidden') ? 1 : 0, // Checkbox logic - jika dicentang = hidden, jika tidak = visible
                'file' => $filePath,
                'created_by' => auth()->id()
            ]);

            // Buat progress untuk semua siswa di kelas (jika diperlukan)
            // $this->createProgressForStudents($tugas, $request->kelas_id);

            // Handle berdasarkan tipe tugas
            switch ($request->tipe) {
                case 1: // Pilihan Ganda
                    // Validasi minimal ada 1 soal untuk pilihan ganda
                    if (empty($request->questions) || count($request->questions) == 0) {
                        throw new \Exception('Minimal harus ada 1 soal untuk tugas pilihan ganda');
                    }
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

            // Determine redirect route based on role
            if ($user->roles_id == 1) {
                $route = 'superadmin.task-management';
            } elseif ($user->roles_id == 2) {
                $route = 'admin.task-management';
            } else {
                $route = 'teacher.tasks';
            }

            return redirect()->route($route)
                ->with('success', 'Tugas berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage());
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
                'created_by' => auth()->id()
            ]);

            // Create progress for all students in the class
            $this->createProgressForStudents($tugas, $kelasMapel->kelas_id);

            // Create multiple choice questions - handled by createMultipleChoiceQuestions method

            DB::commit();

            // Clear caches
            CacheService::clearUserTaskCaches($user->id);
            CacheService::clearTaskTypeStatsCache();

            return redirect()->route('teacher.tasks')
                ->with('success', 'Tugas pilihan ganda berhasil dibuat dengan ' . count($request->questions) . ' soal!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log error untuk debugging
            \Log::error('Failed to create task', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'password']),
                'user_id' => auth()->id()
            ]);
            
            // Simpan semua input termasuk array kompleks
            return redirect()->back()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage())
                ->withInput($request->all()); // Pastikan semua data tersimpan
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
                $query->where('roles_id', 4); // 4 = Siswa (Student)
            },
            'TugasProgress.user',
            'TugasFeedback',
            'TugasKelompok.AnggotaTugasKelompok.user',
            'TugasMultiple',
            'TugasQuiz'
        ])->findOrFail($id);

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $tugas->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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
     * Validate group task requirements
     */
    private function validateGroupTask($request)
    {
        $user = Auth::user();
        
        // Verify all selected groups are template groups from the same class
        $selectedGroups = TugasKelompok::whereIn('id', $request->selected_groups)
                                     ->where('is_template', true)
                                     ->where('kelas_id', $request->kelas_id)
                                     ->get();

        if ($selectedGroups->count() != count($request->selected_groups)) {
            throw new \Exception('Semua kelompok yang dipilih harus merupakan template kelompok dari kelas yang sama.');
        }

        // Verify user has access to these groups
        if ($user->roles_id != 1) { // Not super admin
            $accessibleGroups = TugasKelompok::whereIn('id', $request->selected_groups)
                                           ->whereHas('kelas.users', function($query) use ($user) {
                                               $query->where('id', $user->id);
                                           })
                                           ->count();

            if ($accessibleGroups != count($request->selected_groups)) {
                throw new \Exception('Anda tidak memiliki akses ke semua kelompok yang dipilih.');
            }
        }

        // Verify all groups have members
        foreach ($selectedGroups as $group) {
            if ($group->anggotaTugasKelompok->count() < 2) {
                throw new \Exception("Kelompok '{$group->name}' harus memiliki minimal 2 anggota.");
            }

            $hasLeader = $group->anggotaTugasKelompok->where('isKetua', 1)->count() > 0;
            if (!$hasLeader) {
                throw new \Exception("Kelompok '{$group->name}' harus memiliki ketua kelompok.");
            }
        }
    }

    /**
     * Buat progress untuk semua siswa di kelas
     */
    private function createProgressForStudents($tugas, $kelasId)
    {
        $students = User::where('kelas_id', $kelasId)
            ->where('roles_id', 4)  // 4 = Siswa (Student)
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
        // Validasi data questions
        if (empty($questions) || !is_array($questions)) {
            throw new \Exception('Data soal tidak valid');
        }

        $questionNumber = 1; // Counter untuk nomor soal yang benar
        
        foreach ($questions as $index => $questionData) {
            // Validasi setiap field yang required
            if (empty($questionData['question'])) {
                throw new \Exception("Soal nomor " . $questionNumber . " tidak boleh kosong");
            }
            
            // Validasi ukuran data (max 50KB per soal)
            $questionSize = strlen($questionData['question']);
            if ($questionSize > 50000) {
                $sizeKB = round($questionSize / 1024, 2);
                throw new \Exception("Soal nomor " . $questionNumber . " terlalu besar ({$sizeKB}KB). Maksimal 50KB per soal. Silakan kompres gambar atau gunakan resolusi yang lebih rendah.");
            }
            
            // Validasi ukuran opsi
            foreach (['A', 'B', 'C', 'D', 'E'] as $option) {
                if (isset($questionData['options'][$option])) {
                    $optionSize = strlen($questionData['options'][$option]);
                    if ($optionSize > 50000) {
                        $sizeKB = round($optionSize / 1024, 2);
                        throw new \Exception("Opsi jawaban $option pada soal nomor " . $questionNumber . " terlalu besar ({$sizeKB}KB). Maksimal 50KB per opsi. Silakan kompres gambar atau gunakan resolusi yang lebih rendah.");
                    }
                }
            }
            
            if (empty($questionData['correct_answer'])) {
                throw new \Exception("Jawaban benar untuk soal nomor " . $questionNumber . " harus dipilih");
            }
            if (empty($questionData['options']) || !is_array($questionData['options'])) {
                throw new \Exception("Opsi jawaban untuk soal nomor " . $questionNumber . " tidak valid");
            }

            // Log untuk debugging
            \Log::info('Creating TugasMultiple', [
                'tugas_id' => $tugas->id,
                'question_number' => $questionNumber,
                'question_index' => $index,
                'question_data' => $questionData
            ]);

            // Buat TugasMultiple dengan struktur yang benar
            TugasMultiple::create([
                'tugas_id' => $tugas->id,
                'soal' => $questionData['question'],
                'a' => $questionData['options']['A'] ?? '',
                'b' => $questionData['options']['B'] ?? '',
                'c' => $questionData['options']['C'] ?? '',
                'd' => $questionData['options']['D'] ?? '',
                'e' => $questionData['options']['E'] ?? '',
                'jawaban' => $questionData['correct_answer'],
                'poin' => $questionData['points'] ?? 1,
                'kategori' => $questionData['category'] ?? 'medium',
            ]);
            
            $questionNumber++; // Increment counter
        }
    }

    /**
     * Buat tugas esai/mandiri
     */
    private function createEssayTask($tugas, $request)
    {
        \Log::info('createEssayTask called', [
            'tugas_id' => $tugas->id,
            'has_essay_questions' => $request->has('essay_questions'),
            'essay_questions_data' => $request->essay_questions
        ]);
        
        // Simpan konfigurasi tugas esai/mandiri
        $tugas->update([
            'content' => json_encode([
                'allow_file_upload' => $request->allow_file_upload ?? false,
                'allow_text_input' => $request->allow_text_input ?? true,
                'file_types' => $request->file_types ?? ['pdf', 'docx', 'jpg', 'png']
            ])
        ]);
        
        // Simpan soal essay jika ada
        if ($request->has('essay_questions')) {
            $this->createEssayQuestions($tugas, $request->essay_questions);
        }
    }

    /**
     * Simpan soal essay/mandiri ke database
     */
    private function createEssayQuestions($tugas, $essayQuestions)
    {
        \Log::info('createEssayQuestions called', [
            'tugas_id' => $tugas->id,
            'essay_questions' => $essayQuestions
        ]);
        
        if (!$essayQuestions) {
            \Log::warning('No essay questions provided');
            return;
        }

        foreach ($essayQuestions as $question) {
            \Log::info('Creating essay question', $question);
            TugasMandiri::create([
                'tugas_id' => $tugas->id,
                'pertanyaan' => $question['question'],
                'poin' => $question['points'] ?? 10
            ]);
        }
        
        \Log::info('Essay questions created successfully');
    }

    /**
     * Update soal essay yang sudah ada
     */
    private function updateEssayQuestions($tugas, $essayQuestions)
    {
        if (!$essayQuestions) {
            return;
        }

        // Hapus soal lama
        TugasMandiri::where('tugas_id', $tugas->id)->delete();
        
        // Simpan soal baru/yang diupdate
        foreach ($essayQuestions as $question) {
            TugasMandiri::create([
                'tugas_id' => $tugas->id,
                'pertanyaan' => $question['question'],
                'poin' => $question['points'] ?? 10
            ]);
        }
    }

    /**
     * Buat tugas kelompok
     */
    private function createGroupTask($tugas, $request)
    {
        // Prepare rubric data (legacy)
        $rubricItems = [];
        if ($request->has('rubric') && is_array($request->rubric)) {
            foreach ($request->rubric as $rubric) {
                if (isset($rubric['name']) && isset($rubric['weight'])) {
                    $rubricItems[] = [
                        'name' => $rubric['name'],
                        'weight' => $rubric['weight'],
                        'description' => $rubric['description'] ?? null
                    ];
                }
            }
        }

        // Build peer assessment scale configuration
        $assessmentScale = [];
        if ($request->has('enable_peer_assessment') && $request->enable_peer_assessment) {
            $labels = $request->scale_labels ?? [];
            $points = $request->scale_points ?? [];
            
            for ($i = 0; $i < count($labels); $i++) {
                if (isset($labels[$i]) && isset($points[$i])) {
                    $assessmentScale[] = [
                        'label' => $labels[$i],
                        'point' => (int)$points[$i]
                    ];
                }
            }
        }

        // Simpan konfigurasi tugas kelompok
        $tugas->update([
            'content' => json_encode([
                'peer_assessment_due' => $request->peer_assessment_due,
                'rubric_items' => $rubricItems,
                // New peer assessment configuration
                'peer_assessment_enabled' => $request->has('enable_peer_assessment') ? 1 : 0,
                'peer_assessment_due' => $request->peer_assessment_due,
                'assessment_scale' => $assessmentScale,
                'assessment_type' => 'peer', // ketua kelompok menilai kelompok lain
                'assessment_rule' => 'exclude_own_group' // tidak menilai kelompok sendiri
            ])
        ]);

        // Scenario 1: Link tugas dengan kelompok yang sudah ada (existing_group_id)
        if ($request->has('existing_group_id') && $request->existing_group_id) {
            $templateGroupId = $request->existing_group_id;
            
            // Get template group
            $templateGroup = TugasKelompok::where('id', $templateGroupId)
                                        // ->where('is_template', true) // Allow non-template groups too for flexibility? No, validation ensures access
                                        ->with('anggotaTugasKelompok')
                                        ->first();

            if ($templateGroup) {
                // Create task-specific group based on template
                $taskGroup = TugasKelompok::create([
                    'tugas_id' => $tugas->id,
                    'kelas_id' => $templateGroup->kelas_id,
                    'name' => $templateGroup->name,
                    'description' => $templateGroup->description,
                    'status' => 1,
                    'is_template' => false,
                    'created_by' => Auth::id()
                ]);

                // Copy members from template group
                foreach ($templateGroup->anggotaTugasKelompok as $templateMember) {
                    AnggotaTugasKelompok::create([
                        'tugas_kelompok_id' => $taskGroup->id,
                        'user_id' => $templateMember->user_id,
                        'tugas_id' => $tugas->id,
                        'isKetua' => $templateMember->isKetua
                    ]);
                }
            }
        }
        
        // Scenario 2: Buat kelompok baru dari form (group_names, group_members, group_leaders)
        elseif ($request->has('group_names') && is_array($request->group_names)) {
            $groupNames = $request->group_names;
            $groupMembers = $request->group_members ?? [];
            $groupLeaders = $request->group_leaders ?? [];

            foreach ($groupNames as $index => $name) {
                // Skip if name is empty or no members
                if (empty($name) || !isset($groupMembers[$index])) continue;
                
                // Create new group for this task
                $taskGroup = TugasKelompok::create([
                    'tugas_id' => $tugas->id,
                    'kelas_id' => $request->kelas_id,
                    'name' => $name,
                    'description' => null,
                    'status' => 1,
                    'is_template' => false, // Not a template, specific to this task
                    'created_by' => Auth::id()
                ]);

                $members = $groupMembers[$index];
                $leaderId = $groupLeaders[$index] ?? null; // Can be single value from radio/select
                
                // Verify leader is in members list, if not add them? Or trust validation?
                // Validation ensures leader is selected.
                
                foreach ($members as $memberId) {
                    $isLeader = ($memberId == $leaderId);
                    
                    AnggotaTugasKelompok::create([
                        'tugas_kelompok_id' => $taskGroup->id,
                        'user_id' => $memberId,
                        'tugas_id' => $tugas->id,
                        'isKetua' => $isLeader ? 1 : 0
                    ]);
                }
            }
        }
    }

    /**
     * Edit tugas
     */
    public function edit($id)
    {
        $user = Auth::user();
        $tugas = Tugas::with([
            'KelasMapel.Kelas', 
            'KelasMapel.Mapel',
            'TugasQuiz',
            'TugasMultiple',
            'TugasKelompok.AnggotaTugasKelompok.User',
            'TugasMandiri'
        ])->findOrFail($id);

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $tugas->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $tugas->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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

            // Update tugas basic info
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

            // Handle task type specific updates
            switch ($tugas->tipe) {
                case 1: // Multiple Choice
                    $this->updateMultipleChoiceQuestions($tugas, $request);
                    break;
                case 2: // Essay
                case 3: // Mandiri
                    $this->updateEssayTask($tugas, $request);
                    break;
                case 4: // Kelompok
                    $this->updateGroupTask($tugas, $request);
                    break;
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
     * Update multiple choice questions
     */
    private function updateMultipleChoiceQuestions($tugas, $request)
    {
        if (!$request->has('questions')) {
            return;
        }

        // Delete existing questions and options
        $tugas->TugasMultiple()->delete();

        // Create new questions using the same structure as createMultipleChoiceQuestions
        $questionNumber = 1;
        foreach ($request->questions as $questionData) {
            // Validasi data soal
            if (empty($questionData['question'])) {
                throw new \Exception("Pertanyaan untuk soal nomor " . $questionNumber . " tidak boleh kosong");
            }
            
            // Validasi opsi jawaban
            if (empty($questionData['options']) || !is_array($questionData['options'])) {
                throw new \Exception("Opsi jawaban untuk soal nomor " . $questionNumber . " tidak valid");
            }
            
            if (empty($questionData['correct_answer'])) {
                throw new \Exception("Jawaban benar untuk soal nomor " . $questionNumber . " harus dipilih");
            }

            // Buat TugasMultiple dengan struktur yang sama seperti saat create
            TugasMultiple::create([
                'tugas_id' => $tugas->id,
                'soal' => $questionData['question'],
                'a' => $questionData['options']['a'] ?? '',
                'b' => $questionData['options']['b'] ?? '',
                'c' => $questionData['options']['c'] ?? '',
                'd' => $questionData['options']['d'] ?? '',
                'e' => $questionData['options']['e'] ?? '',
                'jawaban' => $questionData['correct_answer'],
                'poin' => $questionData['points'] ?? 1,
                'kategori' => $questionData['category'] ?? 'medium',
            ]);
            
            $questionNumber++;
        }
    }

    /**
     * Update essay/individual task
     */
    private function updateEssayTask($tugas, $request)
    {
        $config = [
            'allow_file_upload' => $request->has('allow_file_upload'),
            'allow_text_input' => $request->has('allow_text_input'),
            'file_types' => $request->file_types ?? ['pdf', 'docx', 'jpg', 'png']
        ];

        $tugas->update([
            'content' => json_encode($config)
        ]);
        
        // Update soal essay jika ada
        if ($request->has('essay_questions')) {
            $this->updateEssayQuestions($tugas, $request->essay_questions);
        }
    }

    /**
     * Update group task
     */
    private function updateGroupTask($tugas, $request)
    {
        $config = [
            'min_members' => $request->min_members ?? 2,
            'max_members' => $request->max_members ?? 5,
            'allow_peer_evaluation' => $request->has('allow_peer_evaluation'),
            'evaluation_criteria' => $request->evaluation_criteria ?? []
        ];

        $tugas->update([
            'content' => json_encode($config)
        ]);
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $user = Auth::user();

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $tugas->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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

            return redirect()->route('teacher.tasks')
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
     * Calculate auto score for multiple choice tasks
     */
    public function getAutoScore($tugasId, $studentId)
    {
        $tugas = Tugas::findOrFail($tugasId);
        $student = User::findOrFail($studentId);

        if ($tugas->tipe != 1) {
            return response()->json(['error' => 'Not a multiple choice task'], 400);
        }

        // Get student answers
        $answers = $tugas->TugasJawabanMultiple()->where('user_id', $studentId)->get();
        
        if ($answers->count() == 0) {
            return response()->json(['score' => 0]);
        }

        // Calculate score
        $totalQuestions = $answers->count();
        $correctAnswers = $answers->where('nilai', '>', 0)->count();
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        return response()->json(['score' => $score]);
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
                $query->where('roles_id', 4); // 4 = Siswa (Student)
            },
            'KelasMapel.Mapel',
            'TugasProgress' => function($query) {
                $query->with('user');
            },
            'TugasFeedback' => function($query) {
                $query->with('user');
            }
        ])->findOrFail($taskId);

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $task->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Get all students in the class
        $students = $task->KelasMapel->Kelas->users->where('roles_id', 4);  // 4 = Siswa (Student)
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
                $query->where('roles_id', 4);  // 4 = Siswa (Student)
            }
        ])->findOrFail($taskId);

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $task->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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

        // Verifikasi akses
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            // Superadmin dan Admin: akses penuh
            $hasAccess = true;
        } elseif ($user->roles_id == 3) {
            // Teacher: hanya tugas yang mereka buat
            $hasAccess = $task->created_by == $user->id;
        } else {
            $hasAccess = false;
        }

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
        
        // Get KelasMapel where teacher is assigned via EditorAccess
        $kelasMapels = KelasMapel::whereHas('editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        return [
            'kelas_mapel_ids' => $kelasMapels->pluck('id')->toArray(),
            'kelas_ids' => $kelasMapels->pluck('kelas_id')->unique()->toArray(),
            'mapel_ids' => $kelasMapels->pluck('mapel_id')->unique()->toArray(),
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
        
        return Mapel::whereHas('kelasMapel.kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
    }
    
    /**
     * Get students by class for AJAX request
     */
    public function getStudentsByClass($kelasId)
    {
        try {
            $students = \App\Models\User::where('kelas_id', $kelasId)
                ->where('roles_id', 4) // Student role
                ->orderBy('name')
                ->get()
                ->map(function($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'nis' => $student->nis_nip ?? 'N/A',
                        'user_id' => $student->id,
                        'email' => $student->email
                    ];
                });
                
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load students: ' . $e->getMessage()], 500);
        }
    }
}
