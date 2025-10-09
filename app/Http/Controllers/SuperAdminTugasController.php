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
        
        // Tentukan view berdasarkan role
        $viewName = $userRole == 1 ? 'superadmin.task-management' : 'admin.task-management';
        
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
        $subjects = Mapel::all();
        $kelas = Kelas::all();
        
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
        
        return view('superadmin.create-tugas', [
            'title' => 'Buat Tugas ' . $tipeTugas[$tipe],
            'user' => $user,
            'tipe' => $tipe,
            'tipeTugas' => $tipeTugas[$tipe],
            'subjects' => $subjects,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Menyimpan tugas baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userRole = $user->roles_id;
        
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
            
            DB::commit();
            
            $redirectRoute = $userRole == 1 ? 'superadmin.task-management' : 'admin.tugas.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Tugas berhasil dibuat!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage())
                ->withInput();
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
        $tugas = Tugas::with(['KelasMapel.Mapel', 'KelasMapel.Kelas'])->findOrFail($id);
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
}