<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use App\Models\KelasMapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectManagementController extends Controller
{
    /**
     * Display subject management page
     */
    public function index()
    {
        $subjects = Mapel::withCount(['KelasMapel as classes_count'])
            ->withCount(['KelasMapel as teachers_count' => function($query) {
                $query->whereHas('EditorAccess');
            }])
            ->orderBy('name')
            ->get();

        $totalSubjects = $subjects->count();
        $activeSubjects = $subjects->where('is_active', true)->count();
        $totalTeachers = User::where('roles_id', 2)->count();
        $totalClasses = DB::table('kelas')->count();

        return view('superadmin.subject-management-new', compact(
            'subjects',
            'totalSubjects',
            'activeSubjects',
            'totalTeachers',
            'totalClasses'
        ));
    }

    /**
     * Create new subject
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:mapels,name',
            'kategori' => 'required|string|in:akademik,sains,bahasa,sosial,seni',
            'code' => 'nullable|string|max:50|unique:mapels,code',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $subject = Mapel::create([
                'name' => $request->name,
                'kategori' => $request->kategori,
                'code' => $request->code,
                'deskripsi' => $request->description,
                'is_active' => $request->is_active ?? true,
            ]);

            DB::commit();

            return redirect()->route('superadmin.subject-management')
                ->with('success', 'Mata pelajaran berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form for subject
     */
    public function edit($id)
    {
        $subject = Mapel::findOrFail($id);
        
        return response()->json([
            'id' => $subject->id,
            'name' => $subject->name,
            'kategori' => $subject->kategori ?? 'akademik',
            'code' => $subject->code,
            'description' => $subject->deskripsi,
            'is_active' => $subject->is_active,
        ]);
    }

    /**
     * Update subject
     */
    public function update(Request $request, $id)
    {
        $subject = Mapel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:mapels,name,' . $id,
            'kategori' => 'required|string|in:akademik,sains,bahasa,sosial,seni',
            'code' => 'nullable|string|max:50|unique:mapels,code,' . $id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $subject->update([
                'name' => $request->name,
                'kategori' => $request->kategori,
                'code' => $request->code,
                'deskripsi' => $request->description,
                'is_active' => $request->is_active ?? true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil diperbarui!'
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
     * Delete subject
     */
    public function destroy($id)
    {
        $subject = Mapel::findOrFail($id);

        try {
            DB::beginTransaction();

            // Check if subject is being used in any classes
            $isUsed = KelasMapel::where('mapel_id', $id)->exists();
            
            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mata pelajaran tidak dapat dihapus karena sedang digunakan di kelas tertentu.'
                ], 400);
            }

            $subject->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus!'
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
     * Search subjects
     */
    public function search(Request $request)
    {
        $query = Mapel::withCount(['KelasMapel as classes_count'])
            ->withCount(['KelasMapel as teachers_count' => function($query) {
                $query->whereHas('EditorAccess');
            }]);

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $subjects = $query->orderBy('name')->get();

        return response()->json([
            'subjects' => $subjects
        ]);
    }
}
