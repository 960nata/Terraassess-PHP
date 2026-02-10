<?php

namespace App\Http\Controllers;

use App\Models\TugasKelompok;
use App\Models\AnggotaTugasKelompok;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupManagementController extends Controller
{
    /**
     * Display a listing of groups per class
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get classes that the user has access to
        if ($user->roles_id == 1) { // Super Admin
            $kelas = Kelas::with(['tugasKelompoks' => function($query) {
                $query->where('is_template', true)
                      ->with(['anggotaTugasKelompok.user']);
            }])->get();
        } else { // Teacher
            $kelas = Kelas::whereHas('users', function($query) use ($user) {
                $query->where('id', $user->id);
            })->with(['tugasKelompoks' => function($query) {
                $query->where('is_template', true)
                      ->with(['anggotaTugasKelompok.user']);
            }])->get();
        }

        return view('teacher.groups.index', [
            'title' => 'Manajemen Kelompok Kelas',
            'kelas' => $kelas,
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new group
     */
    public function create($kelas_id)
    {
        $user = Auth::user();
        $kelas = Kelas::findOrFail($kelas_id);
        
        // Verify access
        if ($user->roles_id != 1 && !$kelas->users()->where('id', $user->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini');
        }

        // Get students in this class
        $students = User::where('kelas_id', $kelas_id)
                      ->where('roles_id', 4) // 4 = Siswa (Student)
                      ->orderBy('name')
                      ->get();

        return view('teacher.groups.create', [
            'title' => 'Buat Kelompok Baru',
            'kelas' => $kelas,
            'students' => $students,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created group
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array|min:2',
            'members.*' => 'exists:users,id',
            'leader' => 'required|exists:users,id'
        ]);

        // Verify leader is in members list
        if (!in_array($request->leader, $request->members)) {
            return redirect()->back()
                ->with('error', 'Ketua kelompok harus merupakan anggota kelompok')
                ->withInput();
        }

        // Verify all members are students in the same class
        $students = User::where('kelas_id', $request->kelas_id)
                       ->where('roles_id', 4)  // 4 = Siswa (Student)
                       ->whereIn('id', $request->members)
                       ->get();

        if ($students->count() != count($request->members)) {
            return redirect()->back()
                ->with('error', 'Semua anggota harus merupakan siswa di kelas yang sama')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the group
            $group = TugasKelompok::create([
                'tugas_id' => null, // Template group, no specific task
                'kelas_id' => $request->kelas_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => 'active',
                'is_template' => true,
                'created_by' => $user->id
            ]);

            // Add members to the group
            foreach ($request->members as $memberId) {
                AnggotaTugasKelompok::create([
                    'tugas_kelompok_id' => $group->id,
                    'user_id' => $memberId,
                    'tugas_id' => null, // Template group
                    'isKetua' => $memberId == $request->leader ? 1 : 0
                ]);
            }

            DB::commit();

            return redirect()->route('teacher.groups.index')
                ->with('success', 'Kelompok berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat kelompok: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a group
     */
    public function edit($id)
    {
        $user = Auth::user();
        $group = TugasKelompok::with(['anggotaTugasKelompok.user', 'kelas'])
                             ->where('is_template', true)
                             ->findOrFail($id);

        // Verify access
        if ($user->roles_id != 1 && !$group->kelas->users()->where('id', $user->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke kelompok ini');
        }

        // Get all students in the class
        $students = User::where('kelas_id', $group->kelas_id)
                      ->where('roles_id', 4)  // 4 = Siswa (Student)
                      ->orderBy('name')
                      ->get();

        // Get current members
        $currentMembers = $group->anggotaTugasKelompok->pluck('user_id')->toArray();
        $currentLeader = $group->anggotaTugasKelompok->where('isKetua', 1)->first()?->user_id;

        return view('teacher.groups.edit', [
            'title' => 'Edit Kelompok',
            'group' => $group,
            'students' => $students,
            'currentMembers' => $currentMembers,
            'currentLeader' => $currentLeader,
            'user' => $user
        ]);
    }

    /**
     * Update the specified group
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $group = TugasKelompok::where('is_template', true)->findOrFail($id);

        // Verify access
        if ($user->roles_id != 1 && !$group->kelas->users()->where('id', $user->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke kelompok ini');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array|min:2',
            'members.*' => 'exists:users,id',
            'leader' => 'required|exists:users,id'
        ]);

        // Verify leader is in members list
        if (!in_array($request->leader, $request->members)) {
            return redirect()->back()
                ->with('error', 'Ketua kelompok harus merupakan anggota kelompok')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update group info
            $group->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Remove all current members
            $group->anggotaTugasKelompok()->delete();

            // Add new members
            foreach ($request->members as $memberId) {
                AnggotaTugasKelompok::create([
                    'tugas_kelompok_id' => $group->id,
                    'user_id' => $memberId,
                    'tugas_id' => null,
                    'isKetua' => $memberId == $request->leader ? 1 : 0
                ]);
            }

            DB::commit();

            return redirect()->route('teacher.groups.index')
                ->with('success', 'Kelompok berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kelompok: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified group
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $group = TugasKelompok::where('is_template', true)->findOrFail($id);

        // Verify access
        if ($user->roles_id != 1 && !$group->kelas->users()->where('id', $user->id)->exists()) {
            abort(403, 'Anda tidak memiliki akses ke kelompok ini');
        }

        try {
            DB::beginTransaction();

            // Check if group is being used in any tasks
            $taskGroups = TugasKelompok::where('name', $group->name)
                                     ->where('kelas_id', $group->kelas_id)
                                     ->where('is_template', false)
                                     ->exists();

            if ($taskGroups) {
                return redirect()->back()
                    ->with('error', 'Kelompok ini sedang digunakan dalam tugas. Tidak dapat dihapus.');
            }

            // Delete group members first
            $group->anggotaTugasKelompok()->delete();
            
            // Delete the group
            $group->delete();

            DB::commit();

            return redirect()->route('teacher.groups.index')
                ->with('success', 'Kelompok berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kelompok: ' . $e->getMessage());
        }
    }

    /**
     * Get students for a specific class (AJAX)
     */
    public function getStudents($kelas_id)
    {
        // NO VALIDATION - Semua authenticated users bisa akses
        // Middleware 'auth' sudah cukup untuk memastikan user login
        $students = User::where('kelas_id', $kelas_id)
                      ->where('roles_id', 4)  // 4 = Siswa (Student)
                      ->orderBy('name')
                      ->get(['id', 'name', 'email']);

        return response()->json($students);
    }

    /**
     * Get existing groups for a class (AJAX)
     */
    public function getGroups($kelas_id)
    {
        // NO VALIDATION - Semua authenticated users bisa akses
        // Middleware 'auth' sudah cukup untuk memastikan user login
        $groups = TugasKelompok::where('kelas_id', $kelas_id)
                             ->where('is_template', true)
                             ->with(['anggotaTugasKelompok.user'])
                             ->get();

        $groupsData = $groups->map(function($group) {
            $leader = $group->anggotaTugasKelompok->where('isKetua', 1)->first()?->user;
            $members = $group->anggotaTugasKelompok->pluck('user.name')->toArray();
            
            return [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'leader' => $leader ? $leader->name : null,
                'members' => $members,
                'member_count' => count($members)
            ];
        });

        return response()->json($groupsData);
    }
}
