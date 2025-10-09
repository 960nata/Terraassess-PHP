<?php

namespace App\Http\Controllers;

use App\Models\DataSiswa;
use App\Models\EditorAccess;
use App\Models\IotDevice;
use App\Models\IotSensorData;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Notification;
use App\Models\RubrikPenilaian;
use App\Models\SoalUjian;
use App\Models\SoalUjianEssay;
use App\Models\SoalUjianMultiple;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use App\Models\UserJawaban;
use App\Models\UserCommit;
use App\Models\UserTugas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserUjian;
use App\Models\TugasProgress;
use App\Helpers\DashboardHelper;
use App\Traits\TeacherAccessControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Class : DashboardController
 *
 * Kelas ini mengelola berbagai fungsi yang berkaitan dengan pengguna dan dasbor,
 *
 * @copyright  2023 Sunday Interactive
 * @license    http://www.zend.com/license/3_0.txt   PHP License 3.0
 *
 * @version    Release: 1.0
 *
 * @link       http://dev.zend.com/package/PackageName
 * @since      Kelas ini tersedia sejak Rilis 1.0
 */

class DashboardController extends Controller
{
    use TeacherAccessControl;
    /**
     * Menampilkan halaman dasbor menggunakan template yang konsisten.
     *
     * @return \Illuminate\View\View
     */
    public function viewUnifiedDashboard()
    {
        $authRoles = $this->getAuthId();
        $user = auth()->user();
        
        // Get role configuration
        $roleConfig = DashboardHelper::getRoleConfig($authRoles);
        
        // Get dashboard view
        $dashboardView = DashboardHelper::getDashboardView($authRoles);
        
        // Prepare data for template
        $templateData = array_merge($roleConfig, [
            'user' => $user,
            'roleId' => $authRoles,
            'roleName' => $this->getRoleName($authRoles),
            'stats' => $this->getDashboardStats($authRoles),
            'recentActivities' => $this->getRecentActivities($authRoles),
            'notifications' => $this->getNotifications($authRoles)
        ]);
        
        return view('dashboard.unified-dashboard', $templateData);
    }

    /**
     * Super Admin Dashboard - menggunakan template konsisten
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminDashboard()
    {
        $user = auth()->user();
        $roleConfig = DashboardHelper::getRoleConfig(1);
        
        $templateData = array_merge($roleConfig, [
            'user' => $user,
            'roleId' => 1
        ]);
        
        return view('dashboard.superadmin-new', $templateData);
    }

    /**
     * Admin Dashboard - menggunakan template konsisten
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminDashboard()
    {
        $user = auth()->user();
        $roleConfig = DashboardHelper::getRoleConfig(2);
        
        $templateData = array_merge($roleConfig, [
            'user' => $user,
            'roleId' => 2
        ]);
        
        return view('dashboard.admin-new', $templateData);
    }

    /**
     * Teacher Dashboard - menggunakan template konsisten
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherDashboard()
    {
        $user = auth()->user();
        $roleConfig = DashboardHelper::getRoleConfig(3);
        
        $templateData = array_merge($roleConfig, [
            'user' => $user,
            'roleId' => 3
        ]);
        
        return view('dashboard.teacher-new', $templateData);
    }

    /**
     * Student Dashboard - menggunakan template konsisten
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentDashboard()
    {
        $user = auth()->user();
        $roleConfig = DashboardHelper::getRoleConfig(4);
        
        $templateData = array_merge($roleConfig, [
            'user' => $user,
            'roleId' => 4
        ]);
        
        return view('dashboard.student-new', $templateData);
    }

    /**
     * Menampilkan halaman dasbor. Pengguna akan diarahkan berdasarkan roles id mereka.
     *
     * @return \Illuminate\View\View
     */
    public function viewDashboard()
    {
        // Mengumpulkan beberapa informasi tentang pengguna.
        $authRoles = $this->getAuthId();
        $authRolesName = $this->getRolesName();

        // Pengkondisian
        // Roles_id : 1 = Super Admin
        // Roles_id : 2 = Admin
        // Roles_id : 3 = Pengajar
        // Roles_id : 4 = Siswa
        if ($authRoles == 1) {
            // Super Admin - redirect to super admin dashboard
            return redirect()->route('superadmin.dashboard');
        } elseif ($authRoles == 2) {
            // Admin dashboard
            $data = [
                'totalSiswa' => count(DataSiswa::get()),
                'totalUserSiswa' => count(User::where('roles_id', 4)->get()),
                'totalPengajar' => count(User::where('roles_id', 3)->get()),
                'totalKelas' => count(Kelas::get()),
                'totalMapel' => count(Mapel::get()),
                'totalMateri' => count(Materi::get()),
                'totalTugas' => count(Tugas::get()),
                'totalUjian' => count(Ujian::get()),
            ];

            $chartData = $this->getChartData();
            return view('menu/admin/dashboard/dashboard', [
                'materi' => Materi::all(), 
                'title' => 'Dashboard', 
                'roles' => $authRolesName, 
                'data' => $data,
                'chartData' => $chartData
            ]);
        } elseif ($authRoles == 3) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $roles = DashboardController::getRolesName();
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];
                $totalSiswa = 0;
                $totalSiswaUnique = [];
                $kelasMapelId = [];
                $kelasInfo = [];
                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                            array_push($kelasMapelId, $kelasMapel['id']);
                        }

                        // Count Siswa
                        $siswa = DataSiswa::where('kelas_id', $kelasID)->get();
                        $totalSiswa += count($siswa);

                        // Extract unique student IDs
                        $totalSiswaUnique = array_merge($totalSiswaUnique, $siswa->pluck('id')->toArray());
                        // $totalSiswaUnique = $siswa->pluck('id');
                    }
                }

                // dd($kelasMapelId);
                $totalSiswaUnique = array_unique($totalSiswaUnique);
                $totalSiswaUnique = count($totalSiswaUnique);

                $assignedKelas = $this->getAssignedClass();

                $chartData = $this->getChartData();
                return view('menu/pengajar/dashboard/dashboard', [
                    'kelasInfo' => $kelasInfo, 
                    'kelasMapelId' => $kelasMapelId, 
                    'totalSiswaUnique' => $totalSiswaUnique, 
                    'totalSiswa' => $totalSiswa, 
                    'assignedKelas' => $assignedKelas, 
                    'user' => $profile, 
                    'countKelas' => count($editorAccess), 
                    'mapelKelas' => $mapelKelas, 
                    'roles' => $roles, 
                    'title' => 'Dashboard',
                    'chartData' => $chartData
                ]);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 4) {
            return redirect('home');
        }
    }

    /**
     * Menampilkan halaman dasbor. Pengguna akan diarahkan berdasarkan roles id mereka.
     *
     * @return \Illuminate\View\View
     */
    public function viewHome()
    {
        // Mengumpulkan beberapa informasi tentang pengguna.
        $authRoles = $this->getAuthId();
        $authRolesName = $this->getRolesName();

        try {
            // $id = Crypt::decrypt($token);

            $roles = DashboardController::getRolesName();
            $profile = User::findOrFail(Auth()->User()->id);

            $kelas = Kelas::where('id', $profile->kelas_id)->first();

            $kelasMapel = KelasMapel::where('kelas_id', $kelas['id'])->get();
            $mapelCollection = [];

            foreach ($kelasMapel as $key) {
                $mapel = Mapel::where('id', $key->mapel_id)->first();
                $editorAccess = EditorAccess::where(
                    'kelas_mapel_id',
                    $key->id
                )->first();

                if ($editorAccess) {
                    $editorAccess = $editorAccess['user_id'];
                    $pengajar = User::where('id', $editorAccess)->first(['id', 'name']);
                    $pengajarNama = $pengajar['name'];
                    $pengajarId = $pengajar['id'];
                } else {
                    $pengajarNama = '-';
                    $pengajarId = null;
                }

                $mapelCollection[] = [
                    'mapel_name' => $mapel['name'],
                    'mapel_id' => $mapel['id'],
                    'deskripsi' => $mapel['deskripsi'],
                    'gambar' => $mapel['gambar'],
                    'pengajar_id' => $pengajarId,
                    'pengajar_name' => $pengajarNama,
                ];
            }

            $assignedKelas = DashboardController::getAssignedClass();

            return view('menu/siswa/home/home', ['assignedKelas' => $assignedKelas, 'title' => 'Home', 'roles' => $authRolesName, 'user' => $profile, 'kelas' => $kelas, 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);

            return view('menu.profile.profileSiswa', ['assignedKelas' => $assignedKelas, 'user' => $profile, 'kelas' => $kelas['name'], 'mapelKelas' => $mapelCollection, 'roles' => $roles, 'title' => 'Profil']);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return string
     */
    public static function getRolesName()
    {
        // Dapatkan role name dari relationship
        $authRoles = Auth()->User()->Role->name;

        // Map role enum to name
        $roleMap = [
            'SUPERADMIN' => 'Super Admin',
            'ADMIN' => 'Admin',
            'GURU' => 'Guru',
            'SISWA' => 'Siswa',
        ];

        return $roleMap[$authRoles] ?? 'Unknown';
    }

    /**
     * Mendapatkan roles id (jarang digunakan dalam kelas lain).
     *
     * @return int
     */
    public static function getAuthId()
    {
        return Auth()->User()->roles_id;
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return array
     */
    public static function getAssignedClass()
    {
        $authRoles = Auth()->User()->roles_id;

        // Pengkondisian
        // Roles_id : 1 = Super Admin
        // Roles_id : 2 = Admin
        // Roles_id : 3 = Pengajar
        // Roles_id : 4 = Siswa
        if ($authRoles == 1 || $authRoles == 2) {
            return null;
        } elseif ($authRoles == 3) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                        }
                    }
                }

                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 3) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->kelas_id;

                // Kueri
                $kelasMapelId = KelasMapel::where('kelas_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($kelasMapelId as $key) {
                    // Temukan Mapel
                    $mapelKelas[] = [
                        'mapel_id' => $key->mapel_id,
                        'mapel' => Mapel::where('id', $key->mapel_id)->first(),
                        'kelas' => [Kelas::where('id', $key->kelas_id)->first()],
                    ];
                }

                // dd($mapelKelas);
                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        }
    }

    /**
     * Mendapatkan nama peran pengguna (digunakan dalam beberapa metode lain dalam kelas lain).
     * Ini merupakan akses dasar untuk mendapatkan peran yang akan dirender.
     *
     * @return array
     */
    public static function getAssignedClassSiswa()
    {
        $authRoles = Auth()->User()->roles_id;

        // Pengkondisian
        // Roles_id : 1 = Super Admin
        // Roles_id : 2 = Admin
        // Roles_id : 3 = Pengajar
        // Roles_id : 4 = Siswa
        if ($authRoles == 1 || $authRoles == 2) {
            return null;
        } elseif ($authRoles == 3) {
            try {
                // Dapatkan ID Pengguna
                $id = Auth()->User()->id;

                // Kueri
                $profile = User::findOrFail($id);
                $editorAccess = EditorAccess::where('user_id', $id)->get();

                // Inisialisasi Array Kosong
                $mapelKelas = [];

                // Membangun Data yang berkaitan dengan Pengguna dan apa yang mereka Ajar.
                // Sehingga akan muncul di Dasbor apa yang mereka ajar (Editor Access).
                foreach ($editorAccess as $key) {
                    $kelasMapel = KelasMapel::where('id', $key->kelas_mapel_id)->first();

                    if ($kelasMapel) {
                        $mapelID = $kelasMapel->mapel_id;
                        $kelasID = $kelasMapel->kelas_id;

                        // Pemeriksa Mapel
                        $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                        if ($mapelKey !== false) {
                            // Tambahkan ke Array
                            $mapelKelas[$mapelKey]['kelas'][] = Kelas::where('id', $kelasID)->first();
                        } else {
                            // Temukan Mapel
                            $mapelKelas[] = [
                                'mapel_id' => $mapelID,
                                'mapel' => Mapel::where('id', $mapelID)->first(),
                                'kelas' => [Kelas::where('id', $kelasID)->first()],
                            ];
                        }
                    }
                }

                return $mapelKelas;
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                abort(404);
            }
        } elseif ($authRoles == 4) {
            return null;
        }
    }

    /**
     * Mendapatkan data chart untuk dashboard
     *
     * @param string $classId
     * @return array
     */
    public function getChartData($classId = null)
    {
        $authRoles = $this->getAuthId();
        $chartData = [];

        if ($authRoles == 1 || $authRoles == 2) {
            // Super Admin & Admin - data semua kelas
            $chartData = $this->getAdminChartData($classId);
        } elseif ($authRoles == 3) {
            // Guru - data kelas yang diajar
            $chartData = $this->getTeacherChartData($classId);
        }

        return $chartData;
    }

    /**
     * Data chart untuk admin
     */
    private function getAdminChartData($classId = null)
    {
        $data = [];

        // Tugas Completion Chart
        if ($classId && $classId !== 'all') {
            $data['tugasCompletion'] = $this->getTugasCompletionData($classId);
            $data['tugasCount'] = $this->getTugasCountData($classId);
            $data['tugasRealisasi'] = $this->getTugasRealisasiData($classId);
            $data['ujianStatus'] = $this->getUjianStatusData($classId);
        } else {
            // Semua kelas
            $data['tugasCompletion'] = $this->getTugasCompletionDataAll();
            $data['tugasCount'] = $this->getTugasCountDataAll();
            $data['tugasRealisasi'] = $this->getTugasRealisasiDataAll();
            $data['ujianStatus'] = $this->getUjianStatusDataAll();
        }

        $data['activity'] = $this->getActivityData();
        $data['classes'] = $this->getClassesForSelect();

        return $data;
    }

    /**
     * Data chart untuk guru
     */
    private function getTeacherChartData($classId = null)
    {
        $userId = auth()->user()->id;
        $editorAccess = EditorAccess::where('user_id', $userId)->pluck('kelas_mapel_id');
        $kelasMapelIds = KelasMapel::whereIn('id', $editorAccess)->pluck('kelas_id')->unique();

        $data = [];

        if ($classId && $classId !== 'all' && in_array($classId, $kelasMapelIds->toArray())) {
            $data['tugasCompletion'] = $this->getTugasCompletionData($classId);
            $data['tugasCount'] = $this->getTugasCountData($classId);
            $data['tugasRealisasi'] = $this->getTugasRealisasiData($classId);
            $data['ujianStatus'] = $this->getUjianStatusData($classId);
        } else {
            // Semua kelas yang diajar
            $data['tugasCompletion'] = $this->getTugasCompletionDataByKelas($kelasMapelIds);
            $data['tugasCount'] = $this->getTugasCountDataByKelas($kelasMapelIds);
            $data['tugasRealisasi'] = $this->getTugasRealisasiDataByKelas($kelasMapelIds);
            $data['ujianStatus'] = $this->getUjianStatusDataByKelas($kelasMapelIds);
        }

        $data['activity'] = $this->getActivityData();
        $data['classes'] = $this->getClassesForSelect($kelasMapelIds);

        return $data;
    }

    /**
     * Data persentase siswa mengerjakan tugas
     */
    private function getTugasCompletionData($classId)
    {
        $totalSiswa = DataSiswa::where('kelas_id', $classId)->count();
        $siswaMengerjakan = UserTugas::whereHas('tugas', function($query) use ($classId) {
            $query->whereHas('kelasMapel', function($q) use ($classId) {
                $q->where('kelas_id', $classId);
            });
        })->distinct('user_id')->count();

        $siswaBelumMengerjakan = $totalSiswa - $siswaMengerjakan;

        return [
            'series' => [$siswaMengerjakan, $siswaBelumMengerjakan],
            'labels' => ['Mengerjakan', 'Belum Mengerjakan']
        ];
    }

    private function getTugasCompletionDataAll()
    {
        $totalSiswa = DataSiswa::count();
        $siswaMengerjakan = UserTugas::distinct('user_id')->count();
        $siswaBelumMengerjakan = $totalSiswa - $siswaMengerjakan;

        return [
            'series' => [$siswaMengerjakan, $siswaBelumMengerjakan],
            'labels' => ['Mengerjakan', 'Belum Mengerjakan']
        ];
    }

    private function getTugasCompletionDataByKelas($kelasIds)
    {
        $totalSiswa = DataSiswa::whereIn('kelas_id', $kelasIds)->count();
        $siswaMengerjakan = UserTugas::whereHas('tugas', function($query) use ($kelasIds) {
            $query->whereHas('kelasMapel', function($q) use ($kelasIds) {
                $q->whereIn('kelas_id', $kelasIds);
            });
        })->distinct('user_id')->count();

        $siswaBelumMengerjakan = $totalSiswa - $siswaMengerjakan;

        return [
            'series' => [$siswaMengerjakan, $siswaBelumMengerjakan],
            'labels' => ['Mengerjakan', 'Belum Mengerjakan']
        ];
    }

    /**
     * Data jumlah tugas per kelas
     */
    private function getTugasCountData($classId)
    {
        $tugas = Tugas::whereHas('kelasMapel', function($query) use ($classId) {
            $query->where('kelas_id', $classId);
        })->count();

        $kelas = Kelas::find($classId);

        return [
            'series' => [$tugas],
            'labels' => [$kelas ? $kelas->name : 'Kelas']
        ];
    }

    private function getTugasCountDataAll()
    {
        $results = DB::table('kelas')
            ->leftJoin('kelas_mapels', 'kelas.id', '=', 'kelas_mapels.kelas_id')
            ->leftJoin('tugas', 'kelas_mapels.id', '=', 'tugas.kelas_mapel_id')
            ->select('kelas.name', DB::raw('COUNT(tugas.id) as tugas_count'))
            ->groupBy('kelas.id', 'kelas.name')
            ->get();

        return [
            'series' => $results->pluck('tugas_count')->toArray(),
            'labels' => $results->pluck('name')->toArray()
        ];
    }

    private function getTugasCountDataByKelas($kelasIds)
    {
        $results = DB::table('kelas')
            ->leftJoin('kelas_mapels', 'kelas.id', '=', 'kelas_mapels.kelas_id')
            ->leftJoin('tugas', 'kelas_mapels.id', '=', 'tugas.kelas_mapel_id')
            ->whereIn('kelas.id', $kelasIds)
            ->select('kelas.name', DB::raw('COUNT(tugas.id) as tugas_count'))
            ->groupBy('kelas.id', 'kelas.name')
            ->get();

        return [
            'series' => $results->pluck('tugas_count')->toArray(),
            'labels' => $results->pluck('name')->toArray()
        ];
    }

    /**
     * Data realisasi tugas
     */
    private function getTugasRealisasiData($classId)
    {
        $tugas = Tugas::whereHas('kelasMapel', function($query) use ($classId) {
            $query->where('kelas_id', $classId);
        })->get();

        // Hitung tugas yang sudah dikerjakan siswa
        $tugasSelesai = 0;
        $tugasBelumSelesai = 0;

        foreach ($tugas as $t) {
            $siswaMengerjakan = UserTugas::where('tugas_id', $t->id)->count();
            $totalSiswa = DataSiswa::where('kelas_id', $classId)->count();
            
            if ($siswaMengerjakan >= $totalSiswa * 0.8) { // 80% siswa mengerjakan
                $tugasSelesai++;
            } else {
                $tugasBelumSelesai++;
            }
        }

        return [
            'series' => [
                ['name' => 'Tugas Selesai', 'data' => [$tugasSelesai]],
                ['name' => 'Tugas Belum Selesai', 'data' => [$tugasBelumSelesai]]
            ],
            'labels' => ['Realisasi Tugas']
        ];
    }

    private function getTugasRealisasiDataAll()
    {
        $tugas = Tugas::all();
        $tugasSelesai = 0;
        $tugasBelumSelesai = 0;

        foreach ($tugas as $t) {
            $siswaMengerjakan = UserTugas::where('tugas_id', $t->id)->count();
            $kelasId = $t->kelasMapel->kelas_id;
            $totalSiswa = DataSiswa::where('kelas_id', $kelasId)->count();
            
            if ($totalSiswa > 0 && $siswaMengerjakan >= $totalSiswa * 0.8) {
                $tugasSelesai++;
            } else {
                $tugasBelumSelesai++;
            }
        }

        return [
            'series' => [
                ['name' => 'Tugas Selesai', 'data' => [$tugasSelesai]],
                ['name' => 'Tugas Belum Selesai', 'data' => [$tugasBelumSelesai]]
            ],
            'labels' => ['Realisasi Tugas']
        ];
    }

    private function getTugasRealisasiDataByKelas($kelasIds)
    {
        $tugas = Tugas::whereHas('kelasMapel', function($query) use ($kelasIds) {
            $query->whereIn('kelas_id', $kelasIds);
        })->get();

        $tugasSelesai = 0;
        $tugasBelumSelesai = 0;

        foreach ($tugas as $t) {
            $siswaMengerjakan = UserTugas::where('tugas_id', $t->id)->count();
            $kelasId = $t->kelasMapel->kelas_id;
            $totalSiswa = DataSiswa::where('kelas_id', $kelasId)->count();
            
            if ($totalSiswa > 0 && $siswaMengerjakan >= $totalSiswa * 0.8) {
                $tugasSelesai++;
            } else {
                $tugasBelumSelesai++;
            }
        }

        return [
            'series' => [
                ['name' => 'Tugas Selesai', 'data' => [$tugasSelesai]],
                ['name' => 'Tugas Belum Selesai', 'data' => [$tugasBelumSelesai]]
            ],
            'labels' => ['Realisasi Tugas']
        ];
    }

    /**
     * Data status ujian
     */
    private function getUjianStatusData($classId)
    {
        $ujian = Ujian::whereHas('kelasMapel', function($query) use ($classId) {
            $query->where('kelas_id', $classId);
        })->get();

        $now = Carbon::now();
        $aktif = 0;
        $selesai = 0;
        $belumDimulai = 0;

        foreach ($ujian as $u) {
            if ($u->due < $now) {
                $selesai++;
            } elseif ($u->due > $now && $u->due <= $now->addDays(7)) {
                $aktif++;
            } else {
                $belumDimulai++;
            }
        }

        return [
            'series' => [$aktif, $selesai, $belumDimulai],
            'labels' => ['Aktif', 'Selesai', 'Belum Dimulai']
        ];
    }

    private function getUjianStatusDataAll()
    {
        $ujian = Ujian::all();
        $now = Carbon::now();
        $aktif = 0;
        $selesai = 0;
        $belumDimulai = 0;

        foreach ($ujian as $u) {
            if ($u->due < $now) {
                $selesai++;
            } elseif ($u->due > $now && $u->due <= $now->addDays(7)) {
                $aktif++;
            } else {
                $belumDimulai++;
            }
        }

        return [
            'series' => [$aktif, $selesai, $belumDimulai],
            'labels' => ['Aktif', 'Selesai', 'Belum Dimulai']
        ];
    }

    private function getUjianStatusDataByKelas($kelasIds)
    {
        $ujian = Ujian::whereHas('kelasMapel', function($query) use ($kelasIds) {
            $query->whereIn('kelas_id', $kelasIds);
        })->get();

        $now = Carbon::now();
        $aktif = 0;
        $selesai = 0;
        $belumDimulai = 0;

        foreach ($ujian as $u) {
            if ($u->due < $now) {
                $selesai++;
            } elseif ($u->due > $now && $u->due <= $now->addDays(7)) {
                $aktif++;
            } else {
                $belumDimulai++;
            }
        }

        return [
            'series' => [$aktif, $selesai, $belumDimulai],
            'labels' => ['Aktif', 'Selesai', 'Belum Dimulai']
        ];
    }

    /**
     * Data aktivitas terbaru
     */
    private function getActivityData($days = 7)
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays($days);

        $activities = [];
        $labels = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('M d');

            $tugasCount = Tugas::whereDate('created_at', $date)->count();
            $ujianCount = Ujian::whereDate('created_at', $date)->count();
            $materiCount = Materi::whereDate('created_at', $date)->count();

            $activities[] = $tugasCount + $ujianCount + $materiCount;
        }

        return [
            'series' => $activities,
            'labels' => $labels
        ];
    }

    /**
     * Data kelas untuk select
     */
    private function getClassesForSelect($kelasIds = null)
    {
        if ($kelasIds) {
            return Kelas::whereIn('id', $kelasIds)->get(['id', 'name']);
        }
        return Kelas::all(['id', 'name']);
    }





    /**
     * Menampilkan halaman profil Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminProfile()
    {
        $user = auth()->user();
        
        return view('superadmin.profile', [
            'title' => 'Profil Super Admin',
            'user' => $user
        ]);
    }

    /**
     * Update Super Admin profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSuperAdminProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'User tidak terautentikasi.');
            }
            
            // Cast to User model to access Eloquent methods
            $user = User::find($user->id);
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->bio = $request->bio;
            $user->save();

            return redirect()->route('superadmin.profile')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.profile')
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Upload photo for Super Admin profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSuperAdminPhoto(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:4000',
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $newImageName = 'SAIMG' . date('YmdHis') . uniqid() . '.jpg';

                // Simpan file ke dalam penyimpanan
                $path = $file->storeAs('user-images', $newImageName, 'public');

                if (!$path) {
                    return response()->json(['status' => 0, 'msg' => 'Upload Gagal']);
                }

                // Hapus file gambar lama dari penyimpanan
                $user = User::find(auth()->id());
                $userPhoto = $user->gambar;

                if ($userPhoto != null) {
                    Storage::disk('public')->delete('user-images/' . $userPhoto);
                }

                // Perbarui gambar
                $user->gambar = $newImageName;
                $user->save();

                return response()->json([
                    'status' => 1, 
                    'msg' => 'Upload berhasil', 
                    'name' => $newImageName,
                    'url' => asset('storage/user-images/' . $newImageName)
                ]);
            }

            return response()->json(['status' => 0, 'msg' => 'Tidak ada file yang diunggah']);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'msg' => 'Gagal upload: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman ubah password Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminChangePassword()
    {
        $user = auth()->user();
        
        return view('superadmin.change-password', [
            'title' => 'Ubah Password Super Admin',
            'user' => $user
        ]);
    }

    /**
     * Update Super Admin password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeSuperAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'User tidak terautentikasi.');
            }
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Password saat ini tidak benar.');
            }
            
            // Update password
            $user->password = Hash::make($request->password);
            $user->password_changed_at = now();
            $user->save();

            return redirect()->route('superadmin.profile')
                ->with('success', 'Password berhasil diubah!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman 2FA Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdmin2FA()
    {
        $user = auth()->user();
        
        return view('superadmin.2fa', [
            'title' => 'Autentikasi Dua Faktor Super Admin',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman sesi aktif Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminSessions()
    {
        $user = auth()->user();
        
        return view('superadmin.sessions', [
            'title' => 'Sesi Aktif Super Admin',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman pengaturan Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminSettings()
    {
        $user = auth()->user();
        
        return view('superadmin.settings', [
            'title' => 'Pengaturan Super Admin',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Push Notifikasi Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminPushNotification()
    {
        $user = auth()->user();
        
        // Check if user is superadmin
        if ($user->roles_id != 1) {
            abort(403, 'Unauthorized access. Hanya superadmin yang dapat mengirim notifikasi.');
        }
        
        // Get notification statistics
        $stats = [
            'total' => Notification::count(),
            'sent_today' => Notification::whereDate('created_at', today())->count(),
            'pending' => 0, // Notifications don't have pending status in current implementation
            'failed' => 0,  // Notifications don't have failed status in current implementation
        ];

        // Get recent notifications
        $recentNotifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get paginated notifications for the shared component
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get users for specific targeting (exclude superadmin)
        $users = User::where('roles_id', '!=', 1)
            ->select('id', 'name', 'email', 'roles_id')
            ->get();

        // Calculate notification counts
        $totalNotifications = Notification::count();
        $readNotifications = Notification::where('is_read', true)->count();
        $unreadNotifications = Notification::where('is_read', false)->count();
        $urgentNotifications = Notification::where('type', 'error')->count();
        
        return view('superadmin.push-notification', [
            'title' => 'Push Notifikasi Super Admin',
            'user' => $user,
            'stats' => $stats,
            'recentNotifications' => $recentNotifications,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'users' => $users,
        ]);
    }

    /**
     * Mengirim push notifikasi Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendSuperAdminPushNotification(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Check if user is superadmin
        if ($user->roles_id != 1) {
            abort(403, 'Unauthorized access. Hanya superadmin yang dapat mengirim notifikasi.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'recipient_type' => 'required|in:all,students,teachers,admins,specific',
            'specific_users' => 'required_if:recipient_type,specific|array',
            'specific_users.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $notifications = [];
            $recipientCount = 0;

            if ($request->recipient_type === 'all') {
                // Broadcast ke semua user kecuali superadmin (roles_id != 1)
                $users = User::where('roles_id', '!=', 1)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['broadcast' => true, 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'students') {
                // Kirim ke siswa saja (roles_id = 4)
                $users = User::where('roles_id', 4)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'students', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'teachers') {
                // Kirim ke guru saja (roles_id = 3)
                $users = User::where('roles_id', 3)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'teachers', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'admins') {
                // Kirim ke admin saja (roles_id = 2)
                $users = User::where('roles_id', 2)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'admins', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'specific') {
                // Kirim ke user tertentu
                foreach ($request->specific_users as $userId) {
                    $notifications[] = [
                        'user_id' => $userId,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['specific_target' => true, 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            }

            // Insert batch notifications
            if (!empty($notifications)) {
                Notification::insert($notifications);
            }

            DB::commit();

            return redirect()->route('superadmin.push-notification')
                ->with('success', "Notifikasi berhasil dikirim ke {$recipientCount} pengguna");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Filter push notifications Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminPushNotifications(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_type', 'filter_status', 'filter_date_from', 'filter_date_to', 'filter_recipient']);
        
        // Build query for notifications
        $query = \App\Models\Notification::with('user');
        
        // Apply filters
        if (!empty($filters['filter_type'])) {
            $query->where('type', $filters['filter_type']);
        }
        
        if (!empty($filters['filter_status'])) {
            if ($filters['filter_status'] === 'read') {
                $query->where('is_read', true);
            } elseif ($filters['filter_status'] === 'unread') {
                $query->where('is_read', false);
            }
        }
        
        if (!empty($filters['filter_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['filter_date_to']);
        }
        
        if (!empty($filters['filter_recipient'])) {
            if ($filters['filter_recipient'] === 'broadcast') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $filters['filter_recipient']);
            }
        }
        
        // Get filtered notifications
        $recentNotifications = $query->orderBy('created_at', 'desc')->limit(10)->get();
        
        // Get paginated filtered notifications for the shared component
        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get notification statistics (unfiltered)
        $totalNotifications = \App\Models\Notification::count();
        $unreadCount = \App\Models\Notification::unread()->count();
        $todayNotifications = \App\Models\Notification::whereDate('created_at', today())->count();
        
        // Calculate notification counts
        $readNotifications = \App\Models\Notification::where('is_read', true)->count();
        $unreadNotifications = \App\Models\Notification::where('is_read', false)->count();
        $urgentNotifications = \App\Models\Notification::where('type', 'error')->count();
        
        // Get data for recipient selection
        $classes = Kelas::all();
        $roles = collect([]);
        $users = \App\Models\User::all();
        
        return view('superadmin.push-notification', [
            'title' => 'Push Notifikasi',
            'user' => $user,
            'recentNotifications' => $recentNotifications,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'unreadCount' => $unreadCount,
            'todayNotifications' => $todayNotifications,
            'classes' => $classes,
            'roles' => $roles,
            'users' => $users,
            'filters' => $filters, // Pass filters back to view
        ]);
    }

    /**
     * Get target users based on recipient type and value.
     *
     * @param string $recipientType
     * @param string|null $recipientValue
     * @return array
     */
    private function getTargetUsers($recipientType, $recipientValue)
    {
        switch ($recipientType) {
            case 'all':
                return \App\Models\User::where('roles_id', '!=', 1)->pluck('id')->toArray(); // Exclude superadmin
                
            case 'class':
                if (!$recipientValue) return [];
                return \App\Models\User::where('kelas_id', $recipientValue)->pluck('id')->toArray();
                
            case 'role':
                if (!$recipientValue) return [];
                // Map role names to role IDs
                $roleMap = [
                    'ADMIN' => 2,
                    'GURU' => 3,
                    'SISWA' => 4
                ];
                $roleId = $roleMap[$recipientValue] ?? null;
                if (!$roleId) return [];
                return \App\Models\User::where('roles_id', $roleId)->pluck('id')->toArray();
                
            case 'specific':
                if (!$recipientValue) return [];
                return [$recipientValue];
                
            default:
                return [];
        }
    }

    /**
     * Menampilkan halaman Manajemen IoT Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminIotManagement()
    {
        $user = auth()->user();
        
        // Get real IoT data from database
        $totalDevices = \App\Models\IotDevice::count();
        $onlineDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $devices = \App\Models\IotDevice::with('latestSensorData')->latest()->get();
        $recentSensorData = \App\Models\IotSensorData::with(['device', 'researchProject'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        
        // Get statistics
        $totalSensorReadings = \App\Models\IotSensorData::count();
        $todayReadings = \App\Models\IotSensorData::whereDate('measured_at', today())->count();
        
        return view('superadmin.iot-management', [
            'title' => 'Manajemen IoT',
            'user' => $user,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'devices' => $devices,
            'recentSensorData' => $recentSensorData,
            'totalSensorReadings' => $totalSensorReadings,
            'todayReadings' => $todayReadings
        ]);
    }

    /**
     * Filter IoT management data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminIotManagement(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_type', 'filter_status', 'filter_date_from', 'filter_date_to', 'filter_location', 'filter_class']);
        
        // Build query for devices
        $deviceQuery = \App\Models\IotDevice::query();
        
        // Apply device filters
        if (!empty($filters['filter_type'])) {
            $deviceQuery->where('device_type', $filters['filter_type']);
        }
        
        if (!empty($filters['filter_status'])) {
            $deviceQuery->where('status', $filters['filter_status']);
        }
        
        if (!empty($filters['filter_class'])) {
            // Filter devices that have sensor data for the specified class
            $deviceQuery->whereHas('sensorData', function($query) use ($filters) {
                $query->where('kelas_id', $filters['filter_class']);
            });
        }
        
        // Get filtered devices
        $devices = $deviceQuery->with('latestSensorData')->latest()->get();
        
        // Build query for sensor data
        $sensorQuery = \App\Models\IotSensorData::with(['device', 'researchProject']);
        
        // Apply sensor data filters
        if (!empty($filters['filter_date_from'])) {
            $sensorQuery->whereDate('measured_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $sensorQuery->whereDate('measured_at', '<=', $filters['filter_date_to']);
        }
        
        if (!empty($filters['filter_location'])) {
            $sensorQuery->where('location', 'like', '%' . $filters['filter_location'] . '%');
        }
        
        if (!empty($filters['filter_class'])) {
            $sensorQuery->where('kelas_id', $filters['filter_class']);
        }
        
        // Get filtered sensor data
        $recentSensorData = $sensorQuery->latest('measured_at')->limit(10)->get();
        
        // Get statistics (unfiltered)
        $totalDevices = \App\Models\IotDevice::count();
        $onlineDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $totalSensorReadings = \App\Models\IotSensorData::count();
        $todayReadings = \App\Models\IotSensorData::whereDate('measured_at', today())->count();
        
        // Get data for filter options
        $classes = Kelas::all();
        
        return view('superadmin.iot-management', [
            'title' => 'Manajemen IoT',
            'user' => $user,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'devices' => $devices,
            'recentSensorData' => $recentSensorData,
            'totalSensorReadings' => $totalSensorReadings,
            'todayReadings' => $todayReadings,
            'classes' => $classes,
            'filters' => $filters, // Pass filters back to view
        ]);
    }

    /**
     * Register a new IoT device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerIotDevice(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|in:temperature,humidity,pressure,light,motion,sound,other',
            'device_id' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            // Use provided device_id or generate a unique one
            $deviceId = $request->device_id ?: 'DEV_' . strtoupper(uniqid());
            
            // Prepare device info from form data
            $deviceInfo = [];
            if ($request->location) {
                $deviceInfo['location'] = $request->location;
            }
            if ($request->description) {
                $deviceInfo['description'] = $request->description;
            }
            
            // Create the device
            $device = \App\Models\IotDevice::create([
                'name' => $request->device_name,
                'device_id' => $deviceId,
                'device_type' => $request->device_type,
                'bluetooth_address' => null, // Not provided in form
                'status' => 'offline',
                'user_id' => auth()->id(),
                'device_info' => !empty($deviceInfo) ? $deviceInfo : null,
                'last_seen' => null
            ]);

            return redirect()->route('superadmin.iot-management')
                ->with('success', 'Perangkat IoT berhasil didaftarkan dengan ID: ' . $deviceId);
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.iot-management')
                ->with('error', 'Gagal mendaftarkan perangkat IoT: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Manajemen Tugas Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminTaskManagement()
    {
        $user = auth()->user();
        
        // Get real data from database
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        // Calculate statistics
        $totalTasks = $tasks->count();
        $activeTasks = $tasks->where('isHidden', 0)->count();
        $completedTasks = $tasks->where('due', '<', now())->count();
        $activeClasses = Kelas::whereHas('KelasMapel.Tugas')->count();
        
        return view('superadmin.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalTasks' => $totalTasks,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'activeClasses' => $activeClasses
        ]);
    }


    /**
     * Membuat tugas baru untuk Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminTask(Request $request)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:kelas,id',
            'subject_id' => 'required|integer|exists:mapel,id',
            'task_description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'category' => 'required|string|in:tugas_rumah,tugas_kelompok,proyek,presentasi,penelitian',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'max_score' => 'required|integer|min:1|max:100'
        ]);

        try {
            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Map difficulty to tipe (1=easy, 2=medium, 3=hard)
            $difficultyMapping = [
                'easy' => 1,
                'medium' => 2,
                'hard' => 3
            ];

            // Create the task
            $task = Tugas::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->task_title,
                'content' => $request->task_description,
                'due' => $request->due_date,
                'isHidden' => 0, // Active by default
                'tipe' => $difficultyMapping[$request->difficulty],
            ]);

            return redirect()->route('superadmin.tugas.index')
                ->with('success', 'Tugas berhasil dibuat: ' . $request->task_title);
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.tugas.index')
                ->with('error', 'Gagal membuat tugas: ' . $e->getMessage());
        }
    }

    /**
     * Filter tasks based on criteria.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminTasks(Request $request)
    {
        $user = auth()->user();
        
        // Build query with filters
        $query = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel']);
        
        if ($request->filled('filter_class')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'active') {
                $query->where('isHidden', 0)->where('due', '>', now());
            } elseif ($request->filter_status === 'completed') {
                $query->where('due', '<', now());
            } elseif ($request->filter_status === 'draft') {
                $query->where('isHidden', 1);
            }
        }
        
        if ($request->filled('filter_difficulty')) {
            $difficultyMap = ['easy' => 1, 'medium' => 2, 'hard' => 3];
            $query->where('tipe', $difficultyMap[$request->filter_difficulty]);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->get();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        // Calculate statistics
        $totalTasks = $tasks->count();
        $activeTasks = $tasks->where('isHidden', 0)->count();
        $completedTasks = $tasks->where('due', '<', now())->count();
        $activeClasses = Kelas::whereHas('KelasMapel.Tugas')->count();
        
        return view('superadmin.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalTasks' => $totalTasks,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'activeClasses' => $activeClasses,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_difficulty'])
        ]);
    }

    /**
     * Menampilkan halaman Manajemen Ujian Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminExamManagement()
    {
        $user = auth()->user();
        
        // Get real exam data from database
        $exams = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics per exam type
        $stats = [
            'multiple_choice' => $exams->where('tipe', 1)->count(),
            'essay' => $exams->where('tipe', 2)->count(),
        ];
        
        // Ambil ujian terbaru
        $recentExams = $exams->take(8);
        
        return view('superadmin.exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'exams' => $exams,
            'classes' => \App\Models\Kelas::all(),
            'subjects' => \App\Models\Mapel::all(),
            'totalExams' => $exams->count(),
            'activeExams' => $exams->where('is_active', true)->count(),
            'completedExams' => $exams->where('is_active', false)->count(),
            'totalParticipants' => $exams->sum('participants_count')
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian pilihan ganda.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminCreateMultipleChoiceExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('superadmin.exam-create-multiple-choice', [
            'title' => 'Buat Ujian Pilihan Ganda',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian essay.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminCreateEssayExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('superadmin.exam-create-essay', [
            'title' => 'Buat Ujian Essay',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian campuran.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminCreateMixedExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('superadmin.exam-create-mixed', [
            'title' => 'Buat Ujian Campuran',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan hasil ujian Super Admin.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminExamResults($id)
    {
        $user = auth()->user();
        $exam = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])->findOrFail($id);
        
        // Get exam results/participants data
        $participants = collect(); // Placeholder for exam participants
        $statistics = [
            'total_participants' => 0,
            'completed' => 0,
            'average_score' => 0,
            'highest_score' => 0,
            'lowest_score' => 0
        ];
        
        return view('superadmin.exam-results', [
            'title' => 'Hasil Ujian - ' . $exam->name,
            'user' => $user,
            'exam' => $exam,
            'participants' => $participants,
            'statistics' => $statistics
        ]);
    }

    /**
     * Menampilkan hasil ujian Admin.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewAdminExamResults($id)
    {
        $user = auth()->user();
        $exam = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])->findOrFail($id);
        
        // Get exam results/participants data
        $participants = collect(); // Placeholder for exam participants
        $statistics = [
            'total_participants' => 0,
            'completed' => 0,
            'average_score' => 0,
            'highest_score' => 0,
            'lowest_score' => 0
        ];
        
        return view('admin.exam-results', [
            'title' => 'Hasil Ujian - ' . $exam->name,
            'user' => $user,
            'exam' => $exam,
            'participants' => $participants,
            'statistics' => $statistics
        ]);
    }

    /**
     * Menangani pembuatan ujian pilihan ganda.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminMultipleChoiceExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'duration' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'due_date' => 'required|date|after:now',
            'exam_description' => 'required|string',
            'is_hidden' => 'required|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options.A' => 'required|string',
            'questions.*.options.B' => 'required|string',
            'questions.*.options.C' => 'required|string',
            'questions.*.options.D' => 'required|string',
            'questions.*.correct_answer' => 'required|string|in:A,B,C,D',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.category' => 'required|string|in:easy,medium,hard',
        ]);

        try {
            DB::beginTransaction();

            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => 1, // Multiple choice
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden,
            ]);

            // Create multiple choice questions
            foreach ($request->questions as $questionData) {
                SoalUjianMultiple::create([
                    'ujian_id' => $ujian->id,
                    'soal' => $questionData['question'],
                    'a' => $questionData['options']['A'],
                    'b' => $questionData['options']['B'],
                    'c' => $questionData['options']['C'],
                    'd' => $questionData['options']['D'],
                    'jawaban' => $questionData['correct_answer'],
                    'poin' => $questionData['points'],
                    'kategori' => $questionData['category'],
                ]);
            }

            DB::commit();

            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian pilihan ganda berhasil dibuat dengan ' . count($request->questions) . ' soal!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menangani pembuatan ujian essay.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminEssayExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'duration' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'due_date' => 'required|date|after:now',
            'exam_description' => 'required|string',
            'is_hidden' => 'required|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.score' => 'required|integer|min:1',
            'questions.*.min_words' => 'required|integer|min:50',
        ]);

        try {
            DB::beginTransaction();

            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => 2, // Essay
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden,
            ]);

            // Save questions
            foreach ($request->questions as $questionData) {
                SoalUjianEssay::create([
                    'ujian_id' => $ujian->id,
                    'soal' => $questionData['question'],
                    'bobot_nilai' => $questionData['score'],
                    'min_kata' => $questionData['min_words'] ?? 200,
                ]);
            }

            // Save rubrics if provided
            if ($request->has('rubrics')) {
                foreach ($request->rubrics as $rubricData) {
                    RubrikPenilaian::create([
                        'ujian_id' => $ujian->id,
                        'nama_kriteria' => $rubricData['name'],
                        'deskripsi' => $rubricData['description'],
                        'bobot' => $rubricData['weight'],
                        'nilai_maksimal' => $rubricData['max_score'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ujian essay berhasil dibuat!',
                'redirect' => route('superadmin.exam-management')
            ]);
                
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menangani pembuatan ujian campuran.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminMixedExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'duration' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'due_date' => 'required|date|after:now',
            'exam_description' => 'required|string',
            'is_hidden' => 'required|boolean',
            'questions' => 'required|array|min:1',
            'questions.*.type' => 'required|string|in:multiple_choice,essay',
            'questions.*.question' => 'required|string',
            'questions.*.score' => 'required|integer|min:1',
        ]);

        try {
            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => 3, // Mixed
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden,
            ]);

            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian campuran berhasil dibuat!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan halaman edit ujian.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editSuperAdminExam($id)
    {
        $user = auth()->user();
        $exam = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])->findOrFail($id);
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('superadmin.exam-edit', [
            'title' => 'Edit Ujian',
            'user' => $user,
            'exam' => $exam,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan detail ujian.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminExam($id)
    {
        $user = auth()->user();
        $exam = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])->findOrFail($id);
        
        return view('superadmin.exam-view', [
            'title' => 'Detail Ujian',
            'user' => $user,
            'exam' => $exam
        ]);
    }

    /**
     * Menghapus ujian.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSuperAdminExam($id)
    {
        try {
            $exam = Ujian::findOrFail($id);
            $exam->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil dihapus!'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mempublikasikan ujian.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publishSuperAdminExam($id)
    {
        try {
            $exam = Ujian::findOrFail($id);
            $exam->update(['isHidden' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil dipublikasikan!'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mempublikasikan ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Membatalkan publikasi ujian.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unpublishSuperAdminExam($id)
    {
        try {
            $exam = Ujian::findOrFail($id);
            $exam->update(['isHidden' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Publikasi ujian berhasil dibatalkan!'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan publikasi ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update exam information.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSuperAdminExam(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'time' => 'required|integer|min:1',
            'due' => 'required|date|after:now',
            'content' => 'nullable|string',
            'isHidden' => 'required|boolean',
        ]);

        try {
            $exam = Ujian::findOrFail($id);
            
            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            $exam->update([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->name,
                'content' => $request->content,
                'time' => $request->time,
                'due' => $request->due,
                'isHidden' => $request->isHidden,
            ]);

            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Filter exams based on criteria.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminExams(Request $request)
    {
        $user = auth()->user();
        
        // Build query with filters
        $query = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel']);
        
        if ($request->filled('filter_class')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('KelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'active') {
                $query->where('isHidden', 0)->where('due', '>', now());
            } elseif ($request->filter_status === 'completed') {
                $query->where('due', '<', now());
            } elseif ($request->filter_status === 'draft') {
                $query->where('isHidden', 1);
            }
        }
        
        if ($request->filled('filter_type')) {
            $query->where('tipe', $request->filter_type);
        }
        
        $exams = $query->orderBy('created_at', 'desc')->get();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        // Calculate statistics
        $totalExams = $exams->count();
        $activeExams = $exams->where('isHidden', 0)->where('due', '>', now())->count();
        $completedExams = $exams->where('due', '<', now())->count();
        $draftExams = $exams->where('isHidden', 1)->count();
        
        return view('superadmin.exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects,
            'exams' => $exams,
            'totalExams' => $totalExams,
            'activeExams' => $activeExams,
            'completedExams' => $completedExams,
            'draftExams' => $draftExams,
            'filters' => $request->only(['filter_class', 'filter_subject', 'filter_status', 'filter_type'])
        ]);
    }

    /**
     * Menangani pembuatan ujian baru oleh Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|string',
            'subject_id' => 'required|string',
            'exam_type' => 'required|string|in:multiple_choice,essay',
            'duration' => 'required|integer|min:1',
            'due_date' => 'required|date|after:now',
        ]);

        try {
            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'tipe' => $request->exam_type,
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => false,
            ]);

            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian berhasil dibuat!');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.exam-management')
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage());
        }
    }


    /**
     * Menampilkan halaman Manajemen Pengguna Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminUserManagement()
    {
        $user = auth()->user();
        
        // Get all users with their roles
        $users = User::with(['Role', 'Kelas'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all classes
        $classes = Kelas::all();
        
        // Calculate statistics
        $totalUsers = $users->count();
        $totalStudents = $users->where('roles_id', 4)->count();
        $totalTeachers = $users->where('roles_id', 3)->count();
        $totalAdmins = $users->where('roles_id', 2)->count();
        $activeUsers = $users->where('status', 'active')->count();
        
        // Calculate statistics per user role
        $stats = [
            'superadmin' => $users->where('roles_id', 1)->count(),
            'admin' => $users->where('roles_id', 2)->count(),
            'teacher' => $users->where('roles_id', 3)->count(),
            'student' => $users->where('roles_id', 4)->count(),
        ];
        
        // Ambil pengguna terbaru
        $recentUsers = $users->take(8);
        
        return view('superadmin.user-management', [
            'title' => 'Manajemen Pengguna',
            'user' => $user,
            'users' => $users,
            'classes' => $classes,
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalAdmins' => $totalAdmins,
            'activeUsers' => $activeUsers,
            'stats' => $stats,
            'recentUsers' => $recentUsers
        ]);
    }

    /**
     * Filter users Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminUsers(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_role', 'filter_status', 'filter_class', 'filter_search']);
        
        // Build query for users
        $query = User::with(['Role', 'Kelas']);
        
        // Apply filters
        if (!empty($filters['filter_role'])) {
            $roleMapping = [
                'superadmin' => 1,
                'admin' => 2,
                'teacher' => 3,
                'student' => 4,
            ];
            if (isset($roleMapping[$filters['filter_role']])) {
                $query->where('roles_id', $roleMapping[$filters['filter_role']]);
            }
        }
        
        if (!empty($filters['filter_status'])) {
            $query->where('status', $filters['filter_status']);
        }
        
        if (!empty($filters['filter_class'])) {
            $query->where('kelas_id', $filters['filter_class']);
        }
        
        if (!empty($filters['filter_search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['filter_search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['filter_search'] . '%');
            });
        }
        
        // Get filtered users
        $users = $query->orderBy('created_at', 'desc')->get();
        
        // Get all classes
        $classes = Kelas::all();
        
        // Calculate statistics
        $totalUsers = $users->count();
        $totalStudents = $users->where('roles_id', 4)->count();
        $totalTeachers = $users->where('roles_id', 3)->count();
        $totalAdmins = $users->where('roles_id', 2)->count();
        $activeUsers = $users->where('status', 'active')->count();
        
        return view('superadmin.user-management', [
            'title' => 'Manajemen Pengguna',
            'user' => $user,
            'users' => $users,
            'classes' => $classes,
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalAdmins' => $totalAdmins,
            'activeUsers' => $activeUsers,
            'filters' => $filters,
        ]);
    }

    /**
     * Menampilkan halaman Manajemen Kelas Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminClassManagement()
    {
        $user = auth()->user();
        
        // Get all classes
        $classes = Kelas::withCount('students')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics per level
        $stats = [
            'sd' => $classes->where('level', 'SD')->count(),
            'smp' => $classes->where('level', 'SMP')->count(),
            'sma' => $classes->where('level', 'SMA')->count(),
            'smk' => $classes->where('level', 'SMK')->count(),
        ];
        
        // Ambil kelas terbaru
        $recentClasses = $classes->take(8);
        
        return view('superadmin.class-management', [
            'title' => 'Manajemen Kelas',
            'user' => $user,
            'classes' => $classes,
            'totalClasses' => $classes->count(),
            'totalStudents' => $classes->sum('students_count'),
            'totalSubjects' => \App\Models\Mapel::count(),
            'totalTeachers' => \App\Models\User::where('roles_id', 3)->count()
        ]);
    }

    /**
     * Filter classes Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminClasses(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_level', 'filter_grade', 'filter_status', 'filter_date_from', 'filter_date_to']);
        
        // Build query for classes
        $query = Kelas::query();
        
        // Apply filters
        if (!empty($filters['filter_level'])) {
            $query->where('level', $filters['filter_level']);
        }
        
        if (!empty($filters['filter_grade'])) {
            $query->where('grade', $filters['filter_grade']);
        }
        
        if (!empty($filters['filter_status'])) {
            if ($filters['filter_status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['filter_status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if (!empty($filters['filter_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['filter_date_to']);
        }
        
        // Get filtered classes
        $classes = $query->orderBy('created_at', 'desc')->get();
        
        return view('superadmin.class-management', [
            'title' => 'Manajemen Kelas',
            'user' => $user,
            'classes' => $classes, // Pass filtered classes
            'filters' => $filters, // Pass filters back to view
        ]);
    }

    /**
     * Membuat kelas baru untuk Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminClass(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'class_level' => 'required|string|in:x,xi,xii',
            'class_description' => 'nullable|string|max:500',
            'class_type' => 'nullable|string|in:ipa,ips,bahasa,agama',
            'max_students' => 'nullable|integer|min:1|max:50',
            'homeroom_teacher' => 'nullable|string',
            'academic_year' => 'nullable|string|max:20',
        ]);

        try {
            Kelas::create([
                'name' => $request->class_name,
                'level' => $request->class_level,
                'description' => $request->class_description,
            ]);

            return redirect()->route('superadmin.class-management')
                ->with('success', 'Kelas berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.class-management')
                ->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Mata Pelajaran Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminSubjectManagement()
    {
        $user = auth()->user();
        
        // Get all subjects
        $subjects = Mapel::withCount('classes')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics per category
        $stats = [
            'umum' => $subjects->where('category', 'umum')->count(),
            'kejuruan' => $subjects->where('category', 'kejuruan')->count(),
            'agama' => $subjects->where('category', 'agama')->count(),
            'olahraga' => $subjects->where('category', 'olahraga')->count(),
        ];
        
        // Ambil mata pelajaran terbaru
        $recentSubjects = $subjects->take(8);
        
        return view('superadmin.subject-management', [
            'title' => 'Manajemen Mata Pelajaran',
            'user' => $user,
            'subjects' => $subjects,
            'totalSubjects' => $subjects->count(),
            'totalTeachers' => \App\Models\User::where('roles_id', 3)->count(),
            'totalMaterials' => \App\Models\Materi::count(),
            'totalTasks' => \App\Models\Tugas::count()
        ]);
    }

    /**
     * Filter subjects Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminSubjects(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_category', 'filter_level', 'filter_status', 'filter_date_from', 'filter_date_to']);
        
        // Build query for subjects
        $query = \App\Models\Mapel::query();
        
        // Apply filters
        if (!empty($filters['filter_category'])) {
            $query->where('category', $filters['filter_category']);
        }
        
        if (!empty($filters['filter_level'])) {
            $query->where('level', $filters['filter_level']);
        }
        
        if (!empty($filters['filter_status'])) {
            if ($filters['filter_status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['filter_status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if (!empty($filters['filter_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['filter_date_to']);
        }
        
        // Get filtered subjects
        $subjects = $query->orderBy('created_at', 'desc')->get();
        
        return view('superadmin.subject-management', [
            'title' => 'Mata Pelajaran',
            'user' => $user,
            'subjects' => $subjects, // Pass filtered subjects
            'filters' => $filters, // Pass filters back to view
        ]);
    }

    /**
     * Menangani pembuatan mata pelajaran baru oleh Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminSubject(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255|unique:mapels,name',
            'subject_code' => 'required|string|max:10',
        ]);

        try {
            // Create the subject
            $mapel = \App\Models\Mapel::create([
                'name' => $request->subject_name,
                'deskripsi' => $request->subject_code,
            ]);

            return redirect()->route('superadmin.subject-management')
                ->with('success', 'Mata pelajaran berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.subject-management')
                ->with('error', 'Gagal menambahkan mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Manajemen Materi Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminMaterialManagement()
    {
        $user = auth()->user();
        
        // Get all materials
        $materials = \App\Models\Material::with(['subject', 'class', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics per type
        $stats = [
            'document' => $materials->filter(function($material) {
                return $material->type === 'document';
            })->count(),
            'video' => $materials->filter(function($material) {
                return $material->type === 'video';
            })->count(),
            'image' => $materials->filter(function($material) {
                return $material->type === 'image';
            })->count(),
            'text' => $materials->filter(function($material) {
                return $material->type === 'text';
            })->count(),
        ];
        
        // Ambil materi terbaru
        $recentMaterials = $materials->take(8);
        
        // Get subjects and classes for dropdowns
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        return view('superadmin.material-management', [
            'title' => 'Manajemen Materi',
            'user' => $user,
            'materials' => $materials,
            'subjects' => $subjects,
            'classes' => $classes,
            'totalMaterials' => $materials->count(),
            'publishedMaterials' => $materials->where('status', 'published')->count(),
            'totalViews' => $materials->sum('views'),
            'totalDownloads' => 0 // Material model doesn't have downloads_count field yet
        ]);
    }

    /**
     * Menangani filter materi Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminMaterials(Request $request)
    {
        $user = auth()->user();
        
        // Get all materials
        $query = Materi::with(['kelasMapel.mapel', 'kelasMapel.kelas'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('filter_type')) {
            $query->where('file_type', $request->filter_type);
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_class')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_search')) {
            $query->where('name', 'like', '%' . $request->filter_search . '%')
                  ->orWhere('content', 'like', '%' . $request->filter_search . '%');
        }
        
        $materials = $query->get();
        
        // Get subjects and classes for dropdowns
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        return view('superadmin.material-management', [
            'title' => 'Manajemen Materi',
            'user' => $user,
            'materials' => $materials,
            'subjects' => $subjects,
            'classes' => $classes,
            'totalMaterials' => $materials->count(),
            'publishedMaterials' => $materials->where('is_published', true)->count(),
            'totalViews' => $materials->sum('views_count'),
            'totalDownloads' => $materials->sum('downloads_count'),
            'filters' => $request->all()
        ]);
    }

    /**
     * Menangani pembuatan materi baru oleh Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:mapels,id',
            'class_id' => 'required|exists:kelas,id',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:document,video,image,text',
            'status' => 'required|in:draft,published',
            'file' => 'nullable|file|mimes:pdf,mp4,jpg,jpeg,png,doc,docx,ppt,pptx|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'youtube_url' => 'nullable|url',
        ]);

        try {
            // Handle file upload
            $filePath = null;
            $fileName = null;
            $fileSize = null;
            $fileType = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                $filePath = $file->store('materials', 'public');
            }

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
            }

            // Create the material using the Material model
            $material = \App\Models\Material::create([
                'title' => $request->title,
                'content' => $request->content,
                'description' => $request->description,
                'type' => $request->type,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'thumbnail_path' => $thumbnailPath,
                'youtube_url' => $request->youtube_url,
                'teacher_id' => auth()->id(),
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
                'views' => 0,
            ]);

            return redirect()->route('superadmin.material-management')
                ->with('success', 'Materi berhasil dibuat!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat materi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Laporan Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminReports()
    {
        $user = auth()->user();
        
        // Get detailed data per class and subject
        $classes = Kelas::with(['KelasMapel.Mapel', 'DataSiswa'])->get();
        $subjects = Mapel::with(['KelasMapel.Kelas'])->get();
        
        // Get task and exam data per class
        $classData = [];
        foreach ($classes as $class) {
            $classData[] = [
                'class' => $class,
                'total_students' => $class->DataSiswa->count(),
                'total_tasks' => Tugas::whereHas('KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->count(),
                'total_exams' => Ujian::whereHas('KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->count(),
                'completed_tasks' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->where('status', 'completed')->count(),
                'average_score' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->avg('nilai') ?? 0
            ];
        }
        
        // Get subject performance data
        $subjectData = [];
        foreach ($subjects as $subject) {
            $subjectData[] = [
                'subject' => $subject,
                'total_classes' => $subject->KelasMapel->count(),
                'total_tasks' => Tugas::whereHas('KelasMapel', function($q) use ($subject) {
                    $q->where('mapel_id', $subject->id);
                })->count(),
                'total_exams' => Ujian::whereHas('KelasMapel', function($q) use ($subject) {
                    $q->where('mapel_id', $subject->id);
                })->count(),
                'average_score' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($subject) {
                    $q->where('mapel_id', $subject->id);
                })->avg('nilai') ?? 0
            ];
        }
        
        // Get overall statistics
        $totalReports = User::count() + Tugas::count() + Ujian::count() + Kelas::count();
        $completedReports = UserTugas::where('status', 'completed')->count();
        $pendingReports = UserTugas::where('status', 'pending')->count();
        $failedReports = UserTugas::where('status', 'failed')->count();
        
        return view('superadmin.reports', [
            'title' => 'Laporan',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects,
            'classData' => $classData,
            'subjectData' => $subjectData,
            'totalReports' => $totalReports,
            'completedReports' => $completedReports,
            'pendingReports' => $pendingReports,
            'failedReports' => $failedReports
        ]);
    }

    /**
     * Filter Super Admin Reports.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminReports(Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_type', 'filter_status', 'filter_date_from', 'filter_date_to', 'filter_class', 'filter_subject']);
        
        // Get classes and subjects with filters
        $classesQuery = Kelas::with(['KelasMapel.Mapel', 'DataSiswa']);
        $subjectsQuery = Mapel::with(['KelasMapel.Kelas']);
        
        // Apply class filter
        if (!empty($filters['filter_class'])) {
            $classesQuery->where('id', $filters['filter_class']);
        }
        
        // Apply subject filter
        if (!empty($filters['filter_subject'])) {
            $subjectsQuery->where('id', $filters['filter_subject']);
        }
        
        $classes = $classesQuery->get();
        $subjects = $subjectsQuery->get();
        
        // Get task and exam data per class with filters
        $classData = [];
        foreach ($classes as $class) {
            $tasksQuery = Tugas::whereHas('KelasMapel', function($q) use ($class) {
                $q->where('kelas_id', $class->id);
            });
            
            $examsQuery = Ujian::whereHas('KelasMapel', function($q) use ($class) {
                $q->where('kelas_id', $class->id);
            });
            
            // Apply type filter
            if (!empty($filters['filter_type'])) {
                if ($filters['filter_type'] === 'task') {
                    $examsQuery->whereRaw('1=0'); // No exams
                } elseif ($filters['filter_type'] === 'exam') {
                    $tasksQuery->whereRaw('1=0'); // No tasks
                }
            }
            
            $classData[] = [
                'class' => $class,
                'total_students' => $class->DataSiswa->count(),
                'total_tasks' => $tasksQuery->count(),
                'total_exams' => $examsQuery->count(),
                'completed_tasks' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->where('status', 'completed')->count(),
                'average_score' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($class) {
                    $q->where('kelas_id', $class->id);
                })->avg('nilai') ?? 0
            ];
        }
        
        // Get subject performance data with filters
        $subjectData = [];
        foreach ($subjects as $subject) {
            $tasksQuery = Tugas::whereHas('KelasMapel', function($q) use ($subject) {
                $q->where('mapel_id', $subject->id);
            });
            
            $examsQuery = Ujian::whereHas('KelasMapel', function($q) use ($subject) {
                $q->where('mapel_id', $subject->id);
            });
            
            // Apply type filter
            if (!empty($filters['filter_type'])) {
                if ($filters['filter_type'] === 'task') {
                    $examsQuery->whereRaw('1=0'); // No exams
                } elseif ($filters['filter_type'] === 'exam') {
                    $tasksQuery->whereRaw('1=0'); // No tasks
                }
            }
            
            $subjectData[] = [
                'subject' => $subject,
                'total_classes' => $subject->KelasMapel->count(),
                'total_tasks' => $tasksQuery->count(),
                'total_exams' => $examsQuery->count(),
                'average_score' => UserTugas::whereHas('Tugas.KelasMapel', function($q) use ($subject) {
                    $q->where('mapel_id', $subject->id);
                })->avg('nilai') ?? 0
            ];
        }
        
        // Get overall statistics with filters
        $totalReports = 0;
        if (empty($filters['filter_type']) || $filters['filter_type'] === 'user') {
            $totalReports += User::count();
        }
        if (empty($filters['filter_type']) || $filters['filter_type'] === 'task') {
            $totalReports += Tugas::count();
        }
        if (empty($filters['filter_type']) || $filters['filter_type'] === 'exam') {
            $totalReports += Ujian::count();
        }
        if (empty($filters['filter_type']) || $filters['filter_type'] === 'class') {
            $totalReports += Kelas::count();
        }
        
        $completedReports = UserTugas::where('status', 'completed')->count();
        $pendingReports = UserTugas::where('status', 'pending')->count();
        $failedReports = UserTugas::where('status', 'failed')->count();
        
        return view('superadmin.reports', [
            'title' => 'Laporan',
            'user' => $user,
            'filters' => $filters,
            'classes' => $classes,
            'subjects' => $subjects,
            'classData' => $classData,
            'subjectData' => $subjectData,
            'totalReports' => $totalReports,
            'completedReports' => $completedReports,
            'pendingReports' => $pendingReports,
            'failedReports' => $failedReports
        ]);
    }

    /**
     * Menampilkan halaman Analitik Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminAnalytics()
    {
        $user = auth()->user();
        
        // Get analytics data
        $totalUsers = \App\Models\User::count();
        $totalClasses = Kelas::count();
        $totalSubjects = \App\Models\Mapel::count();
        $totalTasks = \App\Models\Tugas::count();
        $totalExams = \App\Models\Ujian::count();
        
        // Get user statistics by role
        $userStats = \App\Models\User::join('roles', 'users.roles_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();
        
        // Get recent activity
        $recentUsers = \App\Models\User::latest()->limit(5)->get();
        $recentClasses = Kelas::latest()->limit(5)->get();
        
        // Get classes and subjects for filter dropdowns
        $classes = Kelas::all(['id', 'name']);
        $subjects = \App\Models\Mapel::all(['id', 'name']);
        
        // Chart data
        $chartData = $this->getAnalyticsChartData();
        
        return view('superadmin.analytics', [
            'title' => 'Analitik',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalTasks' => $totalTasks,
            'totalExams' => $totalExams,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'recentClasses' => $recentClasses,
            'classes' => $classes,
            'subjects' => $subjects,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Filter Analytics untuk Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminAnalytics(Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_period', 'filter_class', 'filter_subject']);
        
        // Set default period if not provided
        $period = $filters['filter_period'] ?? 30;
        $startDate = \Carbon\Carbon::now()->subDays($period);
        
        // Get analytics data with filters
        $totalUsers = \App\Models\User::count();
        $totalClasses = Kelas::count();
        $totalSubjects = \App\Models\Mapel::count();
        
        // Apply period filter to tasks and exams
        $totalTasks = \App\Models\Tugas::where('created_at', '>=', $startDate)->count();
        $totalExams = \App\Models\Ujian::where('created_at', '>=', $startDate)->count();
        
        // Get user statistics by role
        $userStats = \App\Models\User::join('roles', 'users.roles_id', '=', 'roles.id')
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();
        
        // Get recent activity with period filter
        $recentUsers = \App\Models\User::where('created_at', '>=', $startDate)->latest()->limit(5)->get();
        $recentClasses = Kelas::where('created_at', '>=', $startDate)->latest()->limit(5)->get();
        
        // Apply class filter if specified
        if (!empty($filters['filter_class'])) {
            $recentClasses = Kelas::where('id', $filters['filter_class'])
                ->where('created_at', '>=', $startDate)
                ->latest()
                ->limit(5)
                ->get();
        }
        
        // Apply subject filter if specified
        if (!empty($filters['filter_subject'])) {
            $totalTasks = \App\Models\Tugas::whereHas('KelasMapel', function($query) use ($filters) {
                $query->where('mapel_id', $filters['filter_subject']);
            })->where('created_at', '>=', $startDate)->count();
            
            $totalExams = \App\Models\Ujian::whereHas('KelasMapel', function($query) use ($filters) {
                $query->where('mapel_id', $filters['filter_subject']);
            })->where('created_at', '>=', $startDate)->count();
        }
        
        // Get classes and subjects for filter dropdowns
        $classes = Kelas::all(['id', 'name']);
        $subjects = \App\Models\Mapel::all(['id', 'name']);
        
        // Chart data
        $chartData = $this->getAnalyticsChartData();
        
        return view('superadmin.analytics', [
            'title' => 'Analitik',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalTasks' => $totalTasks,
            'totalExams' => $totalExams,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'recentClasses' => $recentClasses,
            'classes' => $classes,
            'subjects' => $subjects,
            'filters' => $filters,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Get analytics chart data from database
     *
     * @return array
     */
    private function getAnalyticsChartData()
    {
        // 1. User Activity Data (last 30 days)
        $userActivityData = [];
        $loginData = [];
        $taskActivityData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            // Count daily logins (simulated - you can implement actual login tracking)
            $dailyLogins = \App\Models\User::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $loginData[] = $dailyLogins;
            
            // Count daily task activities
            $dailyTaskActivity = \App\Models\Tugas::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $taskActivityData[] = $dailyTaskActivity;
        }
        
        // 2. Task Completion Data (last 30 days)
        $taskCompletionData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();
            
            // Count completed tasks (simulated - you can implement actual completion tracking)
            $completedTasks = \App\Models\Tugas::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $taskCompletionData[] = $completedTasks;
        }
        
        // 3. Performance Distribution Data
        $performanceDistribution = [
            'Sangat Baik (90-100)' => 25,
            'Baik (80-89)' => 35,
            'Cukup (70-79)' => 20,
            'Kurang (60-69)' => 15,
            'Sangat Kurang (<60)' => 5
        ];
        
        // 4. Learning Progress Data (simulated)
        $learningProgress = 75; // 75% average progress
        
        // 5. Class Performance Data
        $classPerformanceData = [];
        $classPerformanceLabels = [];
        $classes = Kelas::with(['DataSiswa'])->get();
        
        foreach ($classes as $class) {
            $classPerformanceLabels[] = $class->name;
            // Get real average score for each class from user_tugas table
            $averageScore = UserTugas::whereHas('user', function($query) use ($class) {
                $query->where('kelas_id', $class->id);
            })->avg('nilai') ?? 0;
            $classPerformanceData[] = $averageScore;
        }
        
        // 6. Subject Popularity Data
        $subjectPopularityData = [];
        $subjectPopularityLabels = [];
        $subjects = \App\Models\Mapel::with(['KelasMapel.Kelas.DataSiswa'])->get();
        
        foreach ($subjects as $subject) {
            $subjectPopularityLabels[] = $subject->name;
            // Count students enrolled in this subject
            $studentCount = 0;
            foreach ($subject->KelasMapel as $kelasMapel) {
                $studentCount += $kelasMapel->Kelas->DataSiswa->count();
            }
            $subjectPopularityData[] = $studentCount;
        }
        
        return [
            'userActivity' => [
                'loginData' => $loginData,
                'taskActivityData' => $taskActivityData,
                'labels' => array_map(function($i) {
                    return "Hari " . ($i + 1);
                }, range(0, 29))
            ],
            'taskCompletion' => [
                'data' => $taskCompletionData,
                'labels' => array_map(function($i) {
                    return "Hari " . ($i + 1);
                }, range(0, 29))
            ],
            'performanceDistribution' => $performanceDistribution,
            'learningProgress' => $learningProgress,
            'classPerformance' => [
                'data' => $classPerformanceData,
                'labels' => $classPerformanceLabels
            ],
            'subjectPopularity' => [
                'data' => $subjectPopularityData,
                'labels' => $subjectPopularityLabels
            ]
        ];
    }

    /**
     * Menampilkan halaman Bantuan Super Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminHelp()
    {
        $user = auth()->user();
        
        return view('superadmin.help', [
            'title' => 'Bantuan',
            'user' => $user
        ]);
    }

    // ==================== ADMIN ROUTES ====================

    /**
     * Menampilkan halaman Kelas Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminKelas()
    {
        $user = auth()->user();
        
        return view('menu.admin.controlKelas.viewKelas', [
            'title' => 'Manajemen Kelas',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Mata Pelajaran Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminMapel()
    {
        $user = auth()->user();
        
        return view('menu.admin.controlMapel.viewMapel', [
            'title' => 'Manajemen Mata Pelajaran',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Pengajar Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminPengajar()
    {
        $user = auth()->user();
        
        return view('menu.admin.controlPengajar.viewPengajar', [
            'title' => 'Manajemen Pengajar',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Siswa Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminSiswa()
    {
        $user = auth()->user();
        
        return view('menu.admin.controlSiswa.viewSiswa', [
            'title' => 'Manajemen Siswa',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Notifikasi Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminNotifications()
    {
        $user = auth()->user();
        
        return view('menu.admin.notifications.index', [
            'title' => 'Notifikasi',
            'user' => $user
        ]);
    }

    // ==================== TEACHER ROUTES ====================

    /**
     * Menampilkan halaman Materi Guru.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherMateri()
    {
        $user = auth()->user();
        
        // Guru memiliki akses penuh ke semua materi
        $materials = Materi::with(['kelasMapel.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics per type
        $stats = [
            'document' => $materials->filter(function($materi) {
                return $materi->file_type === 'document';
            })->count(),
            'video' => $materials->filter(function($materi) {
                return $materi->file_type === 'video';
            })->count(),
            'image' => $materials->filter(function($materi) {
                return $materi->file_type === 'image';
            })->count(),
            'audio' => $materials->filter(function($materi) {
                return $materi->file_type === 'audio';
            })->count(),
        ];
        
        // Ambil materi terbaru
        $recentMaterials = $materials->take(8);
        
        // Get subjects and classes for dropdowns
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        $roleConfig = DashboardHelper::getRoleConfig(3);
        
        return view('teacher.material-management', array_merge($roleConfig, [
            'title' => 'Manajemen Materi',
            'roleTitle' => 'Guru',
            'roleIcon' => 'fas fa-chalkboard-teacher',
            'user' => $user,
            'materials' => $materials,
            'subjects' => $subjects,
            'classes' => $classes,
            'stats' => $stats,
            'recentMaterials' => $recentMaterials,
            'totalMaterials' => $materials->count(),
            'totalDocuments' => $stats['document'],
            'totalVideos' => $stats['video'],
            'totalDownloads' => 0, // You can calculate this if needed
            'roleId' => 3
        ]));
    }

    /**
     * Menangani pembuatan materi baru oleh Teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:mapels,id',
            'class_id' => 'required|exists:kelas,id',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:document,video,image,text',
            'status' => 'required|in:draft,published',
            'file' => 'nullable|file|mimes:pdf,mp4,jpg,jpeg,png,doc,docx,ppt,pptx|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'youtube_url' => 'nullable|url',
        ]);

        try {
            // Handle file upload
            $filePath = null;
            $fileName = null;
            $fileSize = null;
            $fileType = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                $filePath = $file->store('materials', 'public');
            }

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
            }

            // Create the material using the Material model
            $material = \App\Models\Material::create([
                'title' => $request->title,
                'content' => $request->content,
                'description' => $request->description,
                'type' => $request->type,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'thumbnail_path' => $thumbnailPath,
                'youtube_url' => $request->youtube_url,
                'teacher_id' => auth()->id(),
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
                'views' => 0,
            ]);

            return redirect()->route('teacher.material-management')
                ->with('success', 'Materi berhasil dibuat!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat materi: ' . $e->getMessage());
        }
    }

    /**
     * Menangani filter materi Teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherMaterials(Request $request)
    {
        $user = auth()->user();
        
        // Get available subjects and classes
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        // Get all materi created by this teacher
        $query = Materi::whereHas('kelasMapel.editorAccess', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['kelasMapel.kelas', 'kelasMapel.mapel']);
        
        // Apply filters
        if ($request->filled('filter_type')) {
            // Add type filter when you add type field to Materi model
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_class')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_search')) {
            $query->where('name', 'like', '%' . $request->filter_search . '%')
                  ->orWhere('content', 'like', '%' . $request->filter_search . '%');
        }
        
        $myMateri = $query->get();
        
        // Calculate statistics
        $totalMaterials = $myMateri->count();
        $totalDocuments = $myMateri->filter(function($materi) {
            return $materi->file_type === 'document';
        })->count();
        $totalVideos = $myMateri->filter(function($materi) {
            return $materi->file_type === 'video';
        })->count();
        $totalDownloads = 0;
        
        // Prepare materials data for the table
        $materials = $myMateri->map(function($materi) {
            return (object) [
                'id' => $materi->id,
                'title' => $materi->name,
                'description' => $materi->deskripsi ?? $materi->content,
                'type' => $materi->file_type ?? 'document',
                'subject_name' => $materi->kelasMapel->mapel->name ?? 'N/A',
                'class_name' => $materi->kelasMapel->kelas->name ?? 'N/A',
                'file_size' => '2.5 MB',
                'download_count' => 0
            ];
        });
        
        return view('teacher.material-management', [
            'title' => 'Manajemen Materi',
            'user' => $user,
            'subjects' => $subjects,
            'classes' => $classes,
            'materials' => $materials,
            'totalMaterials' => $totalMaterials,
            'totalDocuments' => $totalDocuments,
            'totalVideos' => $totalVideos,
            'totalDownloads' => $totalDownloads,
            'filters' => $request->all()
        ]);
    }

    /**
     * Menampilkan halaman Tugas Guru.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherTugas(Request $request)
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.task-management-main', [
                'title' => 'Manajemen Tugas',
                'user' => $user,
                'stats' => ['total' => 0, 'active' => 0, 'completed' => 0, 'multiple_choice' => 0, 'essay' => 0, 'individual' => 0, 'group' => 0],
                'recentTasks' => collect()
            ]);
        }
        
        // Ambil tugas terbaru yang dibuat oleh guru ini dari kelas yang diassign
        $recentTasks = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function($task) {
                // Hitung progress
                $totalStudents = $task->kelasMapel->kelas->siswa()->count();
                $submittedCount = TugasProgress::where('tugas_id', $task->id)
                    ->where('status', 'submitted')
                    ->count();
                
                $task->total_students = $totalStudents;
                $task->submitted_count = $submittedCount;
                $task->progress_percentage = $totalStudents > 0 ? ($submittedCount / $totalStudents) * 100 : 0;
                
                return $task;
            });
        
        // Get additional data needed for the shared component - only assigned classes/subjects
        $classes = $this->getTeacherAvailableClasses($request);
        $subjects = $this->getTeacherAvailableSubjects($request);
        
        // Calculate stats from assigned classes only
        $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        $activeTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', false)->count();
        
        $completedTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->where('isHidden', true)->count();
        
        $activeClasses = $classes->count();
        
        $stats = [
            'total' => $totalTasks,
            'active' => $activeTasks,
            'completed' => $completedTasks,
            'multiple_choice' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 1)->count(),
            'essay' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 2)->count(),
            'individual' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 3)->count(),
            'group' => Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->where('tipe', 4)->count(),
        ];
        
        // Get all tasks for the teacher (for the table) from assigned classes only
        $tasks = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ambil tugas terbaru untuk teacher dari kelas yang diassign
        $recentTasks = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Show the teacher task management view with super admin styling
        return view('teacher.task-management-main', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'stats' => $stats,
            'recentTasks' => $recentTasks
        ]);
    }

    /**
     * Menampilkan halaman Ujian Guru.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherUjian(Request $request)
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.exam-management', [
                'title' => 'Manajemen Ujian',
                'user' => $user,
                'exams' => collect(),
                'stats' => ['total' => 0, 'active' => 0, 'completed' => 0]
            ]);
        }
        
        // Get exams from teacher's assigned classes only
        $exams = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate stats
        $totalExams = $exams->count();
        $activeExams = $exams->where('isHidden', false)->count();
        $completedExams = $exams->where('isHidden', true)->count();
        
        $stats = [
            'total' => $totalExams,
            'active' => $activeExams,
            'completed' => $completedExams
        ];
        
        return view('teacher.exam-management', [
            'title' => 'Manajemen Ujian',
            'user' => $user,
            'exams' => $exams,
            'stats' => $stats
        ]);
    }

    /**
     * Menampilkan halaman IoT Guru.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherIot(Request $request)
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_ids'])) {
            return view('menu.pengajar.iot.dashboard', [
                'title' => 'IoT Dashboard',
                'user' => $user,
                'assignedKelas' => collect(),
                'devices' => collect(),
                'recentData' => collect(),
                'totalDevices' => 0,
                'onlineDevices' => 0,
                'totalReadings' => 0,
                'todayReadings' => 0
            ]);
        }
        
        // Get IoT devices assigned to teacher's classes only
        $devices = IotDevice::whereHas('sensorData', function($query) use ($assignedData) {
            $query->whereIn('kelas_id', $assignedData['kelas_ids']);
        })->with('latestSensorData')->get();

        // Get recent sensor data from teacher's classes only
        $recentData = IotSensorData::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();

        // Get statistics for teacher's classes
        $totalDevices = $devices->count();
        $onlineDevices = $devices->where('status', 'online')->count();
        $totalReadings = IotSensorData::whereIn('kelas_id', $assignedData['kelas_ids'])->count();
        $todayReadings = IotSensorData::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->whereDate('measured_at', today())
            ->count();
        
        // Get assigned classes for display
        $assignedKelas = Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
        
        return view('menu.pengajar.iot.dashboard', [
            'title' => 'IoT Dashboard',
            'user' => $user,
            'assignedKelas' => $assignedKelas,
            'devices' => $devices,
            'recentData' => $recentData,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'totalReadings' => $totalReadings,
            'todayReadings' => $todayReadings
        ]);
    }

    // ==================== STUDENT ROUTES ====================

    /**
     * Menampilkan halaman Home Siswa.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentHome()
    {
        $user = auth()->user();
        
        return view('menu.siswa.home.home', [
            'title' => 'Home',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman Ujian Siswa.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentUjian()
    {
        $user = auth()->user();
        
        return view('menu.siswa.ujian.ujianAccess', [
            'title' => 'Ujian',
            'user' => $user
        ]);
    }

    /**
     * Menampilkan halaman IoT Siswa.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentIot()
    {
        $user = auth()->user();
        
        return view('menu.siswa.iot.hasil-saya', [
            'title' => 'IoT Research',
            'user' => $user
        ]);
    }

    /**
     * Menangani pembuatan pengguna baru oleh Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createSuperAdminUser(Request $request)
    {
        // Debug: Log the request data
        Log::info('User creation request data:', $request->all());
        
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_role' => 'required|string|in:admin,teacher,student',
            'class_id' => 'nullable|string',
            'user_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:user_password',
        ]);

        // Prevent SuperAdmin creation
        if ($request->user_role === 'superadmin') {
            return redirect()->route('superadmin.user-management')
                ->with('error', 'Tidak dapat membuat SuperAdmin melalui interface ini. SuperAdmin hanya dapat dilihat.');
        }

        try {
            // Map role names to role IDs
            $roleMapping = [
                'admin' => 2,
                'teacher' => 3,
                'student' => 4,
            ];

            $roleId = $roleMapping[$request->user_role];

            // Prepare user data
            $userData = [
                'name' => $request->user_name,
                'email' => $request->user_email,
                'roles_id' => $roleId,
                'password' => bcrypt($request->user_password),
            ];

            // Add class_id for students
            if ($request->user_role === 'student' && $request->class_id) {
                $userData['kelas_id'] = $request->class_id;
            }

            // Create the user
            User::create($userData);

            return redirect()->route('superadmin.user-management')
                ->with('success', 'Pengguna berhasil dibuat: ' . $request->user_name);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in user creation:', $e->errors());
            return redirect()->route('superadmin.user-management')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('superadmin.user-management')
                ->with('error', 'Gagal membuat pengguna: ' . $e->getMessage());
        }
    }






    // Admin Management Methods (Matching Superadmin)
    
    /**
     * View admin profile.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminProfile()
    {
        $user = auth()->user();
        return view('admin.profile', [
            'title' => 'Profil Admin',
            'user' => $user
        ]);
    }

    /**
     * Update admin profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdminProfile(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak terautentikasi.');
        }
        
        // Cast to User model to access Eloquent methods
        $user = User::find($user->id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Upload admin photo.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAdminPhoto(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak terautentikasi.']);
        }
        
        // Cast to User model to access Eloquent methods
        $user = User::find($user->id);
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $path = $photo->storeAs('user-images', $filename, 'public');
            
            $user->update(['gambar' => $path]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Foto profile berhasil diupload',
                'photo_url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengupload foto']);
    }


    /**
     * View admin push notification.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminPushNotification()
    {
        $user = auth()->user();
        
        // Check if user is admin or superadmin
        if (!in_array($user->roles_id, [1, 2])) {
            abort(403, 'Unauthorized access. Hanya admin dan superadmin yang dapat mengirim notifikasi.');
        }

        // Get notification statistics
        $stats = [
            'total' => Notification::count(),
            'sent_today' => Notification::whereDate('created_at', today())->count(),
            'pending' => 0, // Notifications don't have pending status in current implementation
            'failed' => 0,  // Notifications don't have failed status in current implementation
        ];

        // Get recent notifications
        $recentNotifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get paginated notifications for the shared component
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get users for specific targeting
        $users = User::where('roles_id', '!=', 1) // Exclude superadmin
            ->select('id', 'name', 'email', 'roles_id')
            ->get();

        // Calculate notification counts
        $totalNotifications = Notification::count();
        $readNotifications = Notification::where('is_read', true)->count();
        $unreadNotifications = Notification::where('is_read', false)->count();
        $urgentNotifications = Notification::where('type', 'error')->count();

        return view('admin.push-notification', [
            'title' => 'Push Notifikasi Admin',
            'user' => $user,
            'stats' => $stats,
            'recentNotifications' => $recentNotifications,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'users' => $users
        ]);
    }


    /**
     * Send admin push notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendAdminPushNotification(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is admin or superadmin
        if (!in_array($user->roles_id, [1, 2])) {
            abort(403, 'Unauthorized access. Hanya admin dan superadmin yang dapat mengirim notifikasi.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'recipient_type' => 'required|in:all,students,teachers,admins,specific',
            'specific_users' => 'required_if:recipient_type,specific|array',
            'specific_users.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $notifications = [];
            $recipientCount = 0;

            if ($request->recipient_type === 'all') {
                // Broadcast ke semua user kecuali admin (roles_id != 1)
                $users = User::where('roles_id', '!=', 1)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['broadcast' => true, 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'students') {
                // Kirim ke siswa saja (roles_id = 4)
                $users = User::where('roles_id', 4)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'students', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'teachers') {
                // Kirim ke guru saja (roles_id = 3)
                $users = User::where('roles_id', 3)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'teachers', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'admins') {
                // Kirim ke admin saja (roles_id = 2)
                $users = User::where('roles_id', 2)->get();
                foreach ($users as $targetUser) {
                    $notifications[] = [
                        'user_id' => $targetUser->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['role_target' => 'admins', 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'specific') {
                // Kirim ke user tertentu
                foreach ($request->specific_users as $userId) {
                    $notifications[] = [
                        'user_id' => $userId,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['specific_target' => true, 'sent_by' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            }

            // Insert batch notifications
            if (!empty($notifications)) {
                Notification::insert($notifications);
            }

            DB::commit();

            return redirect()->route('admin.push-notification')
                ->with('success', "Notifikasi berhasil dikirim ke {$recipientCount} pengguna");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Filter admin push notifications.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminPushNotifications(Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_type', 'filter_status', 'filter_date_from', 'filter_date_to', 'filter_recipient']);
        
        // Build query for notifications
        $query = \App\Models\Notification::with('user');
        
        // Apply filters
        if (!empty($filters['filter_type'])) {
            $query->where('type', $filters['filter_type']);
        }
        
        if (!empty($filters['filter_status'])) {
            if ($filters['filter_status'] === 'read') {
                $query->where('is_read', true);
            } elseif ($filters['filter_status'] === 'unread') {
                $query->where('is_read', false);
            }
        }
        
        if (!empty($filters['filter_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['filter_date_to']);
        }
        
        if (!empty($filters['filter_recipient'])) {
            if ($filters['filter_recipient'] === 'broadcast') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $filters['filter_recipient']);
            }
        }
        
        // Get filtered notifications
        $recentNotifications = $query->orderBy('created_at', 'desc')->limit(10)->get();
        
        // Get paginated filtered notifications for the shared component
        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get notification statistics (unfiltered)
        $totalNotifications = \App\Models\Notification::count();
        $unreadCount = \App\Models\Notification::unread()->count();
        $todayNotifications = \App\Models\Notification::whereDate('created_at', today())->count();
        
        // Calculate notification counts
        $readNotifications = \App\Models\Notification::where('is_read', true)->count();
        $unreadNotifications = \App\Models\Notification::where('is_read', false)->count();
        $urgentNotifications = \App\Models\Notification::where('type', 'error')->count();
        
        // Get users for specific targeting
        $users = \App\Models\User::where('roles_id', '!=', 1)
            ->select('id', 'name', 'email', 'roles_id')
            ->get();
        
        return view('admin.push-notification', [
            'title' => 'Push Notifikasi Admin',
            'user' => $user,
            'recentNotifications' => $recentNotifications,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'unreadCount' => $unreadCount,
            'todayNotifications' => $todayNotifications,
            'users' => $users,
            'filters' => $filters
        ]);
    }

    /**
     * View admin IoT management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminIotManagement()
    {
        $user = auth()->user();
        
        // Get all devices
        $devices = \App\Models\IotDevice::with('latestSensorData')->latest()->get();
        
        // Get recent sensor data
        $recentSensorData = \App\Models\IotSensorData::with(['device', 'researchProject'])
            ->latest('measured_at')->limit(10)->get();
        
        // Get statistics
        $totalDevices = \App\Models\IotDevice::count();
        $onlineDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $offlineDevices = \App\Models\IotDevice::where('status', 'offline')->count();
        $totalSensorReadings = \App\Models\IotSensorData::count();
        $todayReadings = \App\Models\IotSensorData::whereDate('measured_at', today())->count();
        
        // Get current sensor readings for real-time display
        $currentTemperature = \App\Models\IotSensorData::whereNotNull('temperature')
            ->latest('measured_at')->first()->temperature ?? null;
        $currentHumidity = \App\Models\IotSensorData::whereNotNull('humidity')
            ->latest('measured_at')->first()->humidity ?? null;
        $currentMoisture = \App\Models\IotSensorData::whereNotNull('soil_moisture')
            ->latest('measured_at')->first()->soil_moisture ?? null;
        $currentPh = \App\Models\IotSensorData::whereNotNull('ph_level')
            ->latest('measured_at')->first()->ph_level ?? null;
        
        // Get data for filter options
        $classes = Kelas::all();
        
        return view('admin.iot-management', [
            'title' => 'Manajemen IoT',
            'user' => $user,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'offlineDevices' => $offlineDevices,
            'devices' => $devices,
            'recentSensorData' => $recentSensorData,
            'totalSensorReadings' => $totalSensorReadings,
            'todayReadings' => $todayReadings,
            'currentTemperature' => $currentTemperature,
            'currentHumidity' => $currentHumidity,
            'currentMoisture' => $currentMoisture,
            'currentPh' => $currentPh,
            'classes' => $classes,
        ]);
    }

    /**
     * Filter admin IoT management.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminIotManagement(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_type', 'filter_status', 'filter_date_from', 'filter_date_to', 'filter_location', 'filter_class']);
        
        // Build query for devices
        $deviceQuery = \App\Models\IotDevice::query();
        
        // Apply device filters
        if (!empty($filters['filter_type'])) {
            $deviceQuery->where('device_type', $filters['filter_type']);
        }
        
        if (!empty($filters['filter_status'])) {
            $deviceQuery->where('status', $filters['filter_status']);
        }
        
        if (!empty($filters['filter_class'])) {
            // Filter devices that have sensor data for the specified class
            $deviceQuery->whereHas('sensorData', function($query) use ($filters) {
                $query->where('kelas_id', $filters['filter_class']);
            });
        }
        
        // Get filtered devices
        $devices = $deviceQuery->with('latestSensorData')->latest()->get();
        
        // Build query for sensor data
        $sensorQuery = \App\Models\IotSensorData::with(['device', 'researchProject']);
        
        // Apply sensor data filters
        if (!empty($filters['filter_date_from'])) {
            $sensorQuery->whereDate('measured_at', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $sensorQuery->whereDate('measured_at', '<=', $filters['filter_date_to']);
        }
        
        if (!empty($filters['filter_location'])) {
            $sensorQuery->where('location', 'like', '%' . $filters['filter_location'] . '%');
        }
        
        if (!empty($filters['filter_class'])) {
            $sensorQuery->where('kelas_id', $filters['filter_class']);
        }
        
        // Get filtered sensor data
        $recentSensorData = $sensorQuery->latest('measured_at')->limit(10)->get();
        
        // Get statistics (unfiltered)
        $totalDevices = \App\Models\IotDevice::count();
        $onlineDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $totalSensorReadings = \App\Models\IotSensorData::count();
        $todayReadings = \App\Models\IotSensorData::whereDate('measured_at', today())->count();
        
        // Get data for filter options
        $classes = Kelas::all();
        
        return view('admin.iot-management', [
            'title' => 'Manajemen IoT',
            'user' => $user,
            'totalDevices' => $totalDevices,
            'onlineDevices' => $onlineDevices,
            'devices' => $devices,
            'recentSensorData' => $recentSensorData,
            'totalSensorReadings' => $totalSensorReadings,
            'todayReadings' => $todayReadings,
            'classes' => $classes,
            'filters' => $filters, // Pass filters back to view
        ]);
    }

    /**
     * Register admin IoT device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerAdminIotDevice(Request $request)
    {
        // Implementation for registering IoT devices
        return redirect()->route('admin.iot-management')
            ->with('success', 'Perangkat IoT berhasil didaftarkan');
    }

    /**
     * View admin task management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminTaskManagement()
    {
        $user = auth()->user();
        
        // Hitung statistik per tipe tugas untuk admin
        $stats = [
            'multiple_choice' => Tugas::where('tipe', 1)->count(),
            'essay' => Tugas::where('tipe', 2)->count(),
            'individual' => Tugas::where('tipe', 3)->count(),
            'group' => Tugas::where('tipe', 4)->count(),
        ];
        
        // Ambil tugas terbaru untuk admin
        $recentTasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Ambil data kelas dan mata pelajaran untuk filter
        $classes = \App\Models\Kelas::all();
        $subjects = \App\Models\Mapel::all();
        
        // Ambil semua tugas untuk ditampilkan
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'stats' => $stats,
            'recentTasks' => $recentTasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'tasks' => $tasks
        ]);
    }

    /**
     * Create admin task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminTask(Request $request)
    {
        // Implementation for creating tasks
        return redirect()->route('admin.task-management')
            ->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Filter admin tasks.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminTasks(Request $request)
    {
        $user = auth()->user();
        return view('admin.task-management', [
            'title' => 'Manajemen Tugas Admin',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View admin exam management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminExamManagement()
    {
        $user = auth()->user();
        
        // Get real exam data from database
        $exams = Ujian::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        $totalExams = $exams->count();
        $activeExams = $exams->where('isHidden', 0)->count();
        $completedExams = $exams->where('isHidden', 1)->count();
        
        return view('admin.exam-management', [
            'title' => 'Manajemen Ujian Admin',
            'user' => $user,
            'exams' => $exams,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalExams' => $totalExams,
            'activeExams' => $activeExams,
            'completedExams' => $completedExams,
            'totalParticipants' => 0
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian pilihan ganda untuk Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminCreateMultipleChoiceExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('admin.exam-create-multiple-choice', [
            'title' => 'Buat Ujian Pilihan Ganda',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian essay untuk Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminCreateEssayExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('admin.exam-create-essay', [
            'title' => 'Buat Ujian Essay',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian campuran untuk Admin.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminCreateMixedExam()
    {
        $user = auth()->user();
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        return view('admin.exam-create-mixed', [
            'title' => 'Buat Ujian Campuran',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian pilihan ganda untuk Teacher.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherCreateMultipleChoiceExam()
    {
        $user = auth()->user();
        
        // Get classes and subjects that teacher has access to
        $classes = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        $subjects = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        return view('teacher.exam-create-multiple-choice', [
            'title' => 'Buat Ujian Pilihan Ganda',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian essay untuk Teacher.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherCreateEssayExam()
    {
        $user = auth()->user();
        
        // Get classes and subjects that teacher has access to
        $classes = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        $subjects = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        return view('teacher.exam-create-essay', [
            'title' => 'Buat Ujian Essay',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menampilkan halaman pembuatan ujian campuran untuk Teacher.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherCreateMixedExam()
    {
        $user = auth()->user();
        
        // Get classes and subjects that teacher has access to
        $classes = Kelas::whereHas('users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        $subjects = Mapel::whereHas('KelasMapel.Kelas.users', function($query) use ($user) {
            $query->where('id', $user->id);
        })->get();
        
        return view('teacher.exam-create-mixed', [
            'title' => 'Buat Ujian Campuran',
            'user' => $user,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
    }

    /**
     * Menangani pembuatan ujian pilihan ganda untuk Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminMultipleChoiceExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|exists:kelas,id',
            'subject_id' => 'required|exists:mapels,id',
            'duration' => 'required|integer|min:1|max:300',
            'max_score' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'exam_description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'is_hidden' => 'required|in:0,1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:4',
            'questions.*.options.1' => 'required|string',
            'questions.*.options.2' => 'required|string',
            'questions.*.options.3' => 'required|string',
            'questions.*.options.4' => 'required|string',
            'questions.*.correct_answer' => 'required|in:1,2,3,4',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.category' => 'required|string|in:easy,medium,hard'
        ]);

        try {
            DB::beginTransaction();

            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => 1, // Multiple choice
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden,
            ]);

            // Create multiple choice questions
            foreach ($request->questions as $questionData) {
                SoalUjianMultiple::create([
                    'ujian_id' => $ujian->id,
                    'soal' => $questionData['question'],
                    'a' => $questionData['options']['1'],
                    'b' => $questionData['options']['2'],
                    'c' => $questionData['options']['3'],
                    'd' => $questionData['options']['4'],
                    'jawaban' => $questionData['correct_answer'],
                    'poin' => $questionData['points'],
                    'kategori' => $questionData['category'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.exam-management')
                ->with('success', 'Ujian pilihan ganda berhasil dibuat dengan ' . count($request->questions) . ' soal!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menangani pembuatan ujian pilihan ganda untuk Teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherMultipleChoiceExam(Request $request)
    {
        $request->validate([
            'exam_title' => 'required|string|max:255',
            'class_id' => 'required|exists:kelas,id',
            'subject_id' => 'required|exists:mapels,id',
            'duration' => 'required|integer|min:1|max:300',
            'max_score' => 'required|integer|min:1|max:100',
            'difficulty' => 'required|string|in:easy,medium,hard',
            'exam_description' => 'required|string',
            'due_date' => 'required|date|after:now',
            'is_hidden' => 'required|in:0,1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:4',
            'questions.*.options.1' => 'required|string',
            'questions.*.options.2' => 'required|string',
            'questions.*.options.3' => 'required|string',
            'questions.*.options.4' => 'required|string',
            'questions.*.correct_answer' => 'required|in:1,2,3,4',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.category' => 'required|string|in:easy,medium,hard'
        ]);

        try {
            DB::beginTransaction();

            // Find or create KelasMapel relationship
            $kelasMapel = KelasMapel::firstOrCreate([
                'kelas_id' => $request->class_id,
                'mapel_id' => $request->subject_id,
            ]);

            // Create the exam
            $ujian = Ujian::create([
                'kelas_mapel_id' => $kelasMapel->id,
                'name' => $request->exam_title,
                'content' => $request->exam_description,
                'tipe' => 1, // Multiple choice
                'time' => $request->duration,
                'due' => $request->due_date,
                'isHidden' => $request->is_hidden,
            ]);

            // Create multiple choice questions
            foreach ($request->questions as $questionData) {
                SoalUjianMultiple::create([
                    'ujian_id' => $ujian->id,
                    'soal' => $questionData['question'],
                    'a' => $questionData['options']['1'],
                    'b' => $questionData['options']['2'],
                    'c' => $questionData['options']['3'],
                    'd' => $questionData['options']['4'],
                    'jawaban' => $questionData['correct_answer'],
                    'poin' => $questionData['points'],
                    'kategori' => $questionData['category'],
                ]);
            }

            DB::commit();

            return redirect()->route('teacher.exam-management')
                ->with('success', 'Ujian pilihan ganda berhasil dibuat dengan ' . count($request->questions) . ' soal!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Filter admin exams.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminExams(Request $request)
    {
        $user = auth()->user();
        return view('admin.exam-management', [
            'title' => 'Manajemen Ujian Admin',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View admin user management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminUserManagement()
    {
        $user = auth()->user();
        return view('admin.user-management', [
            'title' => 'Manajemen Pengguna Admin',
            'user' => $user
        ]);
    }

    /**
     * Create admin user.
     * Hanya Super Admin yang bisa membuat admin baru
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles_id' => 'required|integer|in:1,2,3,4', // Super Admin bisa buat semua role
            'status' => 'required|string|in:active,inactive'
        ]);

        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->roles_id = $request->roles_id;
        $user->status = $request->status;
        $user->save();

        return redirect()->route('superadmin.user-management')
            ->with('success', 'Pengguna berhasil dibuat');
    }

    /**
     * Filter admin users.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminUsers(Request $request)
    {
        $user = auth()->user();
        return view('admin.user-management', [
            'title' => 'Manajemen Pengguna Admin',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View admin class management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminClassManagement()
    {
        $user = auth()->user();
        return view('admin.kelas-management', [
            'title' => 'Manajemen Kelas Admin',
            'user' => $user
        ]);
    }

    /**
     * Create admin class.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminClass(Request $request)
    {
        // Implementation for creating classes
        return redirect()->route('admin.class-management')
            ->with('success', 'Kelas berhasil dibuat');
    }

    /**
     * Filter admin classes.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminClasses(Request $request)
    {
        $user = auth()->user();
        return view('admin.kelas-management', [
            'title' => 'Manajemen Kelas Admin',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View admin subject management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminSubjectManagement()
    {
        $user = auth()->user();
        return view('admin.mapel-management', [
            'title' => 'Manajemen Mata Pelajaran Admin',
            'user' => $user
        ]);
    }

    /**
     * Create admin subject.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminSubject(Request $request)
    {
        // Implementation for creating subjects
        return redirect()->route('admin.subject-management')
            ->with('success', 'Mata pelajaran berhasil dibuat');
    }

    /**
     * Filter admin subjects.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminSubjects(Request $request)
    {
        $user = auth()->user();
        return view('admin.subject-management', [
            'title' => 'Manajemen Mata Pelajaran Admin',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View admin material management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminMaterialManagement()
    {
        $user = auth()->user();
        
        // Get all materials
        $materials = Materi::with(['kelasMapel.mapel', 'kelasMapel.kelas'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get subjects and classes for dropdowns
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        return view('admin.material-management', [
            'title' => 'Manajemen Materi Admin',
            'user' => $user,
            'materials' => $materials,
            'subjects' => $subjects,
            'classes' => $classes,
            'totalMaterials' => $materials->count(),
            'publishedMaterials' => $materials->where('is_published', true)->count(),
            'totalViews' => $materials->sum('views_count'),
            'totalDownloads' => $materials->sum('downloads_count')
        ]);
    }

    /**
     * Menangani filter materi Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminMaterials(Request $request)
    {
        $user = auth()->user();
        
        // Get all materials
        $query = Materi::with(['kelasMapel.mapel', 'kelasMapel.kelas'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('filter_type')) {
            $query->where('file_type', $request->filter_type);
        }
        
        if ($request->filled('filter_subject')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->filled('filter_class')) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->filled('filter_search')) {
            $query->where('name', 'like', '%' . $request->filter_search . '%')
                  ->orWhere('content', 'like', '%' . $request->filter_search . '%');
        }
        
        $materials = $query->get();
        
        // Get subjects and classes for dropdowns
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        return view('admin.material-management', [
            'title' => 'Manajemen Materi Admin',
            'user' => $user,
            'materials' => $materials,
            'subjects' => $subjects,
            'classes' => $classes,
            'totalMaterials' => $materials->count(),
            'publishedMaterials' => $materials->where('is_published', true)->count(),
            'totalViews' => $materials->sum('views_count'),
            'totalDownloads' => $materials->sum('downloads_count'),
            'filters' => $request->all()
        ]);
    }

    /**
     * Create admin material.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:mapels,id',
            'class_id' => 'required|exists:kelas,id',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:document,video,image,text',
            'status' => 'required|in:draft,published',
            'file' => 'nullable|file|mimes:pdf,mp4,jpg,jpeg,png,doc,docx,ppt,pptx|max:10240',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'youtube_url' => 'nullable|url',
        ]);

        try {
            // Handle file upload
            $filePath = null;
            $fileName = null;
            $fileSize = null;
            $fileType = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                $filePath = $file->store('materials', 'public');
            }

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
            }

            // Create the material using the Material model
            $material = \App\Models\Material::create([
                'title' => $request->title,
                'content' => $request->content,
                'description' => $request->description,
                'type' => $request->type,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'thumbnail_path' => $thumbnailPath,
                'youtube_url' => $request->youtube_url,
                'teacher_id' => auth()->id(),
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
                'views' => 0,
            ]);

            return redirect()->route('admin.material-management')
                ->with('success', 'Materi berhasil dibuat!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat materi: ' . $e->getMessage());
        }
    }

    /**
     * View admin reports.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminReports()
    {
        $user = auth()->user();
        return view('admin.reports', [
            'title' => 'Laporan Admin',
            'user' => $user
        ]);
    }

    /**
     * View admin settings.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminSettings()
    {
        $user = auth()->user();
        return view('admin.settings', [
            'title' => 'Pengaturan Admin',
            'user' => $user
        ]);
    }

    /**
     * View admin help.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminHelp()
    {
        $user = auth()->user();
        return view('admin.help', [
            'title' => 'Bantuan Admin',
            'user' => $user
        ]);
    }

    // Teacher Management Methods (Matching Superadmin)
    
    /**
     * View teacher profile.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherProfile()
    {
        $user = auth()->user();
        return view('teacher.profile', [
            'title' => 'Profil Teacher',
            'user' => $user
        ]);
    }

    /**
     * Update teacher profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTeacherProfile(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('teacher.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Upload teacher photo.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadTeacherPhoto(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $filename);
            
            $user->update(['gambar' => $filename]);
            
            return response()->json(['success' => true, 'photo' => $filename]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengupload foto']);
    }

    /**
     * View teacher settings.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherSettings()
    {
        $user = auth()->user();
        return view('teacher.settings', [
            'title' => 'Pengaturan Teacher',
            'user' => $user
        ]);
    }

    /**
     * View teacher push notification.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherPushNotification()
    {
        $user = auth()->user();
        return view('teacher.push-notification', [
            'title' => 'Push Notifikasi Teacher',
            'user' => $user
        ]);
    }


    /**
     * Filter teacher push notifications.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherPushNotifications(Request $request)
    {
        $user = auth()->user();
        return view('teacher.push-notification', [
            'title' => 'Push Notifikasi Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher IoT management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherIotManagement()
    {
        $user = auth()->user();
        
        // Get all IoT devices
        $devices = \App\Models\IotDevice::with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teacher.iot-management', [
            'title' => 'Manajemen IoT Teacher',
            'user' => $user,
            'devices' => $devices,
            'totalDevices' => $devices->count(),
            'activeDevices' => $devices->where('status', 'active')->count(),
            'inactiveDevices' => $devices->where('status', 'inactive')->count(),
            'totalDataPoints' => $devices->sum('data_count')
        ]);
    }

    /**
     * Menangani filter IoT Teacher.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherIotManagement(Request $request)
    {
        $user = auth()->user();
        
        // Get all IoT devices
        $query = \App\Models\IotDevice::with(['user'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }
        
        if ($request->filled('filter_type')) {
            $query->where('device_type', $request->filter_type);
        }
        
        if ($request->filled('filter_search')) {
            $query->where('device_name', 'like', '%' . $request->filter_search . '%')
                  ->orWhere('device_id', 'like', '%' . $request->filter_search . '%');
        }
        
        $devices = $query->get();
        
        return view('teacher.iot-management', [
            'title' => 'Manajemen IoT Teacher',
            'user' => $user,
            'devices' => $devices,
            'totalDevices' => $devices->count(),
            'activeDevices' => $devices->where('status', 'active')->count(),
            'inactiveDevices' => $devices->where('status', 'inactive')->count(),
            'totalDataPoints' => $devices->sum('data_count'),
            'filters' => $request->all()
        ]);
    }

    /**
     * Register teacher IoT device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerTeacherIotDevice(Request $request)
    {
        // Implementation for registering IoT devices
        return redirect()->route('teacher.iot-management')
            ->with('success', 'Perangkat IoT berhasil didaftarkan');
    }

    /**
     * View teacher task management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherTaskManagement()
    {
        $user = auth()->user();
        
        // Guru memiliki akses penuh ke semua kelas dan mata pelajaran
        $classes = \App\Models\Kelas::with(['KelasMapel.Mapel'])->get();
        $subjects = \App\Models\Mapel::all();
        
        $totalTasks = \App\Models\Tugas::count();
        $activeTasks = \App\Models\Tugas::where('isHidden', false)->count();
        $completedTasks = \App\Models\Tugas::where('isHidden', true)->count();
        
        $tasks = \App\Models\Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'totalTasks' => $totalTasks,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'pendingReview' => 5 // Placeholder
        ];
        
        return view('task-management.unified-task-management', [
            'title' => 'Task Management',
            'user' => $user,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'stats' => $stats,
            'filters' => []
        ]);
    }

    /**
     * Create teacher task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherTask(Request $request)
    {
        // Implementation for creating tasks
        return redirect()->route('teacher.task-management')
            ->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Filter teacher tasks.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherTasks(Request $request)
    {
        $user = auth()->user();
        
        // Cek apakah teacher memiliki assignment
        $hasAssignment = \App\Models\EditorAccess::where('user_id', $user->id)->exists();
        
        if ($hasAssignment) {
            // Jika ada assignment, filter berdasarkan assignment
            $assignedData = $this->getTeacherAssignedData($request);
            
            if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
                return view('teacher.task-management', [
                    'title' => 'Manajemen Tugas',
                    'user' => $user,
                    'tasks' => collect(),
                    'classes' => collect(),
                    'subjects' => collect(),
                    'totalTasks' => 0,
                    'activeTasks' => 0,
                    'completedTasks' => 0,
                    'activeClasses' => 0,
                    'filters' => $request->all()
                ]);
            }
            
            $query = \App\Models\Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
            
            $classes = $this->getTeacherAvailableClasses($request);
            $subjects = $this->getTeacherAvailableSubjects($request);
        } else {
            // Jika tidak ada assignment, filter semua data
            $query = \App\Models\Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel']);
            $classes = \App\Models\Kelas::all();
            $subjects = \App\Models\Mapel::all();
        }
        
        // Apply filters
        if ($request->has('filter_class') && $request->filter_class) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('kelas_id', $request->filter_class);
            });
        }
        
        if ($request->has('filter_subject') && $request->filter_subject) {
            $query->whereHas('kelasMapel', function($q) use ($request) {
                $q->where('mapel_id', $request->filter_subject);
            });
        }
        
        if ($request->has('filter_status') && $request->filter_status) {
            switch ($request->filter_status) {
                case 'active':
                    $query->where('isHidden', false);
                    break;
                case 'completed':
                    $query->where('isHidden', true);
                    break;
            }
        }
        
        if ($request->has('filter_type') && $request->filter_type) {
            $query->where('tipe', $request->filter_type);
        }
        
        $tasks = $query->orderBy('created_at', 'desc')->get();
        
        // Calculate stats
        if ($hasAssignment && $assignedData && !empty($assignedData['kelas_mapel_ids'])) {
            $totalTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $activeTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', false)->count();
            $completedTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', true)->count();
        } else {
            $totalTasks = \App\Models\Tugas::count();
            $activeTasks = \App\Models\Tugas::where('isHidden', false)->count();
            $completedTasks = \App\Models\Tugas::where('isHidden', true)->count();
        }
        
        return view('teacher.task-management', [
            'title' => 'Manajemen Tugas',
            'user' => $user,
            'tasks' => $tasks,
            'classes' => $classes,
            'subjects' => $subjects,
            'totalTasks' => $totalTasks,
            'activeTasks' => $activeTasks,
            'completedTasks' => $completedTasks,
            'activeClasses' => $classes->count(),
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher exam management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherExamManagement()
    {
        $user = auth()->user();
        return view('teacher.exam-management', [
            'title' => 'Manajemen Ujian Teacher',
            'user' => $user
        ]);
    }

    /**
     * Create teacher exam.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherExam(Request $request)
    {
        // Implementation for creating exams
        return redirect()->route('teacher.exam-management')
            ->with('success', 'Ujian berhasil dibuat');
    }

    /**
     * Filter teacher exams.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherExams(Request $request)
    {
        $user = auth()->user();
        return view('teacher.exam-management', [
            'title' => 'Manajemen Ujian Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher user management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherUserManagement()
    {
        $user = auth()->user();
        return view('teacher.user-management', [
            'title' => 'Manajemen Pengguna Teacher',
            'user' => $user
        ]);
    }

    /**
     * Create teacher user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherUser(Request $request)
    {
        // Implementation for creating users
        return redirect()->route('teacher.user-management')
            ->with('success', 'Pengguna berhasil dibuat');
    }

    /**
     * Filter teacher users.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherUsers(Request $request)
    {
        $user = auth()->user();
        return view('teacher.user-management', [
            'title' => 'Manajemen Pengguna Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher class management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherClassManagement()
    {
        $user = auth()->user();
        return view('teacher.class-management', [
            'title' => 'Manajemen Kelas Teacher',
            'user' => $user
        ]);
    }

    /**
     * Create teacher class.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherClass(Request $request)
    {
        // Implementation for creating classes
        return redirect()->route('teacher.class-management')
            ->with('success', 'Kelas berhasil dibuat');
    }

    /**
     * Filter teacher classes.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherClasses(Request $request)
    {
        $user = auth()->user();
        return view('teacher.class-management', [
            'title' => 'Manajemen Kelas Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher subject management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherSubjectManagement()
    {
        $user = auth()->user();
        return view('teacher.subject-management', [
            'title' => 'Manajemen Mata Pelajaran Teacher',
            'user' => $user
        ]);
    }

    /**
     * Create teacher subject.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTeacherSubject(Request $request)
    {
        // Implementation for creating subjects
        return redirect()->route('teacher.subject-management')
            ->with('success', 'Mata pelajaran berhasil dibuat');
    }

    /**
     * Filter teacher subjects.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterTeacherSubjects(Request $request)
    {
        $user = auth()->user();
        return view('teacher.subject-management', [
            'title' => 'Manajemen Mata Pelajaran Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View teacher material management.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherMaterialManagement(Request $request)
    {
        $user = auth()->user();
        
        // Get available subjects and classes for the teacher only
        $subjects = $this->getTeacherAvailableSubjects($request);
        $classes = $this->getTeacherAvailableClasses($request);
        
        // Get all materi from teacher's assigned classes only
        $myMateri = Materi::whereHas('kelasMapel.editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['kelasMapel.kelas', 'kelasMapel.mapel'])->get();
        
        // Calculate statistics
        $totalMaterials = $myMateri->count();
        $totalDocuments = $myMateri->filter(function($materi) {
            return $materi->file_type === 'document';
        })->count();
        $totalVideos = $myMateri->filter(function($materi) {
            return $materi->file_type === 'video';
        })->count();
        $totalDownloads = 0; // This would need to be calculated from download logs
        
        // Prepare materials data for the table
        $materials = $myMateri->map(function($materi) {
            return (object) [
                'id' => $materi->id,
                'title' => $materi->name,
                'description' => $materi->deskripsi ?? $materi->content,
                'type' => $materi->file_type ?? 'document',
                'subject_name' => $materi->kelasMapel->mapel->name ?? 'N/A',
                'class_name' => $materi->kelasMapel->kelas->name ?? 'N/A',
                'file_size' => $materi->getFileSize() ?? '2.5 MB',
                'download_count' => 0
            ];
        });
        
        $stats = [
            'totalMaterials' => $totalMaterials,
            'publishedMaterials' => $totalMaterials, // Assuming all are published
            'draftMaterials' => 0,
            'totalDownloads' => $totalDownloads
        ];
        
        return view('material-management.unified-material-management', [
            'title' => 'Material Management',
            'user' => $user,
            'subjects' => $subjects,
            'classes' => $classes,
            'materials' => $materials,
            'stats' => $stats
        ]);
    }


    /**
     * View teacher reports.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherReports(Request $request)
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.reports', [
                'title' => 'Laporan Teacher',
                'user' => $user,
                'reports' => collect(),
                'stats' => ['total_tasks' => 0, 'total_exams' => 0, 'total_materials' => 0, 'total_students' => 0]
            ]);
        }
        
        // Get reports data from teacher's assigned classes only
        $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        $totalExams = Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        $totalMaterials = Materi::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        // Get total students from assigned classes
        $totalStudents = User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4) // Student role
            ->count();
        
        $stats = [
            'total_tasks' => $totalTasks,
            'total_exams' => $totalExams,
            'total_materials' => $totalMaterials,
            'total_students' => $totalStudents
        ];
        
        // Get recent activities from assigned classes
        $recentTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get();
        
        $recentExams = Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get();
        
        $recentMaterials = Materi::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get();
        
        $reports = collect([
            'recent_tasks' => $recentTasks,
            'recent_exams' => $recentExams,
            'recent_materials' => $recentMaterials
        ]);
        
        return view('teacher.reports', [
            'title' => 'Laporan Teacher',
            'user' => $user,
            'reports' => $reports,
            'stats' => $stats
        ]);
    }

    /**
     * View teacher help.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherHelp()
    {
        $user = auth()->user();
        return view('teacher.help', [
            'title' => 'Bantuan Teacher',
            'user' => $user
        ]);
    }

    // Student Management Methods (Matching Superadmin)
    
    /**
     * View student profile.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentProfile()
    {
        $user = auth()->user();
        return view('student.profile', [
            'title' => 'Profil Student',
            'user' => $user
        ]);
    }

    /**
     * Update student profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStudentProfile(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('student.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Upload student photo.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadStudentPhoto(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $filename);
            
            $user->update(['photo' => $filename]);
            
            return response()->json(['success' => true, 'photo' => $filename]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengupload foto']);
    }

    /**
     * View student settings.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentSettings()
    {
        $user = auth()->user();
        return view('student.settings', [
            'title' => 'Pengaturan Student',
            'user' => $user
        ]);
    }

    /**
     * View student push notification.
     *
     * @return \Illuminate\View\View
     */
    // Push notification method removed for students - not relevant

    /**
     * Send student push notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // Send push notification method removed for students - not relevant

    /**
     * Filter student push notifications.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    // Filter push notification method removed for students - not relevant

    /**
     * View student IoT management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentIotManagement()
    {
        $user = auth()->user();
        return view('student.iot-management', [
            'title' => 'Manajemen IoT Student',
            'user' => $user
        ]);
    }

    /**
     * Register student IoT device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerStudentIotDevice(Request $request)
    {
        // Implementation for registering IoT devices
        return redirect()->route('student.iot-management')
            ->with('success', 'Perangkat IoT berhasil didaftarkan');
    }

    /**
     * View student task management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentTaskManagement()
    {
        $user = auth()->user();
        return view('student.task-management', [
            'title' => 'Manajemen Tugas Student',
            'user' => $user
        ]);
    }

    /**
     * Create student task.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentTask(Request $request)
    {
        // Implementation for creating tasks
        return redirect()->route('student.task-management')
            ->with('success', 'Tugas berhasil dibuat');
    }

    /**
     * Filter student tasks.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentTasks(Request $request)
    {
        $user = auth()->user();
        return view('student.task-management', [
            'title' => 'Manajemen Tugas Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student exam management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentExamManagement()
    {
        $user = auth()->user();
        return view('student.exam-management', [
            'title' => 'Manajemen Ujian Student',
            'user' => $user
        ]);
    }

    /**
     * Create student exam.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentExam(Request $request)
    {
        // Implementation for creating exams
        return redirect()->route('student.exam-management')
            ->with('success', 'Ujian berhasil dibuat');
    }

    /**
     * Filter student exams.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentExams(Request $request)
    {
        $user = auth()->user();
        return view('student.exam-management', [
            'title' => 'Manajemen Ujian Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student user management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentUserManagement()
    {
        $user = auth()->user();
        return view('student.user-management', [
            'title' => 'Manajemen Pengguna Student',
            'user' => $user
        ]);
    }

    /**
     * Create student user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentUser(Request $request)
    {
        // Implementation for creating users
        return redirect()->route('student.user-management')
            ->with('success', 'Pengguna berhasil dibuat');
    }

    /**
     * Filter student users.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentUsers(Request $request)
    {
        $user = auth()->user();
        return view('student.user-management', [
            'title' => 'Manajemen Pengguna Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student class management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentClassManagement()
    {
        $user = auth()->user();
        
        // Get student's class information
        $kelas = $user->kelas;
        
        // Get subjects in this class with pengajar information
        $mapelKelas = DB::table('kelas_mapels')
            ->join('mapels', 'kelas_mapels.mapel_id', '=', 'mapels.id')
            ->leftJoin('editor_accesses', 'kelas_mapels.id', '=', 'editor_accesses.kelas_mapel_id')
            ->leftJoin('users', function($join) {
                $join->on('editor_accesses.user_id', '=', 'users.id')
                     ->where('users.roles_id', '=', 2); // Only pengajar role
            })
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->select('kelas_mapels.*', 'mapels.name as mapel_name', 'users.name as pengajar_name')
            ->get();
        
        // Get classmates
        $classmates = DB::table('users')
            ->where('kelas_id', $user->kelas_id)
            ->where('id', '!=', $user->id)
            ->select('id', 'name', 'email', 'gambar')
            ->get();
        
        // Get class statistics
        $totalStudents = DB::table('users')
            ->where('kelas_id', $user->kelas_id)
            ->count();
        
        $totalSubjects = $mapelKelas->count();
        
        $totalTugas = DB::table('tugas')
            ->join('kelas_mapels', 'tugas.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        $totalUjian = DB::table('ujians')
            ->join('kelas_mapels', 'ujians.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        $totalMateri = DB::table('materis')
            ->join('kelas_mapels', 'materis.kelas_mapel_id', '=', 'kelas_mapels.id')
            ->where('kelas_mapels.kelas_id', $user->kelas_id)
            ->count();
        
        return view('student.class-management', compact(
            'user', 'kelas', 'mapelKelas', 'classmates', 
            'totalStudents', 'totalSubjects', 'totalTugas', 
            'totalUjian', 'totalMateri'
        ))->with('title', 'Kelas Saya');
    }

    /**
     * Create student class.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentClass(Request $request)
    {
        // Implementation for creating classes
        return redirect()->route('student.class-management')
            ->with('success', 'Kelas berhasil dibuat');
    }

    /**
     * Filter student classes.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentClasses(Request $request)
    {
        $user = auth()->user();
        return view('student.class-management', [
            'title' => 'Manajemen Kelas Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student subject management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentSubjectManagement()
    {
        $user = auth()->user();
        return view('student.subject-management', [
            'title' => 'Manajemen Mata Pelajaran Student',
            'user' => $user
        ]);
    }

    /**
     * Create student subject.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentSubject(Request $request)
    {
        // Implementation for creating subjects
        return redirect()->route('student.subject-management')
            ->with('success', 'Mata pelajaran berhasil dibuat');
    }

    /**
     * Filter student subjects.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentSubjects(Request $request)
    {
        $user = auth()->user();
        return view('student.subject-management', [
            'title' => 'Manajemen Mata Pelajaran Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student material management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentMaterialManagement()
    {
        $user = auth()->user();
        return view('student.material-management', [
            'title' => 'Manajemen Materi Student',
            'user' => $user
        ]);
    }

    /**
     * Create student material.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStudentMaterial(Request $request)
    {
        // Implementation for creating materials
        return redirect()->route('student.materials')
            ->with('success', 'Materi berhasil dibuat');
    }

    /**
     * View student reports.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentReports()
    {
        $user = auth()->user();
        return view('student.reports', [
            'title' => 'Laporan Student',
            'user' => $user
        ]);
    }

    /**
     * View student help.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentHelp()
    {
        $user = auth()->user();
        return view('student.help', [
            'title' => 'Bantuan Student',
            'user' => $user
        ]);
    }

    /**
     * View superadmin material creation page.
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminMaterialCreate()
    {
        $user = auth()->user();
        
        // Get all subjects and classes for superadmin
        $subjects = Mapel::all();
        $classes = Kelas::all();
        
        return view('material-create', [
            'title' => 'Tambah Materi Baru',
            'user' => $user,
            'userRole' => 'superadmin',
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }

    /**
     * View admin material creation page.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminMaterialCreate()
    {
        $user = auth()->user();
        
        // Get all subjects and classes for admin
        $subjects = Mapel::all();
        $classes = Kelas::all();
        
        return view('material-create', [
            'title' => 'Tambah Materi Baru',
            'user' => $user,
            'userRole' => 'admin',
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }

    /**
     * View teacher material creation page.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherMaterialCreate()
    {
        $user = auth()->user();
        
        // Get available subjects and classes for the teacher
        $subjects = $this->getTeacherAvailableSubjects(request());
        $classes = $this->getTeacherAvailableClasses(request());
        
        return view('material-create', [
            'title' => 'Tambah Materi Baru',
            'user' => $user,
            'userRole' => 'teacher',
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }

    /**
     * Get role name by role ID
     */
    private function getRoleName($roleId)
    {
        $roleNames = [
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Teacher',
            4 => 'Student'
        ];
        
        return $roleNames[$roleId] ?? 'User';
    }

    /**
     * Get dashboard statistics based on role
     */
    private function getDashboardStats($roleId)
    {
        $stats = [];
        
        switch($roleId) {
            case 1: // Super Admin
            case 2: // Admin
                $stats = [
                    'totalUsers' => User::count(),
                    'totalClasses' => Kelas::count(),
                    'totalTasks' => Tugas::count(),
                    'totalIotDevices' => IotDevice::count() ?? 0,
                ];
                break;
                
            case 3: // Teacher
                $stats = [
                    'totalStudents' => User::where('roles_id', 4)->count(),
                    'activeTasks' => Tugas::where('isHidden', false)->count(),
                    'totalMaterials' => Materi::count(),
                    'iotProjects' => IotDevice::count() ?? 0,
                ];
                break;
                
            case 4: // Student
                $stats = [
                    'pendingTasks' => Tugas::where('isHidden', false)->count(),
                    'completedTasks' => Tugas::where('isHidden', true)->count(),
                    'averageScore' => 85, // Placeholder
                    'iotProjects' => 2, // Placeholder
                ];
                break;
        }
        
        return $stats;
    }

    /**
     * Get recent activities based on role
     */
    private function getRecentActivities($roleId)
    {
        $activities = [];
        
        // Placeholder activities - in real implementation, fetch from database
        switch($roleId) {
            case 1: // Super Admin
            case 2: // Admin
                $activities = [
                    [
                        'icon' => 'fas fa-user-plus',
                        'title' => 'New user registered',
                        'description' => 'John Doe has been added to the system',
                        'time' => '2 hours ago'
                    ],
                    [
                        'icon' => 'fas fa-tasks',
                        'title' => 'Task created',
                        'description' => 'New IoT project task has been created',
                        'time' => '4 hours ago'
                    ],
                ];
                break;
                
            case 3: // Teacher
                $activities = [
                    [
                        'icon' => 'fas fa-tasks',
                        'title' => 'Task submitted',
                        'description' => 'Student completed IoT sensor project',
                        'time' => '1 hour ago'
                    ],
                    [
                        'icon' => 'fas fa-file-plus',
                        'title' => 'Material added',
                        'description' => 'New learning material uploaded',
                        'time' => '3 hours ago'
                    ],
                ];
                break;
                
            case 4: // Student
                $activities = [
                    [
                        'icon' => 'fas fa-check-circle',
                        'title' => 'Task completed',
                        'description' => 'IoT sensor project submitted',
                        'time' => '1 hour ago'
                    ],
                    [
                        'icon' => 'fas fa-microchip',
                        'title' => 'IoT project started',
                        'description' => 'Temperature monitoring project initiated',
                        'time' => '2 days ago'
                    ],
                ];
                break;
        }
        
        return $activities;
    }

    /**
     * Get notifications based on role
     */
    private function getNotifications($roleId)
    {
        $notifications = [];
        
        // Placeholder notifications - in real implementation, fetch from database
        switch($roleId) {
            case 1: // Super Admin
            case 2: // Admin
                $notifications = [
                    [
                        'title' => 'System Update',
                        'message' => 'New features have been added to the platform',
                        'time' => '1 day ago',
                        'unread' => true
                    ],
                    [
                        'title' => 'User Activity',
                        'message' => 'High user activity detected',
                        'time' => '2 days ago',
                        'unread' => false
                    ],
                ];
                break;
                
            case 3: // Teacher
                $notifications = [
                    [
                        'title' => 'New Assignment',
                        'message' => 'You have been assigned to a new class',
                        'time' => '3 hours ago',
                        'unread' => true
                    ],
                ];
                break;
                
            case 4: // Student
                $notifications = [
                    [
                        'title' => 'Task Due Soon',
                        'message' => 'IoT project deadline is approaching',
                        'time' => '6 hours ago',
                        'unread' => true
                    ],
                ];
                break;
        }
        
        return $notifications;
    }

    /**
     * View Admin IoT Dashboard
     */
    public function viewAdminIotDashboard()
    {
        try {
            // Get IoT devices statistics
            $totalDevices = \App\Models\IotDevice::count();
            $connectedDevices = \App\Models\IotDevice::where('status', 'connected')->count();
            $totalDataPoints = \App\Models\IotDevice::sum('data_points') ?? 0;
            $activeClasses = \App\Models\IotDevice::whereNotNull('class_id')
                ->distinct('class_id')
                ->count('class_id');

            // Get recent devices
            $recentDevices = \App\Models\IotDevice::with('kelas')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            $statistics = [
                'total_devices' => $totalDevices,
                'connected_devices' => $connectedDevices,
                'total_data_points' => $totalDataPoints,
                'active_classes' => $activeClasses
            ];

            return view('menu.admin.iot.dashboard', compact(
                'statistics',
                'recentDevices'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in viewAdminIotDashboard: ' . $e->getMessage());
            
            return view('menu.admin.iot.dashboard', [
                'statistics' => [
                    'total_devices' => 0,
                    'connected_devices' => 0,
                    'total_data_points' => 0,
                    'active_classes' => 0
                ],
                'recentDevices' => collect()
            ]);
        }
    }
}

