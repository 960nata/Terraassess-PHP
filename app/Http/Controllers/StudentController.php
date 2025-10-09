<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\UserTugas;
use App\Models\UserUjian;
use App\Models\IotReading;
use App\Models\KelasMapel;
use App\Models\Nilai;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Dashboard utama siswa dengan statistik lengkap
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Data kelas dan mata pelajaran
        $kelas = $user->kelas;
        $mapelKelas = KelasMapel::where('kelas_id', $user->kelas_id)
            ->with(['mapel'])
            ->get();
        
        // Statistik umum - menggunakan tabel yang ada
        $totalMateri = \DB::table('materis')
            ->join('kelas_mapels', 'materis.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        $totalTugas = \DB::table('tugas')
            ->join('kelas_mapels', 'tugas.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        $totalUjian = \DB::table('ujians')
            ->join('kelas_mapels', 'ujians.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        $tugasSelesai = \DB::table('user_tugas')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'graded'])
            ->count();
        
        $ujianSelesai = \DB::table('user_ujians')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'graded'])
            ->count();
        
        // Data IoT - menggunakan tabel iot_readings yang ada
        $totalIotData = \DB::table('iot_readings')
            ->where('student_id', $user->id)
            ->count();
        
        $todayIotData = \DB::table('iot_readings')
            ->where('student_id', $user->id)
            ->whereDate('timestamp', today())
            ->count();
        
        // Analisis nilai per mata pelajaran
        $analisisNilai = $this->getAnalisisNilaiPerMapel($user);
        
        // Tugas terbaru
        $tugasTerbaru = \DB::table('tugas')
            ->join('kelas_mapels', 'tugas.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->join('mapels', 'kelas_mapels.mapel_id', '=', 'mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->select('tugas.*', 'mapels.name as mapel_name')
            ->orderBy('tugas.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ujian terbaru
        $ujianTerbaru = \DB::table('ujians')
            ->join('kelas_mapels', 'ujians.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->join('mapels', 'kelas_mapels.mapel_id', '=', 'mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->select('ujians.*', 'mapels.name as mapel_name')
            ->orderBy('ujians.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Materi terbaru
        $materiTerbaru = \DB::table('materis')
            ->join('kelas_mapels', 'materis.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->join('mapels', 'kelas_mapels.mapel_id', '=', 'mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->select('materis.*', 'mapels.name as mapel_name')
            ->orderBy('materis.created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('student.dashboard', compact(
            'user', 'kelas', 'mapelKelas', 'totalMateri', 'totalTugas', 
            'totalUjian', 'tugasSelesai', 'ujianSelesai', 'totalIotData', 
            'todayIotData', 'analisisNilai', 'tugasTerbaru', 'ujianTerbaru', 
            'materiTerbaru'
        ))->with('title', 'Student Dashboard');
    }
    
    /**
     * Halaman tugas siswa
     */
    public function tugas()
    {
        $user = Auth::user();
        
        $tugas = Tugas::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'userTugas' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('student.tugas', compact('tugas', 'user'))->with('title', 'Student Tugas');
    }
    
    /**
     * Detail tugas dan kerjakan
     */
    public function kerjakanTugas($id)
    {
        $user = Auth::user();
        
        $tugas = Tugas::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'kelasMapel.pengajar'])
        ->findOrFail($id);
        
        $userTugas = UserTugas::where('tugas_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        return view('student.kerjakan-tugas', compact('tugas', 'userTugas', 'user'))->with('title', 'Kerjakan Tugas');
    }
    
    /**
     * Submit jawaban tugas
     */
    public function submitTugas(Request $request, $id)
    {
        $request->validate([
            'jawaban' => 'required|string',
            'file_jawaban' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'
        ]);
        
        $user = Auth::user();
        $tugas = Tugas::findOrFail($id);
        
        $data = [
            'user_id' => $user->id,
            'tugas_id' => $id,
            'jawaban' => $request->jawaban,
            'status' => 'submitted',
            'submitted_at' => now()
        ];
        
        if ($request->hasFile('file_jawaban')) {
            $file = $request->file('file_jawaban');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tugas/jawaban', $filename, 'public');
            $data['file_jawaban'] = $path;
        }
        
        UserTugas::updateOrCreate(
            ['user_id' => $user->id, 'tugas_id' => $id],
            $data
        );
        
        return redirect()->route('student.tugas')->with('success', 'Tugas berhasil dikumpulkan!');
    }
    
    /**
     * Halaman ujian siswa
     */
    public function ujian()
    {
        $user = Auth::user();
        
        $ujian = Ujian::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'soalMultiples', 'soalEssays', 'userUjian' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('student.ujian', compact('ujian', 'user'))->with('title', 'Student Ujian');
    }
    
    /**
     * Kerjakan ujian
     */
    public function kerjakanUjian($id)
    {
        $user = Auth::user();
        
        $ujian = Ujian::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'kelasMapel.pengajar', 'soalMultiples', 'soalEssays'])
        ->findOrFail($id);
        
        $userUjian = UserUjian::where('ujian_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        return view('student.kerjakan-ujian', compact('ujian', 'userUjian', 'user'))->with('title', 'Kerjakan Ujian');
    }
    
    /**
     * Submit jawaban ujian
     */
    public function submitUjian(Request $request, $id)
    {
        $user = Auth::user();
        $ujian = Ujian::findOrFail($id);
        
        $jawaban = $request->jawaban;
        $skor = 0;
        $totalSoal = $ujian->soalMultiples->count() + $ujian->soalEssays->count();
        
        // Hitung skor untuk soal multiple choice
        foreach ($ujian->soalMultiples as $soal) {
            if (isset($jawaban[$soal->id])) {
                if ($soal->jawaban == $jawaban[$soal->id]) {
                    $skor++;
                }
            }
        }
        
        // Untuk soal essay, skor bisa dihitung berdasarkan penilaian manual
        // atau bisa diabaikan untuk sementara
        
        $nilai = ($skor / $totalSoal) * 100;
        
        UserUjian::updateOrCreate(
            ['user_id' => $user->id, 'ujian_id' => $id],
            [
                'jawaban' => json_encode($jawaban),
                'skor' => $skor,
                'nilai' => $nilai,
                'status' => 'completed',
                'completed_at' => now()
            ]
        );
        
        return redirect()->route('student.ujian')->with('success', 'Ujian berhasil diselesaikan!');
    }
    
    /**
     * Halaman materi
     */
    public function materi()
    {
        $user = Auth::user();
        
        $materi = Materi::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'kelasMapel.pengajar'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('student.materi', compact('materi', 'user'))->with('title', 'Student Materi');
    }
    
    /**
     * Detail materi
     */
    public function materiDetail($id)
    {
        $user = Auth::user();
        
        $materi = Materi::whereHas('kelasMapel', function($query) use ($user) {
            $query->where('kelas_id', $user->kelas_id);
        })
        ->with(['kelasMapel.mapel', 'kelasMapel.pengajar'])
        ->findOrFail($id);
        
        return view('student.materi-detail', compact('materi', 'user'))->with('title', 'Detail Materi');
    }
    
    /**
     * Halaman penelitian IoT
     */
    public function iot()
    {
        $user = Auth::user();
        
        $myReadings = IotReading::where('student_id', $user->id)
            ->with(['kelas'])
            ->orderBy('timestamp', 'desc')
            ->paginate(10);
        
        $classReadings = IotReading::where('class_id', $user->kelas_id)
            ->with(['student', 'kelas'])
            ->orderBy('timestamp', 'desc')
            ->limit(20)
            ->get();
        
        // Calculate statistics for myReadings
        $myReadingsStats = [
            'total' => $myReadings->total(),
            'today_count' => IotReading::where('student_id', $user->id)
                ->whereDate('timestamp', today())
                ->count(),
            'avg_temperature' => IotReading::where('student_id', $user->id)
                ->avg('soil_temperature'),
            'avg_moisture' => IotReading::where('student_id', $user->id)
                ->avg('soil_moisture')
        ];
        
        return view('student.iot', compact('myReadings', 'classReadings', 'myReadingsStats', 'user'))->with('title', 'Student IoT');
    }
    
    /**
     * Halaman profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('student.profile', compact('user'))->with('title', 'Student Profile');
    }
    
    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'about' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $data = $request->only(['name', 'email', 'phone', 'address', 'about']);
        
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('user-images', $filename, 'public');
            $data['gambar'] = $path;
        }
        
        $user->update($data);
        
        return redirect()->route('student.profile')->with('success', 'Profile berhasil diperbarui!');
    }
    
    /**
     * Update photo only
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('user-images', $filename, 'public');
            
            $user->update(['gambar' => $path]);
            
            return redirect()->route('student.profile')->with('success', 'Foto profile berhasil diperbarui!');
        }
        
        return redirect()->route('student.profile')->with('error', 'Gagal mengupload foto. Silakan coba lagi.');
    }
    
    /**
     * Upload image for rich text editor
     */
    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,bmp,ico|max:5120' // 5MB max
        ]);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'editor_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('editor-images', $filename, 'public');
            
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan'
        ], 400);
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('student.profile')->with('success', 'Password berhasil diperbarui!');
    }
    
    /**
     * Get analisis nilai per mata pelajaran
     */
    private function getAnalisisNilaiPerMapel($user)
    {
        $mapelKelas = \DB::table('kelas_mapels')
            ->join('mapels', 'kelas_mapels.mapel_id', '=', 'mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->select('kelas_mapels.id as kelas_mapel_id', 'mapels.name as mapel_name', 'mapels.id as mapel_id')
            ->get();
        
        $analisis = [];
        
        foreach ($mapelKelas as $mk) {
            // Hitung nilai tugas
            $tugasNilai = \DB::table('user_tugas')
                ->join('tugas', 'user_tugas.tugas_id', '=', 'tugas.id')
                ->where('user_tugas.user_id', $user->id)
                ->where('tugas.kelas_mapel_id', $mk->kelas_mapel_id)
                ->whereIn('user_tugas.status', ['completed', 'graded'])
                ->avg('user_tugas.nilai');
            
            // Hitung nilai ujian
            $ujianNilai = \DB::table('user_ujians')
                ->join('ujians', 'user_ujians.ujian_id', '=', 'ujians.id')
                ->where('user_ujians.user_id', $user->id)
                ->where('ujians.kelas_mapel_id', $mk->kelas_mapel_id)
                ->whereIn('user_ujians.status', ['completed', 'graded'])
                ->avg('user_ujians.nilai');
            
            // Hitung rata-rata
            $rataRata = 0;
            $count = 0;
            
            if ($tugasNilai) {
                $rataRata += $tugasNilai;
                $count++;
            }
            
            if ($ujianNilai) {
                $rataRata += $ujianNilai;
                $count++;
            }
            
            if ($count > 0) {
                $rataRata = $rataRata / $count;
            }
            
            $analisis[] = [
                'mapel' => (object)['name' => $mk->mapel_name, 'id' => $mk->mapel_id],
                'tugas_nilai' => $tugasNilai ? round($tugasNilai, 2) : 0,
                'ujian_nilai' => $ujianNilai ? round($ujianNilai, 2) : 0,
                'rata_rata' => round($rataRata, 2),
                'grade' => $this->getGrade($rataRata),
                'color' => $this->getGradeColor($rataRata)
            ];
        }
        
        return collect($analisis);
    }
    
    /**
     * Get grade berdasarkan nilai
     */
    private function getGrade($nilai)
    {
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }
    
    /**
     * Get color berdasarkan nilai
     */
    private function getGradeColor($nilai)
    {
        if ($nilai >= 90) return 'success';
        if ($nilai >= 80) return 'primary';
        if ($nilai >= 70) return 'warning';
        if ($nilai >= 60) return 'info';
        return 'danger';
    }
}
