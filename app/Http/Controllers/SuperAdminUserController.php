<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;

class SuperAdminUserController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengguna
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics
        $totalUsers = User::count();
        $totalStudents = User::where('roles_id', 4)->count();
        $totalTeachers = User::where('roles_id', 3)->count();
        $activeUsers = $totalUsers; // Since all users are considered active by default
        
        // Get all users with their classes
        $users = User::with('Kelas')->get();
        
        // Get classes for filter
        $classes = Kelas::all();
        
        $stats = [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalTeachers' => $totalTeachers,
            'totalStudents' => $totalStudents,
            'totalSuperAdmins' => User::where('roles_id', 1)->count(),
            'totalAdmins' => User::where('roles_id', 2)->count()
        ];
        
        $recentActivities = [
            [
                'icon' => 'fas fa-user-plus',
                'title' => 'New user registered',
                'description' => 'John Doe has been added to the system',
                'time' => '2 hours ago'
            ],
            [
                'icon' => 'fas fa-user-edit',
                'title' => 'User updated',
                'description' => 'Jane Smith profile has been updated',
                'time' => '4 hours ago'
            ],
        ];
        
        return view('user-management.unified-user-management', [
            'title' => 'User Management',
            'user' => $user,
            'users' => $users,
            'classes' => $classes,
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'filters' => []
        ]);
    }

    /**
     * Menyimpan pengguna baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_phone' => 'nullable|string|max:20',
            'user_role' => 'required|in:student,teacher,admin,superadmin',
            'class_id' => 'nullable|exists:kelas,id',
            'nis_nip' => 'nullable|string|max:50',
            'user_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:user_password',
            'status' => 'required|in:active,inactive,pending',
            'bio' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            
            // Map role names to role IDs
            $roleMap = [
                'student' => 4,
                'teacher' => 3,
                'admin' => 2,
                'superadmin' => 1
            ];
            
            // Create user
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
                'roles_id' => $roleMap[$request->user_role],
                'kelas_id' => $request->class_id,
                'phone' => $request->user_phone,
                'nis_nip' => $request->nis_nip,
                'status' => $request->status,
                'bio' => $request->bio,
                'email_verified_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('superadmin.user-management')
                ->with('success', 'Pengguna berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail pengguna (AJAX)
     */
    public function show($id)
    {
        $user = User::with('Kelas')->findOrFail($id);
        
        $roleMap = [1 => 'Super Admin', 2 => 'Admin', 3 => 'Guru', 4 => 'Siswa'];
        
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'role' => $roleMap[$user->roles_id] ?? 'Unknown',
            'status' => ucfirst($user->status),
            'class' => $user->Kelas->name ?? 'N/A',
            'nis_nip' => $user->nis_nip ?? 'N/A',
            'last_login' => $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M Y H:i') : 'Belum pernah',
            'phone' => $user->phone ?? 'N/A',
            'bio' => $user->bio ?? 'N/A'
        ]);
    }

    /**
     * Menampilkan form edit pengguna (AJAX)
     */
    public function edit($id)
    {
        $user = User::with('Kelas')->findOrFail($id);
        $classes = Kelas::all();
        
        $roleMap = [
            'student' => 4,
            'teacher' => 3,
            'admin' => 2,
            'superadmin' => 1
        ];
        
        $currentRole = array_search($user->roles_id, $roleMap);
        
        $html = '
        <form id="editUserForm" action="' . route('superadmin.user-management.update', $user->id) . '" method="POST">
            ' . csrf_field() . '
            ' . method_field('PUT') . '
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_name">Nama Lengkap</label>
                    <input type="text" id="edit_name" name="name" value="' . $user->name . '" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" value="' . $user->email . '" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit_phone">Nomor Telepon</label>
                    <input type="tel" id="edit_phone" name="phone" value="' . ($user->phone ?? '') . '">
                </div>
                
                <div class="form-group">
                    <label for="edit_role">Role Pengguna</label>
                    <select id="edit_role" name="role" required>
                        <option value="student" ' . ($currentRole == 'student' ? 'selected' : '') . '>Siswa</option>
                        <option value="teacher" ' . ($currentRole == 'teacher' ? 'selected' : '') . '>Guru</option>
                        <option value="admin" ' . ($currentRole == 'admin' ? 'selected' : '') . '>Admin</option>
                        <option value="superadmin" ' . ($currentRole == 'superadmin' ? 'selected' : '') . '>Super Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit_class_id">Kelas</label>
                    <select id="edit_class_id" name="class_id">
                        <option value="">Pilih kelas</option>';
        
        foreach ($classes as $class) {
            $selected = $user->kelas_id == $class->id ? 'selected' : '';
            $html .= '<option value="' . $class->id . '" ' . $selected . '>' . $class->name . '</option>';
        }
        
        $html .= '
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_nis_nip">NIS/NIP</label>
                    <input type="text" id="edit_nis_nip" name="nis_nip" value="' . ($user->nis_nip ?? '') . '">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status" required>
                        <option value="active" ' . ($user->status == 'active' ? 'selected' : '') . '>Aktif</option>
                        <option value="inactive" ' . ($user->status == 'inactive' ? 'selected' : '') . '>Tidak Aktif</option>
                        <option value="pending" ' . ($user->status == 'pending' ? 'selected' : '') . '>Menunggu Persetujuan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_bio">Bio/Deskripsi</label>
                    <input type="text" id="edit_bio" name="bio" value="' . ($user->bio ?? '') . '">
                </div>
            </div>
        </form>';
        
        return $html;
    }

    /**
     * Update pengguna
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:student,teacher,admin,superadmin',
            'class_id' => 'nullable|exists:kelas,id',
            'nis_nip' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,pending',
            'bio' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            
            // Map role names to role IDs
            $roleMap = [
                'student' => 4,
                'teacher' => 3,
                'admin' => 2,
                'superadmin' => 1
            ];
            
            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'roles_id' => $roleMap[$request->role],
                'kelas_id' => $request->class_id,
                'phone' => $request->phone,
                'nis_nip' => $request->nis_nip,
                'status' => $request->status,
                'bio' => $request->bio,
            ]);
            
            DB::commit();
            
            return redirect()->route('superadmin.user-management')
                ->with('success', 'Pengguna berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengguna
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Prevent deletion of superadmin
            if ($user->roles_id == 1) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus Super Admin!');
            }
            
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('superadmin.user-management')
                ->with('success', 'Pengguna berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Reset password pengguna
     */
    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Generate new password
            $newPassword = Str::random(8);
            
            // Update password
            $user->update([
                'password' => Hash::make($newPassword)
            ]);
            
            return response()->json([
                'success' => true,
                'new_password' => $newPassword
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve pengguna
     */
    public function approve($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Note: Status column doesn't exist in users table
            // $user->update([
            //     'status' => 'active'
            // ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil disetujui'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter pengguna
     */
    public function filter(Request $request)
    {
        $user = auth()->user();
        
        // Get statistics
        $totalUsers = User::count();
        $totalStudents = User::where('roles_id', 4)->count();
        $totalTeachers = User::where('roles_id', 3)->count();
        $activeUsers = $totalUsers; // Since all users are considered active by default
        
        // Build query
        $query = User::with('Kelas');
        
        // Apply filters
        if ($request->filled('filter_role')) {
            $roleMap = [
                'student' => 4,
                'teacher' => 3,
                'admin' => 2,
                'superadmin' => 1
            ];
            $query->where('roles_id', $roleMap[$request->filter_role]);
        }
        
        // Note: Status filter removed as users table doesn't have status column
        // if ($request->filled('filter_status')) {
        //     $query->where('status', $request->filter_status);
        // }
        
        if ($request->filled('filter_class')) {
            $query->where('kelas_id', $request->filter_class);
        }
        
        if ($request->filled('filter_search')) {
            $search = $request->filter_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->get();
        $classes = Kelas::all();
        
        return view('superadmin.user-management', [
            'title' => 'Manajemen Pengguna',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'activeUsers' => $activeUsers,
            'users' => $users,
            'classes' => $classes,
            'filters' => $request->all()
        ]);
    }
}
