<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KelasMapelController;
use App\Http\Controllers\LoginRegistController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengajarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\GroupTaskController;
use App\Http\Controllers\GroupManagementController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RubrikController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\NpkSensorController;
use App\Http\Controllers\Student\ComplaintController;
use App\Http\Controllers\SuperAdmin\ComplaintManagementController;
use App\Http\Controllers\Admin\ComplaintManagementController as AdminComplaintManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome Page - Redirect authenticated users to their dashboard
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        switch ($user->roles_id) {
            case 1: // Super Admin
                return redirect()->route('superadmin.dashboard');
            case 2: // Admin
                return redirect()->route('admin.dashboard');
            case 3: // Teacher/Pengajar
                return redirect()->route('teacher.dashboard');
            case 4: // Student/Siswa
            default:
                return redirect()->route('student.dashboard');
        }
    }
    
    return view('home');
});

// Access Denied Page
Route::get('/access-denied', function () {
    return view('errors.access-denied');
})->name('access-denied');

// Guru Routes (Legacy - using teacher role)
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/guru/dashboard', [App\Http\Controllers\GuruController::class, 'dashboard'])->name('guru.dashboard');
    Route::get('/guru/data-pengajar', [App\Http\Controllers\GuruController::class, 'dataPengajar'])->name('guru.data-pengajar');
    Route::get('/guru/data-siswa', [App\Http\Controllers\GuruController::class, 'dataSiswa'])->name('guru.data-siswa');
    Route::get('/guru/data-kelas', [App\Http\Controllers\GuruController::class, 'dataKelas'])->name('guru.data-kelas');
    Route::get('/guru/data-mapel', [App\Http\Controllers\GuruController::class, 'dataMapel'])->name('guru.data-mapel');
});

// Login route - redirect to homepage for popup login
Route::get('/login', function () {
    return redirect('/')->with('showLogin', true);
})->name('login');

// Debug route for CSRF issues
Route::get('/debug-csrf', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_status' => session_status(),
        'session_data' => session()->all(),
        'timestamp' => now()
    ]);
})->name('debug.csrf');

// IoT Debug Routes (No Authentication Required)
Route::get('/iot-debug', [App\Http\Controllers\IotDebugController::class, 'index'])->name('iot.debug');
Route::post('/api/iot-debug/store-data', [App\Http\Controllers\IotDebugController::class, 'storeSensorData'])->name('api.iot.debug.store');
Route::get('/api/iot-debug/sensor-data', [App\Http\Controllers\IotDebugController::class, 'getSensorData'])->name('api.iot.debug.get');
Route::delete('/api/iot-debug/clear-data', [App\Http\Controllers\IotDebugController::class, 'clearDebugData'])->name('api.iot.debug.clear');

// Debug route for user authentication and role
Route::get('/debug-user', function() {
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'authenticated' => false,
            'message' => 'User not authenticated',
            'session_id' => session()->getId(),
            'session_data' => session()->all()
        ]);
    }
    
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'roles_id' => $user->roles_id,
        'role_name' => $user->Role ? $user->Role->name : 'No role found',
        'kelas_id' => $user->kelas_id,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
        'session_id' => session()->getId()
    ]);
})->middleware('auth')->name('debug.user');

// Test route to check if superadmin middleware works
Route::get('/test-superadmin', function() {
    return response()->json([
        'message' => 'Super admin access granted!',
        'user' => auth()->user()->name,
        'role' => auth()->user()->Role->name
    ]);
})->middleware(['auth', 'role:superadmin'])->name('test.superadmin');

// Demo Pages
Route::get('/demo/collapsible', function () {
    return view('demo.collapsible-demo');
})->name('demo.collapsible');

Route::get('/examples/collapsible-integration', function () {
    return view('examples.collapsible-integration');
})->name('examples.collapsible-integration');

Route::get('/demo/dropdown', function () {
    return view('demo.dropdown-demo');
})->name('demo.dropdown');

Route::get('/examples/dropdown-integration', function () {
    return view('examples.dropdown-integration');
})->name('examples.dropdown-integration');

Route::controller(AdminRegisterController::class)->group(function () {
    Route::get('/admin-register', 'viewAdminRegister')->middleware('guest')->name('adminRegister');
    Route::post('/regist-admin', 'registAdmin')->middleware('guest')->name('registAdmin');
    Route::get('/debug', 'debug')->name('debug');
});

Route::controller(LoginRegistController::class)->group(function () {
    // Get
    Route::get('/register', 'viewRegister')->middleware('guest')->name('register');
    Route::get('/forgot-password', 'viewForgotPassword')->middleware('guest')->name('forgotPassword');
    Route::get('/logout', 'logout')->middleware('auth')->name('logout.get');

    // Post
    Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('forgotPassword.post');
    Route::post('/vallidate-register', 'register')->middleware('guest')->name('validate');
    Route::post('/authenticate', 'authenticate')->middleware('guest')->name('authenticate');
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
});

// Dashboard - Role-based routing
Route::controller(DashboardController::class)->group(function () {
    // Get - Default dashboard (fallback)
    Route::get('/dashboard', 'viewDashboard')->middleware('auth')->name('dashboard');
    Route::get('/home', 'viewHome')->middleware('auth')->name('home');

    // Role-based dashboards
    Route::get('/superadmin/dashboard', 'viewSuperAdminDashboard')->middleware(['auth', 'role:superadmin'])->name('superadmin.dashboard');
    Route::get('/admin/dashboard', 'viewAdminDashboard')->middleware(['auth', 'role:admin'])->name('admin.dashboard');
    Route::get('/teacher/dashboard', 'viewTeacherDashboard')->middleware(['auth'])->name('teacher.dashboard');
    Route::get('/student/dashboard', 'viewStudentDashboard')->middleware(['auth', 'role:student'])->name('student.dashboard');

    // API Routes for Charts
    Route::get('/api/dashboard/chart-data', 'getChartData')->middleware('auth')->name('dashboard.chart-data');
    
    // Profile and Settings Routes
    Route::get('/superadmin/profile', 'viewSuperAdminProfile')->middleware(['auth', 'role:superadmin'])->name('superadmin.profile');
    Route::post('/superadmin/profile/update', 'updateSuperAdminProfile')->middleware(['auth', 'role:superadmin'])->name('superadmin.profile.update');
    Route::post('/superadmin/profile/upload-photo', 'uploadSuperAdminPhoto')->middleware(['auth', 'role:superadmin'])->name('superadmin.profile.upload-photo');
    Route::post('/superadmin/settings/update-password', 'updateSuperAdminPassword')->middleware(['auth', 'role:superadmin'])->name('superadmin.settings.update-password');
    Route::get('/superadmin/settings', 'viewSuperAdminSettings')->middleware(['auth', 'role:superadmin'])->name('superadmin.settings');
    
    // Super Admin Management Routes
    Route::get('/superadmin/push-notification', 'viewSuperAdminPushNotification')->middleware(['auth', 'role:superadmin'])->name('superadmin.push-notification');
    Route::post('/superadmin/push-notification/send', 'sendSuperAdminPushNotification')->middleware(['auth', 'role:superadmin'])->name('superadmin.push-notification.send');
    Route::get('/superadmin/push-notification/filter', 'filterSuperAdminPushNotifications')->middleware(['auth', 'role:superadmin'])->name('superadmin.push-notification.filter');
    Route::get('/superadmin/iot-management', 'viewSuperAdminIotManagement')->middleware(['auth', 'role:superadmin'])->name('superadmin.iot-management');
    Route::get('/superadmin/iot-management/filter', 'filterSuperAdminIotManagement')->middleware(['auth', 'role:superadmin'])->name('superadmin.iot-management.filter');
    Route::post('/superadmin/iot-management/register', 'registerIotDevice')->middleware(['auth', 'role:superadmin'])->name('superadmin.iot-management.register');
    Route::get('/superadmin/task-management', [App\Http\Controllers\SuperAdminTugasController::class, 'index'])->middleware(['auth', 'role:superadmin'])->name('superadmin.task-management');
    Route::get('/superadmin/task-management/filter', 'filterSuperAdminTasks')->middleware(['auth', 'role:superadmin'])->name('superadmin.task-management.filter');
    Route::get('/superadmin/material-management', 'viewSuperAdminMaterialManagement')->middleware(['auth', 'role:superadmin'])->name('superadmin.material-management');
    Route::get('/superadmin/class-management', 'viewSuperAdminClassManagement')->middleware(['auth', 'role:superadmin'])->name('superadmin.class-management');
    Route::get('/superadmin/subject-management', 'viewSuperAdminSubjectManagement')->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management');
    Route::get('/superadmin/reports', 'viewSuperAdminReports')->middleware(['auth', 'role:superadmin'])->name('superadmin.reports');
    Route::get('/superadmin/analytics', 'viewSuperAdminAnalytics')->middleware(['auth', 'role:superadmin'])->name('superadmin.analytics');
    Route::get('/superadmin/analytics/filter', 'filterSuperAdminAnalytics')->middleware(['auth', 'role:superadmin'])->name('superadmin.analytics.filter');
    
    // Debug route
    Route::get('/debug-user', function() {
        $user = auth()->user();
        return view('debug-user', compact('user'));
    })->middleware(['auth'])->name('debug-user');
    
    // Simple URLs without role prefix - All roles access same URLs
    Route::get('/task-management', [App\Http\Controllers\SuperAdminTugasController::class, 'index'])->middleware(['auth'])->name('task-management');
    Route::get('/task-management/filter', 'filterSuperAdminTasks')->middleware(['auth'])->name('task-management.filter');
    Route::get('/exam-management', 'viewSuperAdminExamManagement')->middleware(['auth'])->name('exam-management');
    Route::get('/material-management', 'viewSuperAdminMaterialManagement')->middleware(['auth'])->name('material-management');
    Route::get('/iot-management', 'viewSuperAdminIotManagement')->middleware(['auth'])->name('iot-management');
    Route::get('/user-management', [App\Http\Controllers\SuperAdminUserController::class, 'index'])->middleware(['auth'])->name('user-management');
    Route::get('/class-management', 'viewSuperAdminClassManagement')->middleware(['auth'])->name('class-management');
    Route::get('/subject-management', 'viewSuperAdminSubjectManagement')->middleware(['auth'])->name('subject-management');
    Route::get('/reports', 'viewSuperAdminReports')->middleware(['auth'])->name('reports');
    Route::get('/analytics', 'viewSuperAdminAnalytics')->middleware(['auth'])->name('analytics');
    
    // Super Admin Task Management Routes (New)
    Route::prefix('superadmin/tasks')->middleware(['auth', 'granular.rbac:task-management,create'])->group(function () {
        Route::get('/create/multiple-choice', [App\Http\Controllers\Teacher\TaskController::class, 'createMultipleChoice'])->name('superadmin.tasks.create.multiple-choice');
        Route::get('/create/essay', [App\Http\Controllers\Teacher\TaskController::class, 'createEssay'])->name('superadmin.tasks.create.essay');
        Route::get('/create/individual', [App\Http\Controllers\Teacher\TaskController::class, 'createIndividual'])->name('superadmin.tasks.create.individual');
        Route::get('/create/group', [App\Http\Controllers\Teacher\TaskController::class, 'createGroup'])->name('superadmin.tasks.create.group');
        Route::post('/', [App\Http\Controllers\Teacher\TaskController::class, 'store'])->name('superadmin.tasks.store');
    });
    
    // New comprehensive task management routes
    Route::get('/superadmin/tugas', [App\Http\Controllers\SuperAdminTugasController::class, 'index'])->middleware(['auth', 'granular.rbac:task-management,view'])->name('superadmin.tugas.index');
    Route::get('/superadmin/tugas/create/{tipe}', [App\Http\Controllers\SuperAdminTugasController::class, 'create'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('superadmin.tugas.create');
    Route::post('/superadmin/tugas', [App\Http\Controllers\SuperAdminTugasController::class, 'store'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('superadmin.tugas.store');
    Route::get('/superadmin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'show'])->middleware(['auth', 'granular.rbac:task-management,view'])->name('superadmin.tugas.show');
    Route::get('/superadmin/tugas/{id}/edit', [App\Http\Controllers\SuperAdminTugasController::class, 'edit'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('superadmin.tugas.edit');
    Route::put('/superadmin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'update'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('superadmin.tugas.update');
    Route::delete('/superadmin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'destroy'])->middleware(['auth', 'granular.rbac:task-management,delete'])->name('superadmin.tugas.destroy');
    Route::post('/superadmin/tugas/feedback', [App\Http\Controllers\SuperAdminTugasController::class, 'storeFeedback'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('superadmin.tugas.feedback');
    Route::get('/superadmin/tugas/{id}/penilaian-kelompok', [App\Http\Controllers\SuperAdminTugasController::class, 'penilaianKelompok'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('superadmin.tugas.penilaian-kelompok');
    Route::post('/superadmin/tugas/penilaian-kelompok', [App\Http\Controllers\SuperAdminTugasController::class, 'storePenilaianKelompok'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('superadmin.tugas.store-penilaian-kelompok');
    
    // API routes for AJAX
    Route::get('/superadmin/api/students-by-class/{kelasId}', [App\Http\Controllers\SuperAdminTugasController::class, 'getStudentsByClass'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('superadmin.api.students-by-class');
    
    // SuperAdmin Complaint Management Routes
    Route::get('/superadmin/complaints', [ComplaintManagementController::class, 'index'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.index');
    Route::get('/superadmin/complaints/{complaint}', [ComplaintManagementController::class, 'show'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.show');
    Route::put('/superadmin/complaints/{complaint}', [ComplaintManagementController::class, 'update'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.update');
    Route::post('/superadmin/complaints/{complaint}/reply', [ComplaintManagementController::class, 'reply'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.reply');
    Route::delete('/superadmin/complaints/{complaint}', [ComplaintManagementController::class, 'destroy'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.destroy');
    Route::get('/superadmin/complaints/statistics', [ComplaintManagementController::class, 'statistics'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.statistics');
    
    // SuperAdmin Attachment Routes
    Route::get('/superadmin/complaints/attachments/{attachment}/download', [ComplaintManagementController::class, 'downloadAttachment'])->middleware(['auth', 'role:superadmin'])->name('superadmin.complaints.attachments.download');
    
    // Editor Image Upload
    Route::post('/upload-editor-image', [App\Http\Controllers\FileController::class, 'uploadEditorImage'])->middleware(['auth'])->name('upload.editor.image');
    
    // User Management Routes
    Route::get('/superadmin/user-management', [App\Http\Controllers\SuperAdminUserController::class, 'index'])->middleware(['auth', 'granular.rbac:user-management,view'])->name('superadmin.user-management');
    Route::post('/superadmin/user-management', [App\Http\Controllers\SuperAdminUserController::class, 'store'])->middleware(['auth', 'granular.rbac:user-management,create'])->name('superadmin.user-management.create');
    Route::get('/superadmin/user-management/{id}', [App\Http\Controllers\SuperAdminUserController::class, 'show'])->middleware(['auth', 'granular.rbac:user-management,view'])->name('superadmin.user-management.show');
    Route::get('/superadmin/user-management/{id}/edit', [App\Http\Controllers\SuperAdminUserController::class, 'edit'])->middleware(['auth', 'granular.rbac:user-management,edit'])->name('superadmin.user-management.edit');
    Route::put('/superadmin/user-management/{id}', [App\Http\Controllers\SuperAdminUserController::class, 'update'])->middleware(['auth', 'granular.rbac:user-management,edit'])->name('superadmin.user-management.update');
    Route::delete('/superadmin/user-management/{id}', [App\Http\Controllers\SuperAdminUserController::class, 'destroy'])->middleware(['auth', 'granular.rbac:user-management,delete'])->name('superadmin.user-management.destroy');
    Route::post('/superadmin/user-management/{id}/reset-password', [App\Http\Controllers\SuperAdminUserController::class, 'resetPassword'])->middleware(['auth', 'role:superadmin'])->name('superadmin.user-management.reset-password');
    Route::post('/superadmin/user-management/{id}/approve', [App\Http\Controllers\SuperAdminUserController::class, 'approve'])->middleware(['auth', 'role:superadmin'])->name('superadmin.user-management.approve');
    Route::get('/superadmin/user-management/filter', [App\Http\Controllers\SuperAdminUserController::class, 'filter'])->middleware(['auth', 'role:superadmin'])->name('superadmin.user-management.filter');
    
    // Report Management Routes
    Route::get('/superadmin/reports', [App\Http\Controllers\SuperAdminReportController::class, 'index'])->middleware(['auth', 'role:superadmin'])->name('superadmin.reports');
    Route::get('/superadmin/reports/filter', [App\Http\Controllers\SuperAdminReportController::class, 'filter'])->middleware(['auth', 'role:superadmin'])->name('superadmin.reports.filter');
    Route::get('/superadmin/reports/export/{type}/{id?}', [App\Http\Controllers\SuperAdminReportController::class, 'export'])->middleware(['auth', 'role:superadmin'])->name('superadmin.reports.export');
    
    Route::get('/superadmin/exam-management', 'viewSuperAdminExamManagement')->middleware(['auth', 'granular.rbac:exam-management,view'])->name('superadmin.exam-management');
    Route::post('/superadmin/exam-management/create', 'createSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create');
    Route::get('/superadmin/exam-management/create-multiple-choice', 'viewSuperAdminCreateMultipleChoiceExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-multiple-choice');
    Route::post('/superadmin/exam-management/create-multiple-choice', 'createSuperAdminMultipleChoiceExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-multiple-choice.store');
    Route::get('/superadmin/exam-management/create-essay', 'viewSuperAdminCreateEssayExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-essay');
    Route::post('/superadmin/exam-management/create-essay', 'createSuperAdminEssayExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-essay.store');
    Route::get('/superadmin/exam-management/create-mixed', 'viewSuperAdminCreateMixedExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-mixed');
    Route::post('/superadmin/exam-management/create-mixed', 'createSuperAdminMixedExam')->middleware(['auth', 'granular.rbac:exam-management,create'])->name('superadmin.exam-management.create-mixed.store');
    Route::get('/superadmin/exam-management/filter', 'filterSuperAdminExams')->middleware(['auth', 'granular.rbac:exam-management,view'])->name('superadmin.exam-management.filter');
    Route::get('/superadmin/exam-management/{id}/edit', 'editSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,edit'])->name('superadmin.exam-management.edit');
    Route::put('/superadmin/exam-management/{id}', 'updateSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,edit'])->name('superadmin.exam-management.update');
    Route::get('/superadmin/exam-management/{id}/view', 'viewSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,view'])->name('superadmin.exam-management.view');
    Route::get('/superadmin/exam-management/{id}/results', 'viewSuperAdminExamResults')->middleware(['auth', 'granular.rbac:exam-management,view'])->name('superadmin.exam-management.results');
    Route::delete('/superadmin/exam-management/{id}/delete', 'deleteSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,delete'])->name('superadmin.exam-management.delete');
    Route::post('/superadmin/exam-management/{id}/publish', 'publishSuperAdminExam')->middleware(['auth', 'granular.rbac:exam-management,edit'])->name('superadmin.exam-management.publish');
    Route::get('/superadmin/class-management', 'viewSuperAdminClassManagement')->middleware(['auth'])->name('superadmin.class-management');
    Route::post('/superadmin/class-management/create', 'createSuperAdminClass')->middleware(['auth'])->name('superadmin.class-management.create');
    Route::get('/superadmin/class-management/filter', 'filterSuperAdminClasses')->middleware(['auth'])->name('superadmin.class-management.filter');
    Route::get('/superadmin/class-management/{id}', 'viewSuperAdminClassDetail')->middleware(['auth'])->name('superadmin.class-management.view');
    Route::get('/superadmin/class-management/{id}/edit', 'editSuperAdminClass')->middleware(['auth'])->name('superadmin.class-management.edit');
    Route::put('/superadmin/class-management/{id}', 'updateSuperAdminClass')->middleware(['auth'])->name('superadmin.class-management.update');
    Route::delete('/superadmin/class-management/{id}', 'deleteSuperAdminClass')->middleware(['auth'])->name('superadmin.class-management.delete');
    Route::get('/superadmin/subject-management', 'viewSuperAdminSubjectManagement')->middleware(['auth'])->name('superadmin.subject-management');
    Route::post('/superadmin/subject-management/create', 'createSuperAdminSubject')->middleware(['auth'])->name('superadmin.subject-management.create');
    Route::get('/superadmin/subject-management/filter', 'filterSuperAdminSubjects')->middleware(['auth'])->name('superadmin.subject-management.filter');
    
    // New Subject Management Routes
    Route::get('/superadmin/subject-management-new', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'index'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.new');
    Route::post('/superadmin/subject-management', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'store'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.store');
    Route::get('/superadmin/subject-management/{id}/edit', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'edit'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.edit');
    Route::put('/superadmin/subject-management/{id}', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'update'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.update');
    Route::delete('/superadmin/subject-management/{id}', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'destroy'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.destroy');
    Route::get('/superadmin/subject-management-search', [App\Http\Controllers\SuperAdmin\SubjectManagementController::class, 'search'])->middleware(['auth', 'role:superadmin'])->name('superadmin.subject-management.search');
    
    // New IoT Management Routes
    Route::get('/superadmin/iot-management-new', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'index'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.new');
    Route::post('/superadmin/iot-management', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'store'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.store');
    Route::get('/superadmin/iot-management/{id}/edit', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'edit'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.edit');
    Route::put('/superadmin/iot-management/{id}', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'update'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.update');
    Route::delete('/superadmin/iot-management/{id}', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'destroy'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.destroy');
    Route::post('/superadmin/iot-management/{id}/connect', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'connect'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.connect');
    Route::post('/superadmin/iot-management/{id}/disconnect', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'disconnect'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.disconnect');
    Route::get('/superadmin/iot-management/{id}/data', [App\Http\Controllers\SuperAdmin\IotManagementController::class, 'getDeviceData'])->middleware(['auth', 'role:superadmin', 'teacher.limited'])->name('superadmin.iot-management.data');
    Route::get('/superadmin/analytics', 'viewSuperAdminAnalytics')->middleware(['auth'])->name('superadmin.analytics');
    Route::get('/superadmin/material-management', 'viewSuperAdminMaterialManagement')->middleware(['auth', 'granular.rbac:material-management,view'])->name('superadmin.material-management');
    Route::get('/superadmin/material-management/filter', 'filterSuperAdminMaterials')->middleware(['auth', 'granular.rbac:material-management,view'])->name('superadmin.material-management.filter');
    Route::get('/superadmin/material-management/create', 'viewSuperAdminMaterialCreate')->middleware(['auth', 'granular.rbac:material-management,create'])->name('superadmin.material-management.create');
    Route::post('/superadmin/material-management/store', 'createSuperAdminMaterial')->middleware(['auth', 'granular.rbac:material-management,create'])->name('superadmin.material-management.store');
    Route::get('/superadmin/material-management/{id}', 'viewSuperAdminMaterialDetail')->middleware(['auth', 'granular.rbac:material-management,view'])->name('superadmin.material-management.show');
    Route::get('/superadmin/material-management/{id}/edit', 'editSuperAdminMaterial')->middleware(['auth', 'granular.rbac:material-management,edit'])->name('superadmin.material-management.edit');
    Route::put('/superadmin/material-management/{id}', 'updateSuperAdminMaterial')->middleware(['auth', 'granular.rbac:material-management,edit'])->name('superadmin.material-management.update');
    Route::delete('/superadmin/material-management/{id}', 'deleteSuperAdminMaterial')->middleware(['auth', 'granular.rbac:material-management,delete'])->name('superadmin.material-management.delete');
    Route::get('/superadmin/reports', 'viewSuperAdminReports')->middleware(['auth'])->name('superadmin.reports');
    Route::get('/superadmin/reports/filter', 'filterSuperAdminReports')->middleware(['auth'])->name('superadmin.reports.filter');
    Route::get('/superadmin/help', 'viewSuperAdminHelp')->middleware(['auth', 'role:superadmin'])->name('superadmin.help');
    Route::get('/superadmin/iot-debug', 'viewSuperAdminIotDebug')->middleware(['auth', 'role:superadmin'])->name('superadmin.iot-debug');
    
    // IoT Tasks and Research Routes
    Route::get('/superadmin/iot-tasks', 'viewSuperAdminIotTasks')->middleware(['auth', 'role:superadmin'])->name('superadmin.iot-tasks');
    
    // Admin Management Routes (Matching Superadmin)
    Route::get('/admin/profile', 'viewAdminProfile')->middleware(['auth', 'role:admin'])->name('admin.profile');
    Route::post('/admin/profile/update', 'updateAdminProfile')->middleware(['auth', 'role:admin'])->name('admin.profile.update');
    Route::post('/admin/profile/upload-photo', 'uploadAdminPhoto')->middleware(['auth', 'role:admin'])->name('admin.profile.upload-photo');
    Route::get('/admin/push-notification', 'viewAdminPushNotification')->middleware(['auth'])->name('admin.push-notification');
    Route::post('/admin/push-notification/send', 'sendAdminPushNotification')->middleware(['auth'])->name('admin.push-notification.send');
    Route::get('/admin/push-notification/filter', 'filterAdminPushNotifications')->middleware(['auth'])->name('admin.push-notification.filter');
    Route::get('/admin/iot-dashboard', 'viewAdminIotDashboard')->middleware(['auth', 'role:admin'])->name('admin.iot-dashboard');
    Route::get('/admin/iot-management', 'viewAdminIotManagement')->middleware(['auth', 'role:admin'])->name('admin.iot-management');
    Route::get('/admin/iot-management/filter', 'filterAdminIotManagement')->middleware(['auth', 'role:admin'])->name('admin.iot-management.filter');
    Route::post('/admin/iot-management/register', 'registerAdminIotDevice')->middleware(['auth', 'role:admin'])->name('admin.iot-management.register');
    Route::get('/admin/task-management', 'viewAdminTaskManagement')->middleware(['auth', 'role:admin'])->name('admin.task-management');
    Route::post('/admin/task-management/create', 'createAdminTask')->middleware(['auth', 'role:admin'])->name('admin.task-management.create');
    Route::get('/admin/task-management/filter', 'filterAdminTasks')->middleware(['auth', 'role:admin'])->name('admin.task-management.filter');
    Route::get('/admin/class-management', 'viewAdminClassManagement')->middleware(['auth', 'role:admin'])->name('admin.class-management');
    Route::post('/admin/class-management/create', 'createAdminClass')->middleware(['auth', 'role:admin'])->name('admin.class-management.create');
    Route::get('/admin/class-management/filter', 'filterAdminClasses')->middleware(['auth', 'role:admin'])->name('admin.class-management.filter');
    Route::get('/admin/subject-management', 'viewAdminSubjectManagement')->middleware(['auth', 'role:admin'])->name('admin.subject-management');
    Route::get('/admin/material-management', 'viewAdminMaterialManagement')->middleware(['auth', 'role:admin'])->name('admin.material-management');
    
    // Admin Reports and Analytics Routes
    Route::get('/admin/reports', 'viewAdminReports')->middleware(['auth', 'role:admin'])->name('admin.reports');
    Route::get('/admin/reports/filter', 'filterAdminReports')->middleware(['auth', 'role:admin'])->name('admin.reports.filter');
    Route::get('/admin/analytics', 'viewAdminAnalytics')->middleware(['auth', 'role:admin'])->name('admin.analytics');
    Route::get('/admin/exam-management', [App\Http\Controllers\DashboardController::class, 'viewAdminExamManagement'])->middleware(['auth', 'role:admin'])->name('admin.exam-management');
    Route::get('/admin/help', 'viewAdminHelp')->middleware(['auth', 'role:admin'])->name('admin.help');
    Route::get('/admin/settings', 'viewAdminSettings')->middleware(['auth', 'role:admin'])->name('admin.settings');
    Route::post('/admin/settings/update', 'updateAdminSettings')->middleware(['auth', 'role:admin'])->name('admin.settings.update');
    Route::get('/admin/data/export', 'adminDataExport')->middleware(['auth', 'role:admin'])->name('admin.data.export');
    Route::post('/admin/data/backup', 'adminDataBackup')->middleware(['auth', 'role:admin'])->name('admin.data.backup');
    Route::post('/admin/cache/clear', 'adminCacheClear')->middleware(['auth', 'role:admin'])->name('admin.cache.clear');
    Route::post('/admin/logout/all', 'adminLogoutAll')->middleware(['auth', 'role:admin'])->name('admin.logout.all');
    Route::delete('/admin/account/delete', 'adminAccountDelete')->middleware(['auth', 'role:admin'])->name('admin.account.delete');
    
    // Admin Task Management Routes (Same as Super Admin) - Fixed
    Route::get('/admin/tugas', [App\Http\Controllers\SuperAdminTugasController::class, 'index'])->middleware(['auth', 'granular.rbac:task-management,view'])->name('admin.tugas.index');
    Route::get('/admin/tugas/create/{tipe}', [App\Http\Controllers\SuperAdminTugasController::class, 'create'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('admin.tugas.create');
    Route::post('/admin/tugas', [App\Http\Controllers\SuperAdminTugasController::class, 'store'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('admin.tugas.store');
    Route::get('/admin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'show'])->middleware(['auth', 'granular.rbac:task-management,view'])->name('admin.tugas.show');
    Route::get('/admin/tugas/{id}/edit', [App\Http\Controllers\SuperAdminTugasController::class, 'edit'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('admin.tugas.edit');
    Route::put('/admin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'update'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('admin.tugas.update');
    Route::delete('/admin/tugas/{id}', [App\Http\Controllers\SuperAdminTugasController::class, 'destroy'])->middleware(['auth', 'granular.rbac:task-management,delete'])->name('admin.tugas.destroy');
    Route::post('/admin/tugas/feedback', [App\Http\Controllers\SuperAdminTugasController::class, 'storeFeedback'])->middleware(['auth', 'granular.rbac:task-management,edit'])->name('admin.tugas.feedback');
    Route::get('/admin/tugas/{id}/penilaian-kelompok', [App\Http\Controllers\SuperAdminTugasController::class, 'penilaianKelompok'])->middleware(['auth', 'granular.rbac:task-management,view'])->name('admin.tugas.penilaian-kelompok');
    Route::post('/admin/tugas/penilaian-kelompok', [App\Http\Controllers\SuperAdminTugasController::class, 'storePenilaianKelompok'])->middleware(['auth', 'role:admin'])->name('admin.tugas.store-penilaian-kelompok');
    
    // API routes for AJAX
    Route::get('/admin/api/students-by-class/{kelasId}', [App\Http\Controllers\SuperAdminTugasController::class, 'getStudentsByClass'])->middleware(['auth', 'granular.rbac:task-management,create'])->name('admin.api.students-by-class');
    
    // Admin Complaint Management Routes
    Route::get('/admin/complaints', [AdminComplaintManagementController::class, 'index'])->middleware(['auth', 'role:admin'])->name('admin.complaints.index');
    Route::get('/admin/complaints/{complaint}', [AdminComplaintManagementController::class, 'show'])->middleware(['auth', 'role:admin'])->name('admin.complaints.show');
    Route::put('/admin/complaints/{complaint}', [AdminComplaintManagementController::class, 'update'])->middleware(['auth', 'role:admin'])->name('admin.complaints.update');
    Route::post('/admin/complaints/{complaint}/reply', [AdminComplaintManagementController::class, 'reply'])->middleware(['auth', 'role:admin'])->name('admin.complaints.reply');
    Route::delete('/admin/complaints/{complaint}', [AdminComplaintManagementController::class, 'destroy'])->middleware(['auth', 'role:admin'])->name('admin.complaints.destroy');
    Route::get('/admin/complaints/statistics', [AdminComplaintManagementController::class, 'statistics'])->middleware(['auth', 'role:admin'])->name('admin.complaints.statistics');
    
    // Admin Attachment Routes
    Route::get('/admin/complaints/attachments/{attachment}/download', [AdminComplaintManagementController::class, 'downloadAttachment'])->middleware(['auth', 'role:admin'])->name('admin.complaints.attachments.download');
    
    // Admin Task Management Routes (New) - Fixed
    Route::prefix('admin/tasks')->middleware(['auth', 'granular.rbac:task-management,create'])->group(function () {
        Route::get('/create/{tipe}', [App\Http\Controllers\Teacher\TaskController::class, 'create'])->name('admin.tasks.create');
        Route::get('/create/multiple-choice', [App\Http\Controllers\Teacher\TaskController::class, 'createMultipleChoice'])->name('admin.tasks.create.multiple-choice');
        Route::get('/create/essay', [App\Http\Controllers\Teacher\TaskController::class, 'createEssay'])->name('admin.tasks.create.essay');
        Route::get('/create/individual', [App\Http\Controllers\Teacher\TaskController::class, 'createIndividual'])->name('admin.tasks.create.individual');
        Route::get('/create/group', [App\Http\Controllers\Teacher\TaskController::class, 'createGroup'])->name('admin.tasks.create.group');
        Route::post('/', [App\Http\Controllers\Teacher\TaskController::class, 'store'])->name('admin.tasks.store');
        Route::get('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'show'])->middleware(['granular.rbac:task-management,view'])->name('admin.tasks.show');
        Route::get('/{id}/edit', [App\Http\Controllers\Teacher\TaskController::class, 'edit'])->middleware(['granular.rbac:task-management,edit'])->name('admin.tasks.edit');
        Route::put('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'update'])->middleware(['granular.rbac:task-management,edit'])->name('admin.tasks.update');
        Route::delete('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'destroy'])->middleware(['granular.rbac:task-management,delete'])->name('admin.tasks.destroy');
    });
    // Admin exam management routes removed - admin should use superadmin routes for consistency
    // Admin management routes removed - admin should use superadmin routes for consistency
    // All admin management routes removed - admin should use superadmin routes for consistency
    // All remaining admin routes removed - admin should use superadmin routes for consistency
    
    // Teacher Management Routes (Matching Superadmin) - with access control
    Route::get('/teacher/profile', 'viewTeacherProfile')->middleware(['auth', 'role:teacher'])->name('teacher.profile');
    Route::post('/teacher/profile/update', 'updateTeacherProfile')->middleware(['auth', 'role:teacher'])->name('teacher.profile.update');
    Route::post('/teacher/profile/upload-photo', 'uploadTeacherPhoto')->middleware(['auth', 'role:teacher'])->name('teacher.profile.upload-photo');
    Route::post('/teacher/settings/update-password', 'updateTeacherPassword')->middleware(['auth', 'role:teacher'])->name('teacher.settings.update-password');
    Route::get('/teacher/settings', 'viewTeacherSettings')->middleware(['auth', 'role:teacher'])->name('teacher.settings');
    Route::get('/teacher/push-notification', 'viewTeacherPushNotification')->middleware(['auth', 'role:teacher'])->name('teacher.push-notification');
    Route::post('/teacher/push-notification/send', 'sendTeacherPushNotification')->middleware(['auth', 'role:teacher'])->name('teacher.push-notification.send');
    Route::get('/teacher/push-notification/filter', 'filterTeacherPushNotifications')->middleware(['auth', 'role:teacher'])->name('teacher.push-notification.filter');
    // Teacher routes removed - using superadmin routes instead
    // Teacher management routes removed - using superadmin routes instead
    Route::get('/teacher/reports', 'viewTeacherReports')->middleware(['auth', 'role:teacher'])->name('teacher.reports');
    Route::get('/teacher/reports/filter', 'viewTeacherReports')->middleware(['auth', 'role:teacher'])->name('teacher.reports.filter');
    Route::get('/teacher/reports/export', 'exportTeacherReports')->middleware(['auth', 'role:teacher'])->name('teacher.reports.export');
    Route::get('/teacher/reports/task/{id}', 'viewTeacherTaskDetail')->middleware(['auth', 'role:teacher'])->name('teacher.reports.task.detail');
    Route::get('/teacher/reports/exam/{id}', 'viewTeacherExamDetail')->middleware(['auth', 'role:teacher'])->name('teacher.reports.exam.detail');
    Route::get('/teacher/reports/student/{id}', 'viewTeacherStudentDetail')->middleware(['auth', 'role:teacher'])->name('teacher.reports.student.detail');
    Route::get('/teacher/help', 'viewTeacherHelp')->middleware(['auth', 'role:teacher'])->name('teacher.help');
    Route::get('/teacher/analytics', 'viewTeacherAnalytics')->middleware(['auth', 'role:teacher'])->name('teacher.analytics');
    Route::get('/api/teacher/analytics-data', 'getTeacherAnalyticsData')->middleware(['auth', 'role:teacher'])->name('api.teacher.analytics-data');
    
    // Student Management Routes (Matching Superadmin)
    Route::get('/student/profile', 'viewStudentProfile')->middleware(['auth', 'role:student'])->name('student.profile');
    Route::post('/student/profile/update', 'updateStudentProfile')->middleware(['auth', 'role:student'])->name('student.profile.update');
    Route::post('/student/profile/upload-photo', 'uploadStudentPhoto')->middleware(['auth', 'role:student'])->name('student.profile.upload-photo');
    Route::get('/student/settings', 'viewStudentSettings')->middleware(['auth', 'role:student'])->name('student.settings');
    
    // Student Dashboard Route Aliases (English names for student-new.blade.php)
    Route::get('/student/tasks', 'viewStudentTaskManagement')->middleware(['auth', 'role:student'])->name('student.tasks');
    Route::get('/student/exams', 'viewStudentExamManagement')->middleware(['auth', 'role:student'])->name('student.exams');
    Route::get('/student/materials', 'viewStudentMaterialManagement')->middleware(['auth', 'role:student'])->name('student.materials');
    Route::get('/student/iot-data', 'viewStudentIotManagement')->middleware(['auth', 'role:student'])->name('student.iot-data');
    Route::get('/student/iot-research', 'viewStudentIotManagement')->middleware(['auth', 'role:student'])->name('student.iot-research');
    Route::get('/student/grades', 'viewStudentReports')->middleware(['auth', 'role:student'])->name('student.grades');
    Route::get('/student/schedule', 'viewStudentClassManagement')->middleware(['auth', 'role:student'])->name('student.schedule');
    Route::get('/student/assignments', 'viewStudentTaskManagement')->middleware(['auth', 'role:student'])->name('student.assignments');
    Route::get('/student/progress', 'viewStudentReports')->middleware(['auth', 'role:student'])->name('student.progress');
    Route::get('/student/notifications', 'viewStudentPushNotification')->middleware(['auth', 'role:student'])->name('student.notifications');
    Route::get('/student/help', 'viewStudentHelp')->middleware(['auth', 'role:student'])->name('student.help');
    Route::get('/student/push-notification', 'viewStudentPushNotification')->middleware(['auth', 'role:student'])->name('student.push-notification');
    Route::post('/student/push-notification/send', 'sendStudentPushNotification')->middleware(['auth', 'role:student'])->name('student.push-notification.send');
    Route::get('/student/push-notification/filter', 'filterStudentPushNotifications')->middleware(['auth', 'role:student'])->name('student.push-notification.filter');
    Route::get('/student/iot-management', 'viewStudentIotManagement')->middleware(['auth', 'role:student'])->name('student.iot-management');
    Route::post('/student/iot-management/register', 'registerStudentIotDevice')->middleware(['auth', 'role:student'])->name('student.iot-management.register');
    Route::get('/student/task-management', 'viewStudentTaskManagement')->middleware(['auth', 'role:student'])->name('student.task-management');
    Route::post('/student/task-management/create', 'createStudentTask')->middleware(['auth', 'role:student'])->name('student.task-management.create');
    Route::get('/student/task-management/filter', 'filterStudentTasks')->middleware(['auth', 'role:student'])->name('student.task-management.filter');
    Route::get('/student/exam-management', 'viewStudentExamManagement')->middleware(['auth', 'role:student'])->name('student.exam-management');
    Route::post('/student/exam-management/create', 'createStudentExam')->middleware(['auth', 'role:student'])->name('student.exam-management.create');
    Route::get('/student/exam-management/filter', 'filterStudentExams')->middleware(['auth', 'role:student'])->name('student.exam-management.filter');
    Route::get('/student/user-management', 'viewStudentUserManagement')->middleware(['auth', 'role:student'])->name('student.user-management');
    Route::post('/student/user-management/create', 'createStudentUser')->middleware(['auth', 'role:student'])->name('student.user-management.create');
    Route::get('/student/user-management/filter', 'filterStudentUsers')->middleware(['auth', 'role:student'])->name('student.user-management.filter');
    Route::get('/student/class-management', 'viewStudentClassManagement')->middleware(['auth', 'role:student'])->name('student.class-management');
    Route::post('/student/class-management/create', 'createStudentClass')->middleware(['auth', 'role:student'])->name('student.class-management.create');
    Route::get('/student/class-management/filter', 'filterStudentClasses')->middleware(['auth', 'role:student'])->name('student.class-management.filter');
    Route::get('/student/subject-management', 'viewStudentSubjectManagement')->middleware(['auth', 'role:student'])->name('student.subject-management');
    Route::post('/student/subject-management/create', 'createStudentSubject')->middleware(['auth', 'role:student'])->name('student.subject-management.create');
    Route::get('/student/subject-management/filter', 'filterStudentSubjects')->middleware(['auth', 'role:student'])->name('student.subject-management.filter');
    Route::get('/student/material-management', 'viewStudentMaterialManagement')->middleware(['auth', 'role:student'])->name('student.material-management');
    Route::post('/student/material-management/create', 'createStudentMaterial')->middleware(['auth', 'role:student'])->name('student.material-management.create');
    Route::get('/student/reports', 'viewStudentReports')->middleware(['auth', 'role:student'])->name('student.reports');
    Route::get('/student/help', 'viewStudentHelp')->middleware(['auth', 'role:student'])->name('student.help');
    
    // Admin Management Routes (Legacy)
    Route::get('/admin/kelas-old', 'viewAdminKelas')->middleware(['auth', 'role:admin'])->name('admin.kelas.old');
    Route::get('/admin/mapel-old', 'viewAdminMapel')->middleware(['auth', 'role:admin'])->name('admin.mapel.old');
    Route::get('/admin/pengajar-old', 'viewAdminPengajar')->middleware(['auth', 'role:admin'])->name('admin.pengajar.old');
    Route::get('/admin/siswa-old', 'viewAdminSiswa')->middleware(['auth', 'role:admin'])->name('admin.siswa.old');
    Route::get('/admin/notifications', 'viewAdminNotifications')->middleware(['auth', 'role:admin'])->name('admin.notifications');
});

// Admin Routes (New)
Route::controller(AdminController::class)->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard-new', 'dashboard')->name('admin.dashboard.new');
    
    // Pengajar Management
    Route::post('/admin/pengajar', 'storePengajar')->name('admin.pengajar.store');
    Route::put('/admin/pengajar/{id}', 'updatePengajar')->name('admin.pengajar.update');
    Route::delete('/admin/pengajar/{id}', 'deletePengajar')->name('admin.pengajar.delete');
    
    // Siswa Management
    Route::post('/admin/siswa', 'storeSiswa')->name('admin.siswa.store');
    Route::put('/admin/siswa/{id}', 'updateSiswa')->name('admin.siswa.update');
    Route::delete('/admin/siswa/{id}', 'deleteSiswa')->name('admin.siswa.delete');
    
    // Kelas Management
    Route::post('/admin/kelas', 'storeKelas')->name('admin.kelas.store');
    Route::put('/admin/kelas/{id}', 'updateKelas')->name('admin.kelas.update');
    Route::delete('/admin/kelas/{id}', 'deleteKelas')->name('admin.kelas.delete');
    
    // Mapel Management
    Route::post('/admin/mapel', 'storeMapel')->name('admin.mapel.store');
    Route::put('/admin/mapel/{id}', 'updateMapel')->name('admin.mapel.update');
    Route::delete('/admin/mapel/{id}', 'deleteMapel')->name('admin.mapel.delete');
    
    // Material Management
    Route::post('/admin/material', 'storeMaterial')->name('admin.material.store');
    Route::get('/admin/material-management/{id}', 'viewAdminMaterialDetail')->name('admin.material-management.show');
    Route::get('/admin/material-management/{id}/edit', 'editAdminMaterial')->name('admin.material-management.edit');
    Route::put('/admin/material/{id}', 'updateMaterial')->name('admin.material.update');
    Route::delete('/admin/material/{id}', 'deleteMaterial')->name('admin.material.delete');
    
    // User Management
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'userManagement'])->name('admin.users.index');
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

// Teacher Management Routes
Route::controller(DashboardController::class)->group(function () {
    Route::get('/teacher/materi', 'viewTeacherMateri')->middleware(['auth', 'role:teacher'])->name('teacher.materi');
    Route::get('/teacher/tugas', 'viewTeacherTugas')->middleware(['auth', 'role:teacher'])->name('teacher.tugas');
    Route::get('/teacher/ujian', 'viewTeacherUjian')->middleware(['auth', 'role:teacher'])->name('teacher.ujian');
    Route::get('/teacher/iot', 'viewTeacherIot')->middleware(['auth', 'role:teacher'])->name('teacher.iot');
});

// Teacher Material Management Routes
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/material-management', [DashboardController::class, 'viewTeacherMaterialManagement'])->name('teacher.material-management');
    Route::get('/teacher/material-management/create', [DashboardController::class, 'viewTeacherMaterialCreate'])->name('teacher.material-management.create');
    Route::post('/teacher/material-management/store', [DashboardController::class, 'createTeacherMaterial'])->name('teacher.material-management.store');
    Route::get('/teacher/material-management/filter', [DashboardController::class, 'filterTeacherMaterials'])->name('teacher.material-management.filter');
    Route::get('/teacher/material-management/{id}', [DashboardController::class, 'viewTeacherMaterialDetail'])->name('teacher.material-management.show');
    Route::get('/teacher/material-management/{id}/edit', [DashboardController::class, 'editTeacherMaterial'])->name('teacher.material-management.edit');
    Route::put('/teacher/material-management/{id}', [DashboardController::class, 'updateTeacherMaterial'])->name('teacher.material-management.update');
    Route::delete('/teacher/material-management/{id}', [DashboardController::class, 'deleteTeacherMaterial'])->name('teacher.material-management.delete');
});

// Teacher IoT Routes
Route::controller(App\Http\Controllers\IotClassController::class)->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/iot/dashboard', 'dashboard')->name('teacher.iot.dashboard');
    Route::get('/teacher/iot/class/{kelasId}', 'classData')->name('teacher.iot.class');
    Route::get('/teacher/iot/devices', 'devices')->name('teacher.iot.devices');
    Route::get('/teacher/iot/sensor-data', 'sensorData')->name('teacher.iot.sensor-data');
    Route::get('/teacher/iot/research-projects', 'researchProjects')->name('teacher.iot.research-projects');
    Route::post('/teacher/iot/create-project', 'createResearchProject')->name('teacher.iot.create-project');
    Route::get('/teacher/iot/realtime', 'getRealTimeData')->name('teacher.iot.realtime');
    Route::get('/teacher/iot/device-status', 'getDeviceStatus')->name('teacher.iot.device-status');
    Route::post('/teacher/iot/sensor-data', 'storeSensorData')->name('teacher.iot.store-sensor-data');
});
    
    // Teacher Task Management Routes
    Route::prefix('teacher/tasks')->middleware(['auth', 'role:teacher'])->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\TaskController::class, 'dashboard'])->name('teacher.tasks');
        Route::get('/filter', [App\Http\Controllers\Teacher\TaskController::class, 'filter'])->name('teacher.tasks.filter');
        Route::get('/list', [App\Http\Controllers\Teacher\TaskController::class, 'list'])->name('teacher.tasks.list');
        
        // Create routes for specific task types - specific routes MUST come before generic route
        Route::get('/create/multiple-choice', [App\Http\Controllers\Teacher\TaskController::class, 'createMultipleChoice'])->name('teacher.tasks.create.multiple-choice');
        Route::get('/create/essay', [App\Http\Controllers\Teacher\TaskController::class, 'createEssay'])->name('teacher.tasks.create.essay');
        Route::get('/create/individual', [App\Http\Controllers\Teacher\TaskController::class, 'createIndividual'])->name('teacher.tasks.create.individual');
        Route::get('/create/group', [App\Http\Controllers\Teacher\TaskController::class, 'createGroup'])->name('teacher.tasks.create.group');
        Route::get('/create/{tipe}', [App\Http\Controllers\Teacher\TaskController::class, 'create'])->name('teacher.tasks.create');
        
        // API routes for AJAX
        Route::get('/api/students-by-class/{kelasId}', [App\Http\Controllers\Teacher\TaskController::class, 'getStudentsByClass'])->name('teacher.api.students-by-class');
        Route::get('/multiple-choice/demo', function() {
            return view('teacher.multiple-choice-demo');
        })->name('teacher.multiple-choice.demo');
        
        Route::post('/', [App\Http\Controllers\Teacher\TaskController::class, 'store'])->name('teacher.tasks.store');
        Route::post('/multiple-choice', [App\Http\Controllers\Teacher\TaskController::class, 'storeMultipleChoice'])->name('teacher.tasks.store.multiple-choice');
        Route::get('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'show'])->name('teacher.tasks.show');
        Route::get('/{id}/detail', [App\Http\Controllers\Teacher\TaskController::class, 'showTaskDetail'])->name('teacher.tasks.detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Teacher\TaskController::class, 'edit'])->name('teacher.tasks.edit');
        Route::put('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'update'])->name('teacher.tasks.update');
        Route::delete('/{id}', [App\Http\Controllers\Teacher\TaskController::class, 'destroy'])->name('teacher.tasks.destroy');
        Route::post('/{id}/grade', [App\Http\Controllers\Teacher\TaskController::class, 'grade'])->name('teacher.tasks.grade');
        Route::get('/{id}/student-work/{studentId}', [App\Http\Controllers\Teacher\TaskController::class, 'getStudentWork'])->name('teacher.tasks.student-work');
        Route::get('/{id}/auto-score/{studentId}', [App\Http\Controllers\Teacher\TaskController::class, 'getAutoScore'])->name('teacher.tasks.auto-score');
        
        // Task management routes
        Route::get('/{taskId}/submission/{studentId}', [App\Http\Controllers\Teacher\TaskController::class, 'getSubmissionDetails'])->name('teacher.tasks.submission.details');
        Route::post('/{taskId}/grade/{studentId}', [App\Http\Controllers\Teacher\TaskController::class, 'saveGrade'])->name('teacher.tasks.grade.save');
    });

    // Teacher Exam Management Routes
    Route::prefix('teacher/exams')->middleware(['auth', 'role:teacher'])->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\ExamController::class, 'index'])->name('teacher.exam-management');
        Route::get('/filter', [App\Http\Controllers\Teacher\ExamController::class, 'filter'])->name('teacher.exam-management.filter');
        Route::post('/create', [App\Http\Controllers\Teacher\ExamController::class, 'create'])->name('teacher.exam-management.create');
        Route::get('/{id}', [App\Http\Controllers\Teacher\ExamController::class, 'show'])->name('teacher.exam-detail');
        Route::get('/{id}/edit', [App\Http\Controllers\Teacher\ExamController::class, 'edit'])->name('teacher.exam-edit');
        Route::put('/{id}', [App\Http\Controllers\Teacher\ExamController::class, 'update'])->name('teacher.exam-update');
        Route::delete('/{id}', [App\Http\Controllers\Teacher\ExamController::class, 'destroy'])->name('teacher.exam-destroy');
        Route::get('/{id}/results', [App\Http\Controllers\Teacher\ExamController::class, 'results'])->name('teacher.exam-results');
        
        // Create routes for specific exam types
        Route::get('/create/multiple-choice', function() {
            return redirect()->route('teacher.exam-create-multiple-choice');
        })->name('teacher.exam-create-multiple-choice');
        Route::get('/create/essay', function() {
            return redirect()->route('teacher.exam-create-essay');
        })->name('teacher.exam-create-essay');
        Route::get('/create/mixed', function() {
            return redirect()->route('teacher.exam-create-mixed');
        })->name('teacher.exam-create-mixed');
    });
    
    // Student Routes
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/student/home', 'viewStudentHome')->middleware(['auth', 'role:student'])->name('student.home');
        Route::get('/student/ujian', 'viewStudentUjian')->middleware(['auth', 'role:student'])->name('student.ujian');
        Route::get('/student/iot', 'viewStudentIot')->middleware(['auth', 'role:student'])->name('student.iot');
    });

// Student Controller Routes
Route::controller(App\Http\Controllers\StudentController::class)->group(function () {
    Route::middleware(['auth', 'role:student'])->group(function () {
        // Dashboard moved to DashboardController
        
        // Tugas
        Route::get('/student/tugas', 'tugas')->name('student.tugas');
        Route::get('/student/tugas/{id}/kerjakan', 'kerjakanTugas')->name('student.kerjakan-tugas');
        Route::post('/student/tugas/{id}/submit', 'submitTugas')->name('student.submit-tugas');
        
        // Ujian
        Route::get('/student/ujian', 'ujian')->name('student.ujian');
        Route::get('/student/ujian/{id}/kerjakan', 'kerjakanUjian')->name('student.kerjakan-ujian');
        Route::post('/student/ujian/{id}/submit', 'submitUjian')->name('student.submit-ujian');
        
        // Materi
        Route::get('/student/materi', 'materi')->name('student.materi');
        Route::get('/student/materi/{id}', 'materiDetail')->name('student.materi.detail');
        
        // IoT
        Route::get('/student/iot', 'iot')->name('student.iot');
        
        // Profile
        Route::get('/student/profile', 'profile')->name('student.profile');
        Route::post('/student/profile/update', 'updateProfile')->name('student.update-profile');
        Route::post('/student/profile/photo', 'updatePhoto')->name('student.update-photo');
        Route::post('/student/profile/password', 'updatePassword')->name('student.update-password');
        
        // Editor image upload - removed duplicate
    });
});

// Demo Routes
Route::get('/demo/sidebar', function () {
    return view('demo.sidebar-demo');
})->name('demo.sidebar');

Route::get('/demo/modal', function () {
    return view('demo.modal-demo');
})->name('demo.modal');

// Space Dashboard Demo
Route::get('/space-demo', function () {
    return view('dashboard.space-demo');
})->middleware('auth')->name('space.demo');

// KelasMapel
Route::controller(KelasMapelController::class)->group(function () {
    // Get
    Route::get('/kelas-mapel/{mapel}/{token}', 'viewKelasMapel')->middleware('auth')->name('viewKelasMapel');
    Route::get('/save-image-temp', 'saveImageTemp')->middleware('auth')->name('saveImageTemp');

    Route::get('/export-nilai-tugas', 'exportNilaiTugas')->middleware(['auth', 'role:teacher'])->name('exportNilaiTugas');
    Route::get('/export-nilai-ujian', 'exportNilaiUjian')->middleware(['auth', 'role:teacher'])->name('exportNilaiUjian');
});

// Ujian
Route::controller(UjianController::class)->group(function () {
    // Get
    Route::get('/ujian', 'index')->middleware(['auth', 'role:teacher'])->name('ujian.index');
    Route::get('/ujian/add/1/{token}', 'viewPilihTipeUjian')->middleware('auth')->name('viewPilihTipeUjian');
    Route::get('/ujian/add/2/{token}', 'viewCreateUjian')->middleware('auth')->name('viewCreateUjian');
    Route::get('/ujian/{token}', 'viewUjian')->middleware('auth')->name('viewUjian');
    Route::get('/ujian/update/{token}', 'viewUpdateUjian')->middleware('auth')->name('viewUpdateUjian');

    Route::post('/store-ujian', 'createUjian')->middleware('auth')->name('createUjian');
    Route::post('/ujian/update-nilai', 'ujianUpdateNilai')->middleware(['auth', 'role:teacher'])->name('ujianUpdateNilai');
    Route::post('/update-ujian', 'updateUjian')->middleware(['auth', 'role:teacher'])->name('updateUjian');
    Route::post('/destroy-ujian', 'destroyUjian')->middleware(['auth', 'role:teacher'])->name('destroyUjian');

    Route::post('/import-soal-ujian', 'import')->middleware(['auth', 'role:teacher'])->name('importSoalUjian');

    // Import Export
    Route::get('/contoh-essay', 'contohEssay')->middleware(['auth', 'role:teacher'])->name('contohEssay');
    Route::get('/contoh-multiple', 'contohMultiple')->middleware(['auth', 'role:teacher'])->name('contohMultiple');

    // Siswa
    Route::get('/ujian-access/{token}', 'ujianAccess')->middleware('auth')->name('ujianAccess');
    Route::post('/start-ujian/{token}', 'startUjian')->middleware('auth')->name('startUjian');
    Route::get('/ujian/{ujian}/{token}', 'userUjian')->middleware('auth')->name('userUjian');

    Route::post('/simpan-jawaban', 'simpanJawaban')->middleware('auth')->name('simpanJawaban');
    Route::post('/simpan-jawaban-multiple', 'simpanJawabanMultiple')->middleware('auth')->name('simpanJawabanMultiple');
    Route::post('/selesai-ujian', 'selesaiUjian')->middleware('auth')->name('selesaiUjian');
    Route::post('/selesai-ujian-multiple', 'selesaiUjianMultiple')->middleware('auth')->name('selesaiUjianMultiple');
    Route::get('/get-jawaban', 'getJawaban')->middleware('auth')->name('getJawaban');
    Route::get('/get-jawaban-multiple', 'getJawabanMultiple')->middleware('auth')->name('getJawabanMultiple');
});

// Enhanced Exam Management
    Route::prefix('teacher/enhanced-exam-management')->name('teacher.enhanced-exam-management.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'create'])->name('create');
    Route::get('/{id}', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'show'])->name('show');
    Route::get('/{id}/progress', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'progress'])->name('progress');
    Route::get('/{id}/results', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'results'])->name('results');
    Route::get('/{ujianId}/student-progress/{userId}', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'studentProgress'])->name('student-progress');
    Route::post('/{ujianId}/feedback/{userId}', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'giveFeedback'])->name('give-feedback');
    Route::put('/{id}/status', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/export', [App\Http\Controllers\Teacher\EnhancedExamController::class, 'exportResults'])->name('export');
});

// Group Management Routes
Route::prefix('teacher/groups')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/', [GroupManagementController::class, 'index'])->name('teacher.groups.index');
    Route::get('/create/{kelas_id}', [GroupManagementController::class, 'create'])->name('teacher.groups.create');
    Route::post('/store', [GroupManagementController::class, 'store'])->name('teacher.groups.store');
    Route::get('/{id}/edit', [GroupManagementController::class, 'edit'])->name('teacher.groups.edit');
    Route::put('/{id}', [GroupManagementController::class, 'update'])->name('teacher.groups.update');
    Route::delete('/{id}', [GroupManagementController::class, 'destroy'])->name('teacher.groups.destroy');
    Route::get('/get-students/{kelas_id}', [GroupManagementController::class, 'getStudents'])->name('teacher.groups.get-students');
    Route::get('/get-groups/{kelas_id}', [GroupManagementController::class, 'getGroups'])->name('teacher.groups.get-groups');
});

// Group Management Routes - Accessible by Superadmin, Admin, and Teacher
Route::prefix('groups')->middleware(['auth'])->group(function () {
    Route::get('/get-students/{kelas_id}', [GroupManagementController::class, 'getStudents'])->name('groups.get-students');
    Route::get('/get-groups/{kelas_id}', [GroupManagementController::class, 'getGroups'])->name('groups.get-groups');
});

// Materi
Route::controller(MateriController::class)->group(function () {
    // Get
    Route::get('/materi/add/{token}', 'viewCreateMateri')->middleware(['auth', 'role:teacher'])->name('viewCreateMateri');
    Route::get('/materi/update/{token}', 'viewUpdateMateri')->middleware(['auth', 'role:teacher'])->name('viewUpdateMateri');
    Route::post('/store-materi', 'createMateri')->middleware(['auth', 'role:teacher'])->name('createMateri');
    Route::post('/update-materi', 'updateMateri')->middleware(['auth', 'role:teacher'])->name('updateMateri');
    Route::post('/destroy-materi', 'destroyMateri')->middleware(['auth', 'role:teacher'])->name('destroyMateri');

    Route::get('/materi', 'viewMateri')->middleware(['auth', 'role:teacher'])->name('materi.index');

    Route::post('/upload-materi-file', 'uploadFileMateri')->middleware('auth')->name('uploadFileMateri');
    Route::post('/destroy-materi-file', 'destroyFileMateri')->middleware('auth')->name('destroyFileMateri');
    Route::get('/redirect-after', 'redirectBack')->middleware('auth')->name('redirectBack');
});

// Tugas
Route::controller(TugasController::class)->group(function () {

    // Baru V1.1 - Quiz, Tugas Kelompok Assesment, Pilihan ganda
    Route::get('/tugas/menu/{token}', 'viewMenuTugas')->middleware(['auth', 'role:teacher'])->name('viewMenuTugas');
    Route::get('/tugas/settingKelompok', 'settingKelompok')->middleware(['auth', 'role:teacher'])->name('settingKelompok');

    Route::post('/tugas/submitKelompok', 'submitFileKelompok')->middleware('auth')->name('submitFileKelompok');
    Route::post('/tugas/tambahKelompok', 'tambahKelompok')->middleware(['auth', 'role:teacher'])->name('tambahKelompok');
    Route::post('/tugas/deleteKelompok', 'deleteKelompok')->middleware(['auth', 'role:teacher'])->name('deleteKelompok');
    Route::get('/tugas/tambahAnggota', 'tambahAnggota')->middleware(['auth', 'role:teacher'])->name('tambah-anggota');
    Route::get('/tugas/deleteAnggota', 'deleteAnggota')->middleware(['auth', 'role:teacher'])->name('delete-anggota');
    Route::post('/tugas/submitNilaiKelompok', 'submitNilaiKelompok')->middleware('auth')->name('submitNilaiKelompok');
    Route::post('/tugas/submitTugasMultiple', 'submitTugasMultiple')->middleware('auth')->name('submitTugasMultiple');
    Route::post('/tugas/submitTugasKelompokQuiz', 'submitTugasKelompokQuiz')->middleware('auth')->name('submitTugasKelompokQuiz');

    // Quiz
    Route::post('/tugas/submit-quiz/{token}', 'submitQuiz')->middleware('auth')->name('submit-quiz');
    Route::post('/tugas/nilai-quiz', 'tugasNilaiQuiz')->middleware('auth')->name('tugasNilaiQuiz');
    Route::post('/tugas/delete-file-kelompok', 'deleteKelompokFile')->middleware('auth')->name('deleteKelompokFile');

    // Get
    Route::get('/tugas/add/{token}', 'viewCreateTugas')->middleware(['auth', 'role:teacher'])->name('viewCreateTugas');
    Route::get('/tugas', [App\Http\Controllers\Teacher\TaskController::class, 'list'])->middleware(['auth', 'role:teacher'])->name('tugas.index');
    Route::get('/tugas/update/{token}', 'viewUpdateTugas')->middleware(['auth', 'role:teacher'])->name('viewUpdateTugas');

    Route::post('/tugas/update-nilai/{token}', 'siswaUpdateNilai')->middleware(['auth', 'role:teacher'])->name('siswaUpdateNilai');
    Route::post('/destroy-tugas', 'destroyTugas')->middleware(['auth', 'role:teacher'])->name('destroyTugas');
    Route::post('/store-tugas', 'createTugas')->middleware(['auth', 'role:teacher'])->name('createTugas');
    Route::post('/update-tugas', 'updateTugas')->middleware(['auth', 'role:teacher'])->name('updateTugas');
    Route::post('/destroy-tugas-file', 'destroyFileTugas')->middleware(['auth', 'role:teacher'])->name('destroyFileTugas');
    Route::post('/destroy-tugas-submit-file', 'destroyFileSubmit')->middleware('auth')->name('destroyFileSubmit');

    Route::post('/upload-tugas-file', 'uploadFileTugas')->middleware('auth')->name('uploadFileTugas');
    Route::post('/submit-tugas-file', 'submitFileTugas')->middleware('auth')->name('submitFileTugas');

    Route::post('/submit-tugas/{token}', 'submitTugas')->middleware('auth')->name('submitTugas');
});

//Admin Only
Route::controller(PengajarController::class)->group(function () {
    // Get
    Route::get('/data-pengajar', 'viewPengajar')->middleware(['auth', 'role:admin'])->name('viewPengajar');
    Route::get('/data-pengajar/new-pengajar-1', 'viewNewPengajar')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('viewTambahPengajar');
    Route::get('/data-pengajar/new-pengajar-2', 'tambahKelasPengajar')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('tambahKelasPengajar');
    Route::get('/data-pengajar/debug', 'debugRoute')->middleware('auth')->name('debugRoute');
    Route::get('/data-pengajar/success', 'dataPengajarSuccess')->middleware(['auth', 'role:admin'])->name('dataPengajarSuccess');

    Route::get('/data-pengajar/update/{token}', 'viewUpdatePengajar')->middleware(['auth', 'role:admin'])->name('viewUpdatePengajar');

    Route::post('/validate-pengajar', 'validateDataPengajar')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('validateDataPengajar');
    Route::post('/validate-pengajar-2', 'validateDataPengajarKelas')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('validateDataPengajarKelas');
    Route::post('/update-pengajar', 'updatePengajar')->middleware(['auth', 'role:admin'])->name('updatePengajar');
    Route::post('/destroy-pengajar', 'destroyPengajar')->middleware(['auth', 'role:admin'])->name('destroyPengajar');
    Route::post('/catch', 'catch')->middleware(['auth', 'role:admin'])->name('catch');

    Route::get('/export-pengajar', 'export')->middleware(['auth', 'role:admin'])->name('exportPengajar');
    Route::get('/contoh-pengajar', 'fileContoh')->middleware(['auth', 'role:admin'])->name('contohPengajar');
    Route::post('/import-pengajar', 'import')->middleware(['auth', 'role:admin'])->name('importPengajar');

    // API routes
    Route::get('/search-pengajar', 'searchPengajar')->middleware(['auth', 'role:admin'])->name('searchPengajar');
});

// All Roles
Route::controller(ProfileController::class)->group(function () {
    // Get
    Route::get('/data-pengajar/profile/{token}', 'viewProfilePengajar')->middleware(['auth', 'role:admin'])->name('viewProfileAdmin');
    Route::get('/profile-pengajar/{token}', 'viewProfilePengajar')->middleware('auth')->name('viewProfilePengajar');
    Route::get('/profile/{token}', 'viewProfileSiswa')->middleware('auth')->name('viewProfileSiswa');
    Route::get('/user-setting/{token}', 'viewProfileSetting')->middleware('auth')->name('viewProfileSetting');
    
    // Simple profile and settings routes
    Route::get('/profile', 'viewProfile')->middleware('auth')->name('profile');
    Route::get('/settings', 'viewSettings')->middleware('auth')->name('settings');

    // Profile photo upload routes
    Route::post('/profile/upload-photo', 'uploadProfilePhoto')->middleware('auth')->name('profile.upload-photo');
    Route::delete('/profile/delete-photo', 'deleteProfilePhoto')->middleware('auth')->name('profile.delete-photo');

    Route::post('/crop-photo-user', 'cropImageUser')->middleware('auth')->name('cropImageUser');
});

// Kelas
Route::controller(KelasController::class)->group(function () {
    Route::get('/data-kelas', 'viewKelas')->middleware(['auth', 'role:admin'])->name('viewKelas');
    Route::get('/data-kelas/tambah-kelas', 'viewTambahKelas')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('viewTambahKelas');
    Route::get('/data-kelas/success', 'dataKelasSuccess')->middleware(['auth', 'role:admin'])->name('dataKelasSuccess');
    Route::get('/data-kelas/update-kelas/{kelas:id}', 'viewUpdateKelas')->middleware(['auth', 'role:admin'])->name('viewUpdateKelas');
    Route::get('/data-kelas/get-kelas', 'viewDetailKelas')->middleware(['auth', 'role:admin'])->name('viewDetailKelas');

    Route::post('/validate-kelas', 'validateNamaKelas')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('validateNamaKelas');
    Route::post('/destroy-kelas', 'destroyKelas')->middleware(['auth', 'role:admin'])->name('destroyKelas');
    Route::post('/update-kelas', 'updateKelas')->middleware(['auth', 'role:admin'])->name('updateKelas');

    Route::get('/export-Kelas', 'export')->middleware(['auth', 'role:admin'])->name('exportKelas');
    Route::get('/contoh-Kelas', 'contohKelas')->middleware(['auth', 'role:admin'])->name('contohKelas');
    Route::post('/import-Kelas', 'import')->middleware(['auth', 'role:admin'])->name('importKelas');

    // API routes
    Route::get('/search-kelas', 'searchKelas')->middleware(['auth', 'role:admin'])->name('searchKelas');
});

// All Mapel
Route::controller(MapelController::class)->group(function () {
    // Get
    Route::get('/data-mapel', 'viewMapel')->middleware(['auth', 'role:admin'])->name('viewMapel');
    Route::get('/data-mapel/tambah-mapel', 'viewTambahMapel')->middleware(['auth', 'role:admin'])->name('viewTambahMapel');
    Route::get('/data-mapel/update-mapel/{mapel:id}', 'viewUpdateMapel')->middleware(['auth', 'role:admin'])->name('viewUpdateMapel');
    Route::get('/data-mapel/success', 'dataMapelSuccess')->middleware(['auth', 'role:admin'])->name('dataMapelSuccess');
    Route::get('/cek-kelas-mapel', 'cekKelasMapel')->middleware(['auth', 'role:admin'])->name('cekKelasMapel');

    Route::post('/validate-mapel', 'validateNamaMapel')->middleware(['auth', 'role:admin'])->name('validateNamaMapel');
    Route::post('/add-change-access', 'addChangeEditorAccess')->middleware(['auth', 'role:admin'])->name('addChangeEditorAccess');
    Route::post('/add-editor-access', 'tambahEditorAccess')->middleware(['auth', 'role:admin'])->name('tambahEditorAccess');
    Route::post('/delete-editor-access', 'deleteEditorAccess')->middleware(['auth', 'role:admin'])->name('deleteEditorAccess');
    Route::post('/update-mapel', 'updateMapel')->middleware(['auth', 'role:admin'])->name('updateMapel');
    Route::post('/destroy-mapel', 'destroyMapel')->middleware(['auth', 'role:admin'])->name('destroyMapel');
    Route::post('/mapel-crop-image', 'mapelTambahGambar')->middleware(['auth', 'role:admin'])->name('mapelTambahGambar');

    Route::get('/export-mapel', 'export')->middleware(['auth', 'role:admin'])->name('exportMapel');
    Route::get('/contoh-mapel', 'contohMapel')->middleware(['auth', 'role:admin'])->name('contohMapel');
    Route::post('/import-mapel', 'import')->middleware(['auth', 'role:admin'])->name('importMapel');

    // API routes
    Route::get('/search-mapel', 'searchMapel')->middleware(['auth', 'role:admin'])->name('searchMapel');
    Route::get('/search-mapel-from-kelas', 'searchKelasMapel')->middleware(['auth', 'role:admin'])->name('searchKelasMapel');
    
    // Teacher API routes
    Route::get('/api/kelas/{kelasId}/mapel', 'searchKelasMapel')->middleware(['auth', 'role:teacher'])->name('api.kelas.mapel');
});

// DataSiswa
Route::controller(DataSiswaController::class)->group(function () {
    Route::get('/data-siswa', 'viewSiswa')->middleware(['auth', 'role:admin'])->name('viewSiswa');
    Route::get('/data-siswa/tambah-siswa', 'viewTambahSiswa')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('viewTambahSiswa');
    Route::get('/data-siswa/update-siswa/{data_siswa:id}', 'viewUpdateDataSiswa')->middleware(['auth', 'role:admin'])->name('viewUpdateDataSiswa');
    Route::get('/data-siswa/success', 'dataSiswaSuccess')->middleware(['auth', 'role:admin'])->name('dataSiswaSuccess');
    Route::get('/data-siswa/update/{token}', 'viewUpdateUserSiswa')->middleware(['auth', 'role:admin'])->name('viewUpdateUserSiswa');

    Route::post('/update-user-siswa', 'updateUserSiswa')->middleware('auth')->name('updateUserSiswa');
    Route::post('/validate-data-siswa', 'validateDataSiswa')->middleware(['auth', 'role:admin', 'restrict.guru.create'])->name('validateDataSiswa');
    Route::post('/destroy-siswa', 'destroyDataSiswa')->middleware(['auth', 'role:admin'])->name('destroyDataSiswa');
    Route::post('/update-siswa', 'updateDataSiswa')->middleware(['auth', 'role:admin'])->name('updateSiswa');

    Route::get('/export-siswa', 'export')->middleware(['auth', 'role:admin'])->name('exportSiswa');
    Route::get('/contoh-siswa', 'contohSiswa')->middleware(['auth', 'role:admin'])->name('contohSiswa');

    Route::post('/import-siswa', 'import')->middleware(['auth', 'role:admin'])->name('importSiswa');

    // API routes
    Route::get('/search-siswa', 'searchSiswa')->middleware(['auth', 'role:admin'])->name('searchSiswa');
    Route::get('/search-siswa-kelas', 'viewSiswaKelas')->middleware('auth')->name('viewSiswaKelas');
});

// File
Route::controller(FileController::class)->group(function () {
    Route::get('/getFile/{namaFile}', 'getFile')->middleware('auth')->name('getFile');
    Route::get('/getFileTugas/{namaFile}', 'getFileTugas')->middleware('auth')->name('getFileTugas');
    Route::get('/getFileUser/{namaFile}', 'getFileUser')->middleware('auth')->name('getFileUser');
});

// IoT Routes
Route::prefix('iot')->middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\IotController::class, 'index'])->name('iot.dashboard');
    Route::get('/devices', [App\Http\Controllers\IotController::class, 'devices'])->name('iot.devices');
    Route::get('/sensor-data', [App\Http\Controllers\IotController::class, 'sensorData'])->name('iot.sensor-data');
    Route::get('/research-projects', [App\Http\Controllers\IotController::class, 'researchProjects'])->name('iot.research-projects');
    Route::get('/research-project/{projectId}/data', [App\Http\Controllers\IotController::class, 'getResearchProjectData'])->name('iot.research-project-data');
    
    // IoT Tugas Routes
    Route::get('/tugas', [App\Http\Controllers\IotTugasController::class, 'index'])->name('iot.tugas');
    Route::get('/hasil-saya', [App\Http\Controllers\IotTugasController::class, 'hasilSaya'])->name('iot.hasil-saya');
});

// IoT API Routes
Route::prefix('api/iot')->middleware('auth')->group(function () {
    Route::post('/sensor-data', [App\Http\Controllers\IotController::class, 'storeSensorData'])->name('api.iot.store-sensor-data');
    Route::get('/real-time-data', [App\Http\Controllers\IotController::class, 'getRealTimeData'])->name('api.iot.real-time-data');
    Route::get('/device-status', [App\Http\Controllers\IotController::class, 'getDeviceStatus'])->name('api.iot.device-status');
    Route::post('/research-project', [App\Http\Controllers\IotController::class, 'storeResearchProject'])->name('api.iot.store-research-project');
    
    // IoT Tugas API Routes
    Route::post('/readings', [App\Http\Controllers\IotTugasController::class, 'store'])->name('api.iot.store-reading');
    Route::get('/readings/class/{classId}', [App\Http\Controllers\IotTugasController::class, 'getClassReadings'])->name('api.iot.class-readings');
    Route::get('/readings/student/{studentId}', [App\Http\Controllers\IotTugasController::class, 'getStudentReadings'])->name('api.iot.student-readings');
    Route::get('/readings/export', [App\Http\Controllers\IotTugasController::class, 'exportCsv'])->name('api.iot.export-readings');
    Route::get('/readings/realtime', [App\Http\Controllers\IotTugasController::class, 'getRealTimeData'])->name('api.iot.readings-realtime');
    Route::get('/readings/statistics', [App\Http\Controllers\IotTugasController::class, 'getStatistics'])->name('api.iot.readings-statistics');
});

// Teacher Assignment Routes
Route::get('/admin/teacher-assignments', [TeacherAssignmentController::class, 'index'])->middleware(['auth', 'role:admin'])->name('admin.teacher-assignments');
Route::prefix('admin/teacher-assignments')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [TeacherAssignmentController::class, 'index'])->name('admin.teacher-assignments.index');
    Route::get('/dashboard', [TeacherAssignmentController::class, 'dashboard'])->name('admin.teacher-assignments.dashboard');
    Route::post('/assign', [TeacherAssignmentController::class, 'assignTeacher'])->name('admin.assign-teacher');
    Route::put('/{id}', [TeacherAssignmentController::class, 'updateAssignment'])->name('admin.teacher-assignments.update');
    Route::delete('/{id}', [TeacherAssignmentController::class, 'deleteAssignment'])->name('admin.teacher-assignments.delete');
    Route::post('/bulk-assign', [TeacherAssignmentController::class, 'bulkAssignTeachers'])->name('admin.teacher-assignments.bulk-assign');
    Route::delete('/bulk-delete', [TeacherAssignmentController::class, 'bulkDeleteAssignments'])->name('admin.teacher-assignments.bulk-delete');
    Route::get('/export', [TeacherAssignmentController::class, 'exportAssignments'])->name('admin.teacher-assignments.export');
    Route::post('/import', [TeacherAssignmentController::class, 'importAssignments'])->name('admin.teacher-assignments.import');
    Route::get('/statistics', [TeacherAssignmentController::class, 'getAssignmentStatistics'])->name('admin.teacher-assignments.statistics');
});

// Notification Routes
Route::controller(NotificationController::class)->group(function () {
    // Admin routes
    Route::prefix('admin/notifications')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', 'index')->name('admin.notifications.index');
        Route::get('/create', 'create')->name('admin.notifications.create');
        Route::post('/', 'store')->name('admin.notifications.store');
        Route::get('/{id}', 'show')->name('admin.notifications.show');
        Route::delete('/{id}', 'destroy')->name('admin.notifications.destroy');
        Route::delete('/', 'destroyAll')->name('admin.notifications.destroyAll');
    });
    
    // User routes
    Route::middleware('auth')->group(function () {
        Route::get('/notifications', 'index')->name('notifications.index');
        Route::post('/notifications/{id}/mark-read', 'markAsRead')->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read');
        Route::get('/api/notifications/unread-count', 'getUnreadCount')->name('api.notifications.unread-count');
        Route::get('/api/notifications/latest', 'getLatest')->name('api.notifications.latest');
        Route::delete('/notifications/{id}', 'destroy')->name('notifications.destroy');
        Route::delete('/notifications', 'clearAll')->name('notifications.clear-all');
    });
});

// Group Task Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('group-tasks', GroupTaskController::class);
    Route::post('group-tasks/{groupTask}/join', [GroupTaskController::class, 'join'])->name('group-tasks.join');
    Route::delete('group-tasks/{groupTask}/leave', [GroupTaskController::class, 'leave'])->name('group-tasks.leave');
    Route::patch('group-tasks/{groupTask}/select-leader', [GroupTaskController::class, 'selectLeader'])->name('group-tasks.select-leader');
    Route::get('group-tasks/{groupTask}/evaluation', [GroupTaskController::class, 'evaluationForm'])->name('group-tasks.evaluation');
    Route::post('group-tasks/{groupTask}/evaluation', [GroupTaskController::class, 'submitEvaluation'])->name('group-tasks.submit-evaluation');
    Route::get('group-tasks/{groupTask}/results', [GroupTaskController::class, 'results'])->name('group-tasks.results');
    
    // Material Routes
    Route::resource('materials', MaterialController::class);
    Route::post('materials/{material}/publish', [MaterialController::class, 'publish'])->name('materials.publish');
    Route::post('materials/{material}/unpublish', [MaterialController::class, 'unpublish'])->name('materials.unpublish');
});

// Rubrik Routes
Route::controller(RubrikController::class)->middleware(['auth'])->group(function () {
    Route::get('/rubrik/{tugasId}', 'show')->name('rubrik.show');
    Route::post('/rubrik/store', 'store')->name('rubrik.store');
    Route::put('/rubrik/{id}', 'update')->name('rubrik.update');
    Route::delete('/rubrik/{id}', 'destroy')->name('rubrik.destroy');
    Route::get('/api/rubrik/{tugasId}', 'getRubrik')->name('api.rubrik.get');
});

// API Routes for Grading System
Route::prefix('api')->middleware(['auth'])->group(function () {
    // Rubrik API
    Route::get('/rubrik/{tugasId}', [RubrikController::class, 'getRubrik']);
    Route::post('/rubrik', [RubrikController::class, 'store']);
    Route::put('/rubrik/{id}', [RubrikController::class, 'update']);
    Route::delete('/rubrik/{id}', [RubrikController::class, 'destroy']);
    
    // Grading API
    Route::post('/grading/submit', [TugasController::class, 'apiSubmitGrading']);
    Route::get('/grading/{tugasId}/students', [TugasController::class, 'apiGetStudents']);
    Route::get('/grading/{tugasId}/stats', [TugasController::class, 'apiGetGradingStats']);
    
    // History API
    Route::get('/nilai/history/{userTugasId}', [TugasController::class, 'apiGetHistory']);
    
    // Analytics API
    Route::get('/analytics/grading', [AnalyticsController::class, 'apiGradingAnalytics']);
    Route::get('/analytics/teacher/{teacherId}', [AnalyticsController::class, 'apiTeacherAnalytics']);
    Route::get('/analytics/student/{studentId}', [AnalyticsController::class, 'apiStudentAnalytics']);
    
    // Export/Import API
    Route::get('/export/nilai/{tugasId}', [TugasController::class, 'apiExportNilai']);
    Route::post('/import/nilai', [TugasController::class, 'apiImportNilai']);
    
    // Report API
    Route::get('/report/transkrip/{studentId}', [TugasController::class, 'apiGenerateTranskrip']);
    Route::get('/report/class/{kelasId}', [TugasController::class, 'apiGenerateClassReport']);
});

// History Routes
Route::controller(TugasController::class)->middleware(['auth'])->group(function () {
    Route::get('/nilai/history/{userTugasId}', 'showHistory')->name('nilai.history');
});

// Analytics Routes
Route::controller(AnalyticsController::class)->middleware(['auth'])->group(function () {
    Route::get('/analytics/grading', 'gradingAnalytics')->name('analytics.grading');
    Route::get('/api/analytics/data', 'getAnalyticsData')->name('api.analytics.data');
    Route::get('/analytics/export', 'exportAnalytics')->name('analytics.export');
});

// Export/Import Routes
Route::middleware(['auth'])->group(function () {
    // Route::get('/nilai/export/{tugasId?}', [App\Http\Controllers\ExportController::class, 'exportNilai'])->name('nilai.export');
    // Route::post('/nilai/import', [App\Http\Controllers\ImportController::class, 'importNilai'])->name('nilai.import');
    Route::get('/transkrip/{userId}', [App\Services\ReportService::class, 'generateTranskrip'])->name('transkrip.generate');
    Route::get('/class-report/{kelasId}', [App\Services\ReportService::class, 'generateClassReport'])->name('class.report');
    Route::get('/teacher-report/{teacherId}', [App\Services\ReportService::class, 'generateTeacherReport'])->name('teacher.report');
});

// ThingsBoard NPK Sensor Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard sensor NPK
    Route::get('/sensor/dashboard', [NpkSensorController::class, 'showDashboard'])->name('sensor.dashboard');
    
    // API endpoints untuk AJAX calls
    Route::get('/api/sensor/data', [NpkSensorController::class, 'getSensorData'])->name('api.sensor.data');
    Route::get('/api/sensor/refresh', [NpkSensorController::class, 'refreshData'])->name('api.sensor.refresh');
    Route::get('/api/sensor/test-connection', [NpkSensorController::class, 'testConnection'])->name('api.sensor.test-connection');
    Route::get('/api/sensor/device-info', [NpkSensorController::class, 'getDeviceInfo'])->name('api.sensor.device-info');
});

// Public NPK Sensor Test Routes (no authentication required)
Route::get('/sensor/test', [NpkSensorController::class, 'showPublicTest'])->name('sensor.test');
Route::get('/api/sensor/public-data', [NpkSensorController::class, 'getPublicSensorData'])->name('api.sensor.public-data');

// Public ESP8266 Status Routes (no authentication required)
Route::get('/iot/esp8266-status', function () {
    return view('iot.esp8266-status');
})->name('iot.esp8266-status.public');

// Public WiFi Config Routes (no authentication required)
Route::get('/iot/wifi-config', [App\Http\Controllers\WifiConfigController::class, 'index'])->name('iot.wifi-config.public');
Route::post('/iot/wifi-config/detect-ports', [App\Http\Controllers\WifiConfigController::class, 'detectPorts'])->name('iot.wifi-config.detect-ports');

// Public Device Dashboard Routes (no authentication required)
Route::get('/iot/device/{deviceId}', [App\Http\Controllers\IotDeviceController::class, 'showDeviceDashboard'])
    ->name('iot.device.dashboard');


// Auto WiFi Sync routes removed - using manual WiFi config only

// ESP8266 Status Routes for All Roles
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/esp8266-status', [App\Http\Controllers\DashboardController::class, 'viewSuperAdminEsp8266Status'])
        ->name('superadmin.esp8266-status');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/esp8266-status', [App\Http\Controllers\DashboardController::class, 'viewAdminEsp8266Status'])
        ->name('admin.esp8266-status');
});

Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/esp8266-status', [App\Http\Controllers\DashboardController::class, 'viewTeacherEsp8266Status'])
        ->name('teacher.esp8266-status');
});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/esp8266-status', [App\Http\Controllers\DashboardController::class, 'viewStudentEsp8266Status'])
        ->name('student.esp8266-status');
    
        // Student Complaint Routes
        Route::resource('student/complaints', ComplaintController::class)->names([
            'index' => 'student.complaints.index',
            'create' => 'student.complaints.create',
            'store' => 'student.complaints.store',
            'show' => 'student.complaints.show',
            'edit' => 'student.complaints.edit',
            'update' => 'student.complaints.update',
            'destroy' => 'student.complaints.destroy',
        ]);
        
        // Student Attachment Routes
        Route::delete('/student/complaints/{complaint}/attachments/{attachment}', [ComplaintController::class, 'deleteAttachment'])->name('student.complaints.attachments.delete');
        Route::get('/student/complaints/attachments/{attachment}/download', [ComplaintController::class, 'downloadAttachment'])->name('student.complaints.attachments.download');
});

// IoT Device Status API Routes (no authentication required for ESP8266)
Route::prefix('api/iot')->group(function () {
    Route::post('/device-status', [App\Http\Controllers\IotDeviceStatusController::class, 'updateStatus'])
        ->name('api.iot.device-status.update');
    Route::get('/device-status/{deviceId}', [App\Http\Controllers\IotDeviceStatusController::class, 'getStatus'])
        ->name('api.iot.device-status.get');
    Route::get('/devices-status', [App\Http\Controllers\IotDeviceStatusController::class, 'getAllDevicesStatus'])
        ->name('api.iot.devices-status.all');
});

// ESP8266 Device Status Route (bypass CSRF for IoT devices)
Route::post('/esp8266/device-status', [App\Http\Controllers\Esp8266Controller::class, 'updateDeviceStatus'])
    ->withoutMiddleware([\App\Http\Middleware\HandleCsrfError::class])
    ->name('esp8266.device-status.update');

// ESP8266 Sensor Data Routes (bypass CSRF for IoT devices)
Route::post('/esp8266/sensor-data', [App\Http\Controllers\SensorDataController::class, 'store'])
    ->name('esp8266.sensor-data.store')
    ->withoutMiddleware([\App\Http\Middleware\HandleCsrfError::class]);
Route::get('/esp8266/sensor-data/export', [App\Http\Controllers\SensorDataController::class, 'export'])
    ->name('esp8266.sensor-data.export');
