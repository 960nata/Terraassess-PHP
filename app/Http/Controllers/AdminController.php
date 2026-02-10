<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Ujian;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->roles_id != 1 && Auth::user()->roles_id != 2) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $data = [
            'totalSiswa' => DataSiswa::count(),
            'totalUserSiswa' => User::where('roles_id', 4)->count(),
            'totalPengajar' => User::where('roles_id', 3)->count(),
            'totalKelas' => Kelas::count(),
            'totalMapel' => Mapel::count(),
            'totalMateri' => Materi::count(),
            'totalTugas' => Tugas::count(),
            'totalUjian' => Ujian::count(),
        ];

        // Get chart data
        $chartData = $this->getChartData();

        return view('dashboard.admin-new', [
            'title' => 'Admin Dashboard',
            'user' => $user,
            'data' => $data,
            'chartData' => $chartData
        ]);
    }

    /**
     * Display pengajar management page
     */
    public function pengajarManagement()
    {
        $pengajar = User::where('roles_id', 3)
            ->with(['mapel', 'kelas'])
            ->get();
        
        $mapel = Mapel::all();

        return view('admin.pengajar-management', [
            'title' => 'Manajemen Pengajar',
            'pengajar' => $pengajar,
            'mapel' => $mapel
        ]);
    }

    /**
     * Store new pengajar
     */
    public function storePengajar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'mapel_id' => 'nullable|exists:mapel,id',
            'status' => 'required|in:active,inactive'
        ]);

        $pengajar = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => 3, // Pengajar role
            'status' => $request->status,
            'mapel_id' => $request->mapel_id
        ]);

        return redirect()->route('admin.pengajar')->with('success', 'Pengajar berhasil ditambahkan');
    }

    /**
     * Update pengajar
     */
    public function updatePengajar(Request $request, $id)
    {
        $pengajar = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'mapel_id' => 'nullable|exists:mapel,id',
            'status' => 'required|in:active,inactive'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'mapel_id' => $request->mapel_id
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $pengajar->update($updateData);

        return redirect()->route('admin.pengajar')->with('success', 'Pengajar berhasil diperbarui');
    }

    /**
     * Delete pengajar
     */
    public function deletePengajar($id)
    {
        $pengajar = User::findOrFail($id);
        $pengajar->delete();

        return redirect()->route('admin.pengajar')->with('success', 'Pengajar berhasil dihapus');
    }

    /**
     * Display siswa management page
     */
    public function siswaManagement()
    {
        $siswa = DataSiswa::with(['kelas', 'user'])->get();
        $kelas = Kelas::all();

        return view('admin.siswa-management', [
            'title' => 'Manajemen Siswa',
            'siswa' => $siswa,
            'kelas' => $kelas,
            'totalSiswa' => $siswa->count(),
            'kelasPenuh' => $this->getKelasPenuh()
        ]);
    }

    /**
     * Store new siswa
     */
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nis' => 'required|string|unique:data_siswa,nis',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:active,inactive'
        ]);

        DB::transaction(function () use ($request) {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles_id' => 4, // Siswa role
                'status' => $request->status
            ]);

            // Create siswa data
            DataSiswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'status' => $request->status
            ]);
        });

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Update siswa
     */
    public function updateSiswa(Request $request, $id)
    {
        $siswa = DataSiswa::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $siswa->user_id,
            'password' => 'nullable|string|min:8',
            'nis' => 'required|string|unique:data_siswa,nis,' . $id,
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:active,inactive'
        ]);

        DB::transaction(function () use ($request, $siswa) {
            // Update user account
            $updateUserData = [
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status
            ];

            if ($request->password) {
                $updateUserData['password'] = Hash::make($request->password);
            }

            $siswa->user->update($updateUserData);

            // Update siswa data
            $siswa->update([
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
                'status' => $request->status
            ]);
        });

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil diperbarui');
    }

    /**
     * Delete siswa
     */
    public function deleteSiswa($id)
    {
        $siswa = DataSiswa::findOrFail($id);
        
        DB::transaction(function () use ($siswa) {
            $siswa->user->delete();
            $siswa->delete();
        });

        return redirect()->route('admin.siswa')->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Display kelas management page
     */
    public function kelasManagement()
    {
        try {
            $kelas = Kelas::with(['siswa', 'mapel'])->get();
            $totalSiswa = DataSiswa::count();
            $kelasPenuh = $this->getKelasPenuh();

            return view('admin.kelas-management', [
                'title' => 'Manajemen Kelas',
                'kelas' => $kelas,
                'totalSiswa' => $totalSiswa,
                'kelasPenuh' => $kelasPenuh
            ]);
        } catch (\Exception $e) {
            // Fallback in case of any errors
            return view('admin.kelas-management', [
                'title' => 'Manajemen Kelas',
                'kelas' => collect(), // Empty collection
                'totalSiswa' => 0,
                'kelasPenuh' => 0
            ]);
        }
    }

    /**
     * Store new kelas
     */
    public function storeKelas(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:kelas,name',
            'description' => 'nullable|string',
            'tingkat' => 'required|in:X,XI,XII',
            'max_capacity' => 'nullable|integer|min:1|max:50'
        ]);

        Kelas::create([
            'name' => $request->name,
            'description' => $request->description,
            'tingkat' => $request->tingkat,
            'max_capacity' => $request->max_capacity ?? 30
        ]);

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Update kelas
     */
    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:kelas,name,' . $id,
            'description' => 'nullable|string',
            'tingkat' => 'required|in:X,XI,XII',
            'max_capacity' => 'nullable|integer|min:1|max:50'
        ]);

        $kelas->update([
            'name' => $request->name,
            'description' => $request->description,
            'tingkat' => $request->tingkat,
            'max_capacity' => $request->max_capacity ?? 30
        ]);

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Delete kelas
     */
    public function deleteKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Check if kelas has students
        if ($kelas->siswa->count() > 0) {
            return redirect()->route('admin.kelas')->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil dihapus');
    }

    /**
     * Display mapel management page
     */
    public function mapelManagement()
    {
        $mapel = Mapel::with(['pengajar', 'kelas'])->get();
        $totalPengajar = User::where('roles_id', 3)->count();
        $totalKelas = Kelas::count();

        return view('admin.mapel-management', [
            'title' => 'Manajemen Mata Pelajaran',
            'mapel' => $mapel,
            'totalPengajar' => $totalPengajar,
            'totalKelas' => $totalKelas
        ]);
    }

    /**
     * Store new mapel
     */
    public function storeMapel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mapel,name',
            'kode' => 'nullable|string|max:20|unique:mapel,kode',
            'description' => 'nullable|string',
            'category' => 'required|in:science,social,language,math,other',
            'status' => 'required|in:active,inactive'
        ]);

        Mapel::create([
            'name' => $request->name,
            'kode' => $request->kode,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status
        ]);

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    /**
     * Update mapel
     */
    public function updateMapel(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:mapel,name,' . $id,
            'kode' => 'nullable|string|max:20|unique:mapel,kode,' . $id,
            'description' => 'nullable|string',
            'category' => 'required|in:science,social,language,math,other',
            'status' => 'required|in:active,inactive'
        ]);

        $mapel->update([
            'name' => $request->name,
            'kode' => $request->kode,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status
        ]);

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil diperbarui');
    }

    /**
     * Delete mapel
     */
    public function deleteMapel($id)
    {
        $mapel = Mapel::findOrFail($id);
        
        // Check if mapel has teachers or classes
        if ($mapel->pengajar->count() > 0 || $mapel->kelas->count() > 0) {
            return redirect()->route('admin.mapel')->with('error', 'Tidak dapat menghapus mata pelajaran yang memiliki pengajar atau kelas');
        }

        $mapel->delete();

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil dihapus');
    }

    /**
     * Display material management page
     */
    public function materialManagement()
    {
        $materials = Materi::with(['user', 'mapel'])->get();
        
        return view('admin.material-management', [
            'title' => 'Manajemen Materi Admin',
            'materials' => $materials,
            'user' => Auth::user()
        ]);
    }

    /**
     * Store new material
     */
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:video,document,image,audio,other',
            'file' => 'nullable|file|mimes:pdf,doc,docx,mp4,mp3,jpg,jpeg,png|max:10240',
            'status' => 'required|string|in:active,inactive'
        ]);

        $material = new Materi();
        $material->title = $request->title;
        $material->description = $request->description;
        $material->category = $request->category;
        $material->status = $request->status;
        $material->user_id = Auth::id();
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/materials', $filename);
            $material->file_path = 'materials/' . $filename;
        }

        $material->save();

        return redirect()->route('admin.material')->with('success', 'Materi berhasil ditambahkan');
    }

    /**
     * Update material
     */
    public function updateMaterial(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:video,document,image,audio,other',
            'file' => 'nullable|file|mimes:pdf,doc,docx,mp4,mp3,jpg,jpeg,png|max:10240',
            'status' => 'required|string|in:active,inactive'
        ]);

        $material = Materi::findOrFail($id);
        $material->title = $request->title;
        $material->description = $request->description;
        $material->category = $request->category;
        $material->status = $request->status;
        
        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($material->file_path) {
                \Storage::delete('public/' . $material->file_path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/materials', $filename);
            $material->file_path = 'materials/' . $filename;
        }

        $material->save();

        return redirect()->route('admin.material')->with('success', 'Materi berhasil diperbarui');
    }

    /**
     * Delete material
     */
    public function deleteMaterial($id)
    {
        $material = Materi::findOrFail($id);
        
        // Delete file if exists
        if ($material->file_path) {
            \Storage::delete('public/' . $material->file_path);
        }

        $material->delete();

        return redirect()->route('admin.material')->with('success', 'Materi berhasil dihapus');
    }

    /**
     * Display user management page
     */
    public function userManagement()
    {
        // Admin hanya bisa melihat dan mengelola guru dan siswa
        $users = User::whereIn('roles_id', [3, 4])->with('Role')->get();
        
        return view('admin.user-management', [
            'title' => 'Manajemen Pengguna Admin',
            'users' => $users,
            'user' => Auth::user()
        ]);
    }

    /**
     * Show edit user form
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        // Pastikan admin tidak bisa edit user dengan role admin atau super admin
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            return response()->json([
                'error' => 'Admin tidak dapat mengedit user dengan role admin atau super admin.'
            ], 403);
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles_id' => $user->roles_id,
            'status' => $user->status
        ]);
    }

    /**
     * Store new user
     * Admin tidak bisa membuat user dengan role admin (roles_id = 2)
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles_id' => 'required|integer|in:3,4', // Hanya guru dan siswa
            'status' => 'required|string|in:active,inactive'
        ]);

        // Pastikan admin tidak bisa membuat admin atau super admin baru
        if ($request->roles_id == 1 || $request->roles_id == 2) {
            return redirect()->route('admin.user-management')->with('error', 'Admin tidak dapat membuat user dengan role admin atau super admin. Hanya Super Admin yang dapat membuat admin baru.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->roles_id = $request->roles_id;
        $user->status = $request->status;
        $user->save();

        return redirect()->route('admin.user-management')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Update user
     * Admin tidak bisa mengubah user menjadi admin (roles_id = 2)
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles_id' => 'required|integer|in:3,4', // Hanya guru dan siswa
            'status' => 'required|string|in:active,inactive'
        ]);

        // Pastikan admin tidak bisa mengubah user menjadi admin atau super admin
        if ($request->roles_id == 1 || $request->roles_id == 2) {
            return redirect()->route('admin.user-management')->with('error', 'Admin tidak dapat mengubah user menjadi admin atau super admin. Hanya Super Admin yang dapat melakukan ini.');
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->roles_id = $request->roles_id;
        $user->status = $request->status;
        
        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.user-management')->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.user-management')->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        // Prevent deleting admin or super admin
        if ($user->roles_id == 1 || $user->roles_id == 2) {
            return redirect()->route('admin.user-management')->with('error', 'Admin tidak dapat menghapus user dengan role admin atau super admin.');
        }

        $user->delete();

        return redirect()->route('admin.user-management')->with('success', 'Pengguna berhasil dihapus');
    }

    /**
     * Get chart data for dashboard
     */
    private function getChartData()
    {
        // Get monthly data for the last 6 months
        $months = [];
        $studentData = [];
        $teacherData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $studentData[] = DataSiswa::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
                
            $teacherData[] = User::where('roles_id', 3)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [
            'months' => $months,
            'students' => $studentData,
            'teachers' => $teacherData
        ];
    }

    /**
     * Get number of full classes
     */
    private function getKelasPenuh()
    {
        try {
            return Kelas::whereHas('siswa', function($query) {
                $query->havingRaw('COUNT(*) >= max_capacity');
            })->count();
        } catch (\Exception $e) {
            // Fallback: count classes with students >= 30 (assuming max capacity is 30)
            return Kelas::withCount('siswa')->having('siswa_count', '>=', 30)->count();
        }
    }

    /**
     * Edit Admin Material
     */
    public function editAdminMaterial($id)
    {
        $material = Materi::findOrFail($id);
        $subjects = Mapel::all();
        $classes = Kelas::all();
        
        return view('material-edit', [
            'title' => 'Edit Materi',
            'user' => Auth::user(),
            'userRole' => 'admin',
            'material' => $material,
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }
}
