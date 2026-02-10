<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\TugasProgress;
use App\Models\TugasFeedback;
use App\Models\TugasKelompok;
use App\Models\KelompokPenilaian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminTugasController extends Controller
{
    /**
     * Menampilkan halaman manajemen tugas super admin dan admin
     */
    public function index()
    {
        $user = auth()->user();
        $userRole = $user->roles_id;
        
        // Statistik tugas
        $totalTugas = Tugas::count();
        $activeTasks = Tugas::where('isHidden', 0)->count();
        $completedTasks = Tugas::where('due', '<', now())->count();
        $activeClasses = Kelas::whereHas('KelasMapel.Tugas')->count();
        
        $tugasPilihanGanda = Tugas::where('tipe', 1)->count();
        $tugasEssay = Tugas::where('tipe', 2)->count();
        $tugasMandiri = Tugas::where('tipe', 3)->count();
        $tugasKelompok = Tugas::where('tipe', 4)->count();
        
        // Tugas terbaru
        $tugasTerbaru = Tugas::with(['KelasMapel.Mapel', 'KelasMapel.Kelas'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Progress siswa
        $progressSiswa = TugasProgress::with(['user', 'tugas'])
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();
        
        // Data untuk form (jika masih menggunakan view lama)
        $subjects = Mapel::all();
        $classes = Kelas::all();
        
        // Ambil semua tugas untuk tabel
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Gunakan satu view bersama untuk semua role
        $viewName = 'admin.task-management';
        
        return view($viewName, [
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
     * Menampilkan halaman create tugas berdasarkan tipe
     */
    public function create($tipe)
    {
        $user = auth()->user();
        $userRole = $user->roles_id;
        
        // Get all necessary data
        $kelas = Kelas::orderBy('name')->get();
        $subjects = Mapel::orderBy('name')->get();
        
        // Get KelasMapel based on role
        if ($userRole == 1 || $userRole == 2) {
            // Superadmin & Admin: all KelasMapel
            $kelasMapels = KelasMapel::with(['kelas', 'mapel', 'pengajar'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Teacher: only their KelasMapel
            $kelasMapels = KelasMapel::with(['kelas', 'mapel', 'pengajar'])
                ->where('pengajar_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Check if kelasMapels is empty
        if ($kelasMapels->isEmpty()) {
            $redirectRoute = $userRole == 1 ? 'superadmin.task-management' : 'admin.tugas.index';
            return redirect()->route($redirectRoute)
                ->with('error', 'Tidak ada kelas mapel tersedia. Silakan hubungi admin untuk assign kelas mapel terlebih dahulu.');
        }
        
        $tipeTugas = [
            1 => 'Pilihan Ganda',
            2 => 'Essay', 
            3 => 'Mandiri',
            4 => 'Kelompok'
        ];
        
        if (!isset($tipeTugas[$tipe])) {
            $redirectRoute = $userRole == 1 ? 'superadmin.task-management' : 'admin.tugas.index';
            return redirect()->route($redirectRoute)
                ->with('error', 'Tipe tugas tidak valid');
        }
        
        // Determine which shared view to use based on task type
        if ($tipe == 3) {
            return view('shared.task-create-individual', [
                'title' => 'Buat Tugas ' . $tipeTugas[$tipe],
                'user' => $user,
                'userRole' => $userRole == 1 ? 'superadmin' : ($userRole == 2 ? 'admin' : 'teacher'),
                'tipe' => $tipe,
                'tipeTugas' => $tipeTugas[$tipe],
                'subjects' => $subjects,
                'kelas' => $kelas,
                'mapel' => $subjects, // Use subjects as mapel for compatibility
                'kelasMapels' => $kelasMapels,
            ]);
        } else {
            // Get existing groups for dropdown
            $groups = TugasKelompok::with('AnggotaTugasKelompok')->get();
            
            return view('shared.task-create-group', [
                'title' => 'Buat Tugas ' . $tipeTugas[$tipe],
                'user' => $user,
                'userRole' => $userRole == 1 ? 'superadmin' : ($userRole == 2 ? 'admin' : 'teacher'),
                'tipe' => $tipe,
                'tipeTugas' => $tipeTugas[$tipe],
                'subjects' => $subjects,
                'kelas' => $kelas,
                'mapel' => $subjects, // Use subjects as mapel for compatibility
                'kelasMapels' => $kelasMapels,
                'groups' => $groups
            ]);
        }
    }

    /**
     * Menyimpan tugas baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userRole = $user->roles_id;

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
                'isHidden' => $request->has('isHidden') ? 0 : 1, // Checkbox logic
                'file' => $filePath,
                'created_by' => auth()->id()
            ]);
            
            // Handle group task creation if tipe = 4
            if ($validated['tipe'] == 4) {
                $this->createGroupTask($tugas, $request);
            }
            
            // Buat progress untuk semua siswa di kelas (jika diperlukan)
            // $this->createProgressForStudents($tugas, $request->kelas_id);
            
            DB::commit();
            
            // Determine redirect route based on role
            if ($userRole == 1) {
                $route = 'superadmin.task-management';
            } elseif ($userRole == 2) {
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
     * Menampilkan detail tugas dan progress siswa
     */
    public function show($id)
    {
        $user = auth()->user();
        $tugas = Tugas::with(['KelasMapel.Mapel', 'KelasMapel.Kelas', 'TugasProgress.user'])
            ->findOrFail($id);
        
        // Progress siswa
        $progressSiswa = TugasProgress::with('user')
            ->where('tugas_id', $id)
            ->get();
        
        // Feedback
        $feedbacks = TugasFeedback::with(['user', 'guru'])
            ->where('tugas_id', $id)
            ->get();
        
        return view('superadmin.detail-tugas', [
            'title' => 'Detail Tugas: ' . $tugas->name,
            'user' => $user,
            'tugas' => $tugas,
            'progressSiswa' => $progressSiswa,
            'feedbacks' => $feedbacks,
        ]);
    }

    /**
     * Menyimpan feedback untuk siswa
     */
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'user_id' => 'required|exists:users,id',
            'feedback' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        try {
            TugasFeedback::create([
                'tugas_id' => $request->tugas_id,
                'user_id' => $request->user_id,
                'guru_id' => auth()->id(),
                'feedback' => $request->feedback,
                'rating' => $request->rating,
                'status' => 'approved',
            ]);

            return redirect()->back()
                ->with('success', 'Feedback berhasil disimpan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan feedback: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman penilaian kelompok
     */
    public function penilaianKelompok($tugasId)
    {
        $user = auth()->user();
        $tugas = Tugas::with(['KelasMapel.Mapel', 'KelasMapel.Kelas'])
            ->findOrFail($tugasId);
        
        $kelompok = TugasKelompok::with(['AnggotaTugasKelompok.user'])
            ->where('tugas_id', $tugasId)
            ->get();
        
        return view('superadmin.penilaian-kelompok', [
            'title' => 'Penilaian Kelompok - ' . $tugas->name,
            'user' => $user,
            'tugas' => $tugas,
            'kelompok' => $kelompok,
        ]);
    }

    /**
     * Menyimpan penilaian kelompok
     */
    public function storePenilaianKelompok(Request $request)
    {
        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'tugas_kelompok_id' => 'required|exists:tugas_kelompoks,id',
            'penilai_kelompok_id' => 'required|exists:tugas_kelompoks,id',
            'nilai_kerjasama' => 'required|integer|min:1|max:5',
            'nilai_kualitas' => 'required|integer|min:1|max:5',
            'nilai_presentasi' => 'required|integer|min:1|max:5',
            'nilai_inovasi' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        try {
            KelompokPenilaian::updateOrCreate([
                'tugas_kelompok_id' => $request->tugas_kelompok_id,
                'penilai_kelompok_id' => $request->penilai_kelompok_id,
            ], [
                'tugas_id' => $request->tugas_id,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_kualitas' => $request->nilai_kualitas,
                'nilai_presentasi' => $request->nilai_presentasi,
                'nilai_inovasi' => $request->nilai_inovasi,
                'komentar' => $request->komentar,
                'status' => 'completed',
            ]);

            return redirect()->back()
                ->with('success', 'Penilaian kelompok berhasil disimpan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Membuat progress untuk semua siswa di kelas
     */
    private function createProgressForStudents($tugas, $kelasId)
    {
        $siswa = User::where('kelas_id', $kelasId)
            ->where('roles_id', 4) // Role siswa
            ->get();
        
        foreach ($siswa as $student) {
            TugasProgress::create([
                'tugas_id' => $tugas->id,
                'user_id' => $student->id,
                'status' => 'not_started',
                'progress_percentage' => 0,
            ]);
        }
    }

    /**
     * Menampilkan form edit tugas
     */
    public function edit($id)
    {
        $user = auth()->user();
        $tugas = Tugas::with([
            'KelasMapel.Mapel', 
            'KelasMapel.Kelas',
            'TugasQuiz',
            'TugasMultiple',
            'TugasKelompok.AnggotaTugasKelompok.User',
            'TugasMandiri'
        ])->findOrFail($id);
        $subjects = Mapel::all();
        $kelas = Kelas::all();
        
        $tipeTugas = [
            1 => 'Pilihan Ganda',
            2 => 'Essay', 
            3 => 'Mandiri',
            4 => 'Kelompok'
        ];
        
        return view('superadmin.edit-tugas', [
            'title' => 'Edit Tugas: ' . $tugas->name,
            'user' => $user,
            'tugas' => $tugas,
            'tipe' => $tugas->tipe,
            'tipeTugas' => $tipeTugas[$tugas->tipe],
            'subjects' => $subjects,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Update tugas yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $userRole = $user->roles_id;
        $tugas = Tugas::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'tipe' => 'required|integer|in:1,2,3,4',
            'due' => 'nullable|date|after:now',
            'mapel_id' => 'required|exists:mapels,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Cari atau buat KelasMapel
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
            ]);
            
            // Update tugas
            $tugas->update([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->name,
                'content' => $request->content,
                'due' => $request->due,
                'tipe' => $request->tipe,
            ]);
            
            DB::commit();
            
            $redirectRoute = $userRole == 1 ? 'superadmin.tugas.show' : 'admin.tugas.show';
            return redirect()->route($redirectRoute, $tugas->id)
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
        $user = auth()->user();
        $userRole = $user->roles_id;
        
        try {
            DB::beginTransaction();
            
            $tugas = Tugas::findOrFail($id);
            
            // Hapus progress siswa terkait
            TugasProgress::where('tugas_id', $id)->delete();
            
            // Hapus feedback terkait
            TugasFeedback::where('tugas_id', $id)->delete();
            
            // Hapus tugas
            $tugas->delete();
            
            DB::commit();
            
            $redirectRoute = $userRole == 1 ? 'superadmin.tugas.index' : 'admin.tugas.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Tugas berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus tugas: ' . $e->getMessage());
        }
    }

    /**
     * Buat tugas kelompok
     */
    private function createGroupTask($tugas, $request)
    {
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

        // Update tugas content dengan peer assessment config
        $tugas->update([
            'content' => json_encode([
                'peer_assessment_enabled' => $request->has('enable_peer_assessment') ? 1 : 0,
                'peer_assessment_due' => $request->peer_assessment_due,
                'assessment_scale' => $assessmentScale,
                'assessment_type' => 'peer', // ketua kelompok menilai kelompok lain
                'assessment_rule' => 'exclude_own_group' // tidak menilai kelompok sendiri
            ])
        ]);

        // Handle multiple groups (is_new_group = 1)
        if ($request->has('is_new_group') && $request->is_new_group) {
            // Handle multiple groups
            if ($request->has('group_names') && is_array($request->group_names)) {
                foreach ($request->group_names as $index => $groupName) {
                    $taskGroup = TugasKelompok::create([
                        'tugas_id' => $tugas->id,
                        'kelas_id' => $request->kelas_id,
                        'name' => $groupName,
                        'description' => null,
                        'status' => 'active',
                        'is_template' => false,
                        'created_by' => auth()->id()
                    ]);
                    
                    // Add members
                    if (isset($request->group_members[$index])) {
                        foreach ($request->group_members[$index] as $memberId) {
                            $isKetua = ($memberId == $request->group_leaders[$index]) ? 1 : 0;
                            
                            \App\Models\AnggotaTugasKelompok::create([
                                'tugas_kelompok_id' => $taskGroup->id,
                                'user_id' => $memberId,
                                'tugas_id' => $tugas->id,
                                'isKetua' => $isKetua
                            ]);
                        }
                    }
                }
            }
        }
        
        // Use existing group
        elseif ($request->has('existing_group_id') && $request->existing_group_id) {
            $existingGroup = TugasKelompok::with('AnggotaTugasKelompok')->find($request->existing_group_id);
            
            if ($existingGroup) {
                // Create task-specific group based on existing group
                $taskGroup = TugasKelompok::create([
                    'tugas_id' => $tugas->id,
                    'kelas_id' => $request->kelas_id,
                    'name' => $existingGroup->name,
                    'description' => $existingGroup->description,
                    'status' => 'active',
                    'is_template' => false,
                    'created_by' => auth()->id()
                ]);

                // Copy members from existing group
                foreach ($existingGroup->AnggotaTugasKelompok as $member) {
                    \App\Models\AnggotaTugasKelompok::create([
                        'tugas_kelompok_id' => $taskGroup->id,
                        'user_id' => $member->user_id,
                        'tugas_id' => $tugas->id,
                        'isKetua' => $member->isKetua
                    ]);
                }
            }
        }
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