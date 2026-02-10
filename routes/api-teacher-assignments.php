<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherAssignmentController;

/*
|--------------------------------------------------------------------------
| Teacher Assignment API Routes
|--------------------------------------------------------------------------
|
| API routes untuk management penugasan guru ke kelas-mata pelajaran
|
*/

// Routes untuk Admin/Superadmin
Route::middleware(['auth', 'role:admin|superadmin'])->group(function () {
    
    // Get subjects for a specific class
    Route::get('/classes/{classId}/subjects', [TeacherAssignmentController::class, 'getSubjectsForClass'])
        ->name('api.classes.subjects');
    
    // Get teachers for a specific class-subject combination
    Route::get('/classes/{classId}/subjects/{subjectId}/teachers', [TeacherAssignmentController::class, 'getTeachersForClassSubject'])
        ->name('api.classes.subjects.teachers');
    
    // Assign teacher to class-subject
    Route::post('/assign-teacher', [TeacherAssignmentController::class, 'assignTeacher'])
        ->name('api.assign.teacher');
    
    // Update teacher assignment
    Route::put('/assignments/{assignmentId}', [TeacherAssignmentController::class, 'updateAssignment'])
        ->name('api.assignments.update');
    
    // Delete teacher assignment
    Route::delete('/assignments/{assignmentId}', [TeacherAssignmentController::class, 'deleteAssignment'])
        ->name('api.assignments.delete');
    
    // Bulk assign teachers
    Route::post('/bulk-assign-teachers', [TeacherAssignmentController::class, 'bulkAssignTeachers'])
        ->name('api.bulk.assign.teachers');
    
    // Bulk delete assignments
    Route::delete('/bulk-delete-assignments', [TeacherAssignmentController::class, 'bulkDeleteAssignments'])
        ->name('api.bulk.delete.assignments');
    
    // Get assignment statistics
    Route::get('/assignments/statistics', [TeacherAssignmentController::class, 'getAssignmentStatistics'])
        ->name('api.assignments.statistics');
    
    // Export assignments
    Route::get('/assignments/export', [TeacherAssignmentController::class, 'exportAssignments'])
        ->name('api.assignments.export');
    
    // Import assignments
    Route::post('/assignments/import', [TeacherAssignmentController::class, 'importAssignments'])
        ->name('api.assignments.import');
});

// Routes untuk Teacher
Route::middleware(['auth', 'role:teacher'])->group(function () {
    
    // Get teacher's assigned classes and subjects
    Route::get('/teacher/assignments', [TeacherAssignmentController::class, 'getTeacherAssignments'])
        ->name('api.teacher.assignments');
    
    // Get specific teacher's assignments (for admin/superadmin)
    Route::get('/teachers/{teacherId}/assignments', [TeacherAssignmentController::class, 'getTeacherAssignments'])
        ->name('api.teachers.assignments');
    
    // Get teacher's available classes and subjects for task creation
    Route::get('/teacher/available-classes-subjects', [TeacherAssignmentController::class, 'getAvailableClassesSubjects'])
        ->name('api.teacher.available.classes.subjects');
});

// Routes untuk Student
Route::middleware(['auth', 'role:student'])->group(function () {
    
    // Get student's class subjects and teachers
    Route::get('/student/class-subjects', [TeacherAssignmentController::class, 'getStudentClassSubjects'])
        ->name('api.student.class.subjects');
});
