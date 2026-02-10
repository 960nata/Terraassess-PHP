<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;
use App\Models\KelasMapel;
use App\Models\EditorAccess;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherAssignmentsExport;
use App\Imports\TeacherAssignmentsImport;

class TeacherAssignmentController extends Controller
{
    /**
     * Display teacher assignments management page
     */
    public function index()
    {
        $assignments = EditorAccess::with(['user', 'kelasMapel.kelas', 'kelasMapel.mapel'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $classes = Kelas::all();
        $subjects = Mapel::all();
        $teachers = User::where('roles_id', 2)->get();
        
        return view('admin.teacher-assignments', [
            'title' => 'Manajemen Penugasan Guru',
            'assignments' => $assignments,
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers
        ]);
    }
    
    /**
     * Display teacher assignments dashboard
     */
    public function dashboard()
    {
        $classes = Kelas::all();
        $subjects = Mapel::all();
        $teachers = User::where('roles_id', 2)->get();
        
        return view('admin.teacher-assignments-dashboard', [
            'title' => 'Dashboard Penugasan Guru',
            'classes' => $classes,
            'subjects' => $subjects,
            'teachers' => $teachers
        ]);
    }
    
    /**
     * Get subjects for a specific class
     */
    public function getSubjectsForClass($classId): JsonResponse
    {
        try {
            $class = Kelas::findOrFail($classId);
            
            $subjects = Mapel::whereHas('kelasMapels', function($query) use ($classId) {
                $query->where('kelas_id', $classId);
            })->get(['id', 'name', 'code']);
            
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load subjects'], 500);
        }
    }
    
    /**
     * Get teachers for a specific class-subject combination
     */
    public function getTeachersForClassSubject($classId, $subjectId): JsonResponse
    {
        try {
            $kelasMapel = KelasMapel::where('kelas_id', $classId)
                ->where('mapel_id', $subjectId)
                ->first();
            
            if (!$kelasMapel) {
                return response()->json(['error' => 'Class-subject combination not found'], 404);
            }
            
            // Get current teacher assignment
            $currentTeacher = EditorAccess::where('kelas_mapel_id', $kelasMapel->id)
                ->with('user:id,name,email')
                ->first();
            
            // Get all available teachers (role = 2)
            $availableTeachers = User::where('roles_id', 2)
                ->where('id', '!=', $currentTeacher?->user_id)
                ->get(['id', 'name', 'email']);
            
            return response()->json([
                'current_teacher' => $currentTeacher?->user,
                'available_teachers' => $availableTeachers
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load teachers'], 500);
        }
    }
    
    /**
     * Get teacher's current assignments
     */
    public function getTeacherAssignments($teacherId): JsonResponse
    {
        try {
            $teacher = User::findOrFail($teacherId);
            
            $assignments = EditorAccess::where('user_id', $teacherId)
                ->with(['kelasMapel.kelas:id,name', 'kelasMapel.mapel:id,name'])
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => $assignment->id,
                        'kelas_name' => $assignment->kelasMapel->kelas->name,
                        'mapel_name' => $assignment->kelasMapel->mapel->name,
                        'created_at' => $assignment->created_at
                    ];
                });
            
            return response()->json([
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email
                ],
                'assignments' => $assignments
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load teacher assignments'], 500);
        }
    }
    
    /**
     * Assign teacher to class-subject (single or bulk)
     */
    public function assignTeacher(Request $request): JsonResponse
    {
        // Handle bulk assignments
        if ($request->has('bulk_assignments')) {
            return $this->handleBulkAssignment($request);
        }
        
        // Handle single assignment
        $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'teacher_id' => 'required|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Find or create KelasMapel
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id
            ]);
            
            // Check if teacher is already assigned
            $existingAssignment = EditorAccess::where('kelas_mapel_id', $kelasMapel->id)
                ->where('user_id', $request->teacher_id)
                ->first();
            
            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru sudah ditugaskan ke kelas-mata pelajaran ini'
                ], 400);
            }
            
            // Create new assignment
            EditorAccess::create([
                'user_id' => $request->teacher_id,
                'kelas_mapel_id' => $kelasMapel->id
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditugaskan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menugaskan guru: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Handle bulk assignment of teacher to multiple class-subject combinations
     */
    private function handleBulkAssignment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
            'bulk_assignments' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $bulkAssignments = json_decode($request->bulk_assignments, true);
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            foreach ($bulkAssignments as $index => $assignment) {
                try {
                    // Find or create KelasMapel
                    $kelasMapel = KelasMapel::firstOrCreate([
                        'kelas_id' => $assignment['class_id'],
                        'mapel_id' => $assignment['subject_id']
                    ]);
                    
                    // Check if teacher is already assigned
                    $existingAssignment = EditorAccess::where('kelas_mapel_id', $kelasMapel->id)
                        ->where('user_id', $request->teacher_id)
                        ->first();
                    
                    if (!$existingAssignment) {
                        EditorAccess::create([
                            'user_id' => $request->teacher_id,
                            'kelas_mapel_id' => $kelasMapel->id
                        ]);
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Assignment {$index}: Guru sudah ditugaskan ke kelas-mata pelajaran ini";
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Assignment {$index}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Bulk assignment completed. Success: {$successCount}, Errors: {$errorCount}",
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan bulk assignment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update teacher assignment
     */
    public function updateAssignment(Request $request, $assignmentId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $assignment = EditorAccess::findOrFail($assignmentId);
            
            // Check if new teacher is already assigned to this class-subject
            $existingAssignment = EditorAccess::where('kelas_mapel_id', $assignment->kelas_mapel_id)
                ->where('user_id', $request->teacher_id)
                ->where('id', '!=', $assignmentId)
                ->first();
            
            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru sudah ditugaskan ke kelas-mata pelajaran ini'
                ], 400);
            }
            
            $assignment->update([
                'user_id' => $request->teacher_id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Penugasan berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui penugasan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete teacher assignment
     */
    public function deleteAssignment($assignmentId): JsonResponse
    {
        try {
            $assignment = EditorAccess::findOrFail($assignmentId);
            $assignment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Penugasan berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus penugasan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk assign teachers
     */
    public function bulkAssignTeachers(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array',
            'assignments.*.kelas_id' => 'required|exists:kelas,id',
            'assignments.*.mapel_id' => 'required|exists:mapels,id',
            'assignments.*.teacher_id' => 'required|exists:users,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            foreach ($request->assignments as $index => $assignment) {
                try {
                    // Find or create KelasMapel
                    $kelasMapel = KelasMapel::firstOrCreate([
                        'kelas_id' => $assignment['kelas_id'],
                        'mapel_id' => $assignment['mapel_id']
                    ]);
                    
                    // Check if teacher is already assigned
                    $existingAssignment = EditorAccess::where('kelas_mapel_id', $kelasMapel->id)
                        ->where('user_id', $assignment['teacher_id'])
                        ->first();
                    
                    if (!$existingAssignment) {
                        EditorAccess::create([
                            'user_id' => $assignment['teacher_id'],
                            'kelas_mapel_id' => $kelasMapel->id
                        ]);
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Assignment {$index}: Guru sudah ditugaskan";
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Assignment {$index}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Bulk assignment completed. Success: {$successCount}, Errors: {$errorCount}",
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan bulk assignment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk delete assignments
     */
    public function bulkDeleteAssignments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assignment_ids' => 'required|array',
            'assignment_ids.*' => 'exists:editor_accesses,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $deletedCount = EditorAccess::whereIn('id', $request->assignment_ids)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} penugasan",
                'deleted_count' => $deletedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus penugasan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get assignment statistics
     */
    public function getAssignmentStatistics(): JsonResponse
    {
        try {
            $stats = [
                'total_assignments' => EditorAccess::count(),
                'total_classes' => Kelas::count(),
                'total_subjects' => Mapel::count(),
                'total_teachers' => User::where('roles_id', 2)->count(),
                'assignments_by_class' => EditorAccess::with('kelasMapel.kelas')
                    ->get()
                    ->groupBy('kelasMapel.kelas.name')
                    ->map->count(),
                'assignments_by_subject' => EditorAccess::with('kelasMapel.mapel')
                    ->get()
                    ->groupBy('kelasMapel.mapel.name')
                    ->map->count(),
                'assignments_by_teacher' => EditorAccess::with('user')
                    ->get()
                    ->groupBy('user.name')
                    ->map->count(),
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }
    
    /**
     * Export assignments
     */
    public function exportAssignments(Request $request)
    {
        try {
            return Excel::download(new TeacherAssignmentsExport, 'teacher_assignments.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to export assignments'], 500);
        }
    }
    
    /**
     * Import assignments
     */
    public function importAssignments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            Excel::import(new TeacherAssignmentsImport, $request->file('file'));
            
            return response()->json([
                'success' => true,
                'message' => 'Assignments berhasil diimport'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimport assignments: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
    /**
     * Get teacher's available classes and subjects for task creation
     */
    public function getAvailableClassesSubjects(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $assignments = EditorAccess::where('user_id', $user->id)
                ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->get()
                ->groupBy('kelasMapel.kelas.name')
                ->map(function($classAssignments, $className) {
                    return [
                        'class_name' => $className,
                        'subjects' => $classAssignments->map(function($assignment) {
                            return [
                                'id' => $assignment->kelasMapel->id,
                                'name' => $assignment->kelasMapel->mapel->name,
                                'code' => $assignment->kelasMapel->mapel->code ?? 'N/A'
                            ];
                        })
                    ];
                });
            
            return response()->json($assignments);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load available classes and subjects'], 500);
        }
    }
    
    /**
     * Get student's class subjects and teachers
     */
    public function getStudentClassSubjects(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user->kelas_id) {
                return response()->json(['error' => 'Student not assigned to any class'], 400);
            }
            
            $classSubjects = KelasMapel::where('kelas_id', $user->kelas_id)
                ->with(['mapel', 'editorAccess.user'])
                ->get()
                ->map(function($kelasMapel) {
                    return [
                        'id' => $kelasMapel->id,
                        'subject_name' => $kelasMapel->mapel->name,
                        'subject_code' => $kelasMapel->mapel->code ?? 'N/A',
                        'teachers' => $kelasMapel->editorAccess->map(function($access) {
                            return [
                                'id' => $access->user->id,
                                'name' => $access->user->name,
                                'email' => $access->user->email
                            ];
                        })
                    ];
                });
            
            return response()->json($classSubjects);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load class subjects'], 500);
        }
    }
}
