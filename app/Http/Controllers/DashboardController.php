<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
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
use Illuminate\Support\Str;
use App\Models\UserUjian;
use App\Models\TugasProgress;
use App\Helpers\DashboardHelper;
use App\Traits\TeacherAccessControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
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
            'roleId' => $authRoles
        ]);
        
        return view($dashboardView, $templateData);
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
        
        $stats = [
            'total_admins' => \App\Models\User::where('roles_id', 2)->count(),
            'total_teachers' => \App\Models\User::where('roles_id', 3)->count(),
            'total_students' => \App\Models\User::where('roles_id', 4)->count(),
            'total_classes' => \App\Models\Kelas::count(),
        ];

        return view('dashboard.superadmin-profile', [
            'title' => 'Profil Super Admin',
            'user' => $user,
            'stats' => $stats
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
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update super admin password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSuperAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
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
            
            // Log error untuk debugging
            \Log::error('Failed to create superadmin multiple choice exam', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'password']),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput($request->all()); // Pastikan semua data tersimpan
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
            // Log error untuk debugging
            \Log::error('Failed to create superadmin mixed exam', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'password']),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput($request->all()); // Pastikan semua data tersimpan
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
            
            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.exam-management')
                ->with('error', 'Gagal menghapus ujian: ' . $e->getMessage());
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
            
            return redirect()->route('superadmin.exam-management')
                ->with('success', 'Ujian berhasil dipublikasikan!');
                
        } catch (\Exception $e) {
            return redirect()->route('superadmin.exam-management')
                ->with('error', 'Gagal mempublikasikan ujian: ' . $e->getMessage());
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
            // Log error untuk debugging
            \Log::error('Failed to create superadmin exam', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'password']),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal membuat ujian: ' . $e->getMessage())
                ->withInput($request->all()); // Pastikan semua data tersimpan
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
        $classes = Kelas::withCount(['siswa', 'subjects'])
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'level' => 'required|string|in:X,XI,XII',
            'major' => 'required|string|in:IPA,IPS,Bahasa',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            Kelas::create([
                'name' => $request->name,
                'code' => $request->code,
                'level' => $request->level,
                'major' => $request->major,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return redirect()->route('superadmin.class-management')
                ->with('success', 'Kelas berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.class-management')
                ->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail kelas untuk Super Admin.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminClassDetail($id)
    {
        $user = auth()->user();
        
        $class = Kelas::with(['students', 'kelasMapel.mapel', 'kelasMapel.editorAccess.user'])
            ->findOrFail($id);
        
        // Get statistics
        $totalStudents = $class->students->count();
        $totalSubjects = $class->kelasMapel->count();
        
        return view('superadmin.class-detail', [
            'title' => 'Detail Kelas - ' . $class->name,
            'user' => $user,
            'class' => $class,
            'totalStudents' => $totalStudents,
            'totalSubjects' => $totalSubjects,
        ]);
    }

    /**
     * Menampilkan form edit kelas untuk Super Admin.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editSuperAdminClass($id)
    {
        try {
            $class = Kelas::findOrFail($id);
            
            // Get all students
            $allStudents = User::where('roles_id', 4)
                ->orderBy('name')
                ->get(['id', 'name', 'email', 'kelas_id']);
            
            // Get all subjects
            $allSubjects = Mapel::orderBy('name')
                ->get(['id', 'name', 'code']);
            
            // Get current class students (students assigned to this class)
            $classStudentIds = $class->siswa()->pluck('id')->toArray();
            
            // Get current class subjects
            $classSubjectIds = $class->kelasMapel()->pluck('mapel_id')->toArray();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $class,
                    'all_students' => $allStudents,
                    'all_subjects' => $allSubjects,
                    'class_student_ids' => $classStudentIds,
                    'class_subject_ids' => $classSubjectIds
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load class data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update kelas untuk Super Admin.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSuperAdminClass(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'level' => 'required|string|in:X,XI,XII',
            'major' => 'required|string|in:IPA,IPS,Bahasa',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:mapels,id'
        ]);

        try {
            $class = Kelas::findOrFail($id);
            
            // Update class basic info
            $class->update([
                'name' => $request->name,
                'code' => $request->code,
                'level' => $request->level,
                'major' => $request->major,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            // Update students - assign selected students to this class
            if ($request->has('students')) {
                // Remove all previous students from this class
                User::where('kelas_id', $class->id)->update(['kelas_id' => null]);
                
                // Assign new students to this class
                User::whereIn('id', $request->students)->update(['kelas_id' => $class->id]);
            }

            // Update subjects - sync kelas_mapels
            if ($request->has('subjects')) {
                // Delete existing relationships
                KelasMapel::where('kelas_id', $class->id)->delete();
                
                // Create new relationships
                foreach ($request->subjects as $mapelId) {
                    KelasMapel::create([
                        'kelas_id' => $class->id,
                        'mapel_id' => $mapelId
                    ]);
                }
            }

            return redirect()->route('superadmin.class-management')
                ->with('success', 'Kelas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.class-management')
                ->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }


    /**
     * Hapus kelas untuk Super Admin.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSuperAdminClass($id)
    {
        try {
            $class = Kelas::findOrFail($id);
            
            // Check if class has students
            if ($class->students()->count() > 0) {
                return redirect()->route('superadmin.class-management')
                    ->with('error', 'Tidak dapat menghapus kelas yang memiliki siswa. Pindahkan siswa terlebih dahulu.');
            }
            
            // Check if class has subjects
            if ($class->kelasMapel()->count() > 0) {
                return redirect()->route('superadmin.class-management')
                    ->with('error', 'Tidak dapat menghapus kelas yang memiliki mata pelajaran. Hapus mata pelajaran terlebih dahulu.');
            }
            
            $class->delete();

            return redirect()->route('superadmin.class-management')
                ->with('success', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.class-management')
                ->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
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
            // Log error untuk debugging
            \Log::error('Failed to create superadmin material', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'password']),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()
                ->withInput($request->all()) // Pastikan semua data tersimpan
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
        $classes = Kelas::all();
        $subjects = \App\Models\Mapel::all();
        
        // Calculate additional metrics
        $activeUsers = \App\Models\User::where('updated_at', '>=', now()->subDays(30))->count();
        $completedTasks = \App\Models\TugasProgress::whereIn('status', ['submitted', 'graded'])->count();
        $averageScore = 85; // Placeholder - you can implement actual calculation
        
        return view('superadmin.analytics', [
            'title' => 'Analitik',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'totalExams' => $totalExams,
            'averageScore' => $averageScore,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'recentClasses' => $recentClasses,
            'classes' => $classes,
            'subjects' => $subjects,
            'filters' => [],
        ]);
    }

    /**
     * Menampilkan halaman Analitik Super Admin dengan filter.
     *
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminAnalytics()
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = request()->only(['filter_period', 'filter_class', 'filter_subject', 'filter_type']);
        
        // Get analytics data with filters
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
        $classes = Kelas::all();
        $subjects = \App\Models\Mapel::all();
        
        // Calculate additional metrics based on filters
        $activeUsers = \App\Models\User::where('updated_at', '>=', now()->subDays(30))->count();
        $completedTasks = \App\Models\TugasProgress::whereIn('status', ['submitted', 'graded'])->count();
        $averageScore = 85; // Placeholder - you can implement actual calculation
        
        return view('superadmin.analytics', [
            'title' => 'Analitik',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'totalExams' => $totalExams,
            'averageScore' => $averageScore,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'recentClasses' => $recentClasses,
            'classes' => $classes,
            'subjects' => $subjects,
            'filters' => $filters,
        ]);
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
        
        // Get all materi created by this teacher
        $materials = Materi::whereHas('kelasMapel.editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['kelasMapel.mapel'])
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
                'download_count' => 0,
                'thumbnail_path' => null, // Add thumbnail_path property
                'thumbnail_url' => null,  // Add thumbnail_url property
                'file_materi' => $materi->file_materi,
                'is_published' => !$materi->isHidden,
                'views_count' => 0,
                'downloads_count' => 0
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
        /** @var User $user */
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000'
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'bio']));

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update admin password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
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
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $filename);
            
            $user->update(['photo' => $filename]);
            
            return response()->json(['success' => true, 'photo' => $filename]);
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

        return view('superadmin.push-notification', [
            'title' => 'Push Notifikasi Admin',
            'user' => $user,
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
        
        return view('superadmin.push-notification', [
            'title' => 'Push Notifikasi Admin',
            'user' => $user,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'users' => $users
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
            ->latest('measured_at')->first()->temperature ?? 25.5;
        $currentHumidity = \App\Models\IotSensorData::whereNotNull('humidity')
            ->latest('measured_at')->first()->humidity ?? 65.2;
        $currentMoisture = \App\Models\IotSensorData::whereNotNull('soil_moisture')
            ->latest('measured_at')->first()->soil_moisture ?? 45.8;
        $currentPh = \App\Models\IotSensorData::whereNotNull('ph_level')
            ->latest('measured_at')->first()->ph_level ?? 6.8;
        
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
     * View admin reports.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminReports()
    {
        $user = auth()->user();
        
        // Get reports data for admin (similar to superadmin but with admin-specific filters)
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        // Get class data
        $classData = [];
        foreach ($classes as $class) {
            $classData[] = [
                'name' => $class->name,
                'student_count' => $class->siswa->count(),
                'task_count' => $class->KelasMapel->sum(function($km) {
                    return $km->Tugas->count();
                }),
                'exam_count' => $class->KelasMapel->sum(function($km) {
                    return $km->Ujian->count();
                })
            ];
        }
        
        // Get subject data
        $subjectData = [];
        foreach ($subjects as $subject) {
            $subjectData[] = [
                'name' => $subject->name,
                'class_count' => $subject->KelasMapel->count(),
                'task_count' => $subject->KelasMapel->sum(function($km) {
                    return $km->Tugas->count();
                }),
                'exam_count' => $subject->KelasMapel->sum(function($km) {
                    return $km->Ujian->count();
                })
            ];
        }
        
        // Get report statistics
        $totalReports = UserTugas::count();
        $completedReports = UserTugas::where('status', 'completed')->count();
        $pendingReports = UserTugas::where('status', 'pending')->count();
        $failedReports = UserTugas::where('status', 'failed')->count();
        
        return view('admin.reports', [
            'title' => 'Laporan Admin',
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
     * Filter admin reports.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterAdminReports(Request $request)
    {
        $user = auth()->user();
        
        // Apply filters similar to superadmin but with admin-specific logic
        $filters = $request->only(['filter_class', 'filter_subject', 'filter_date_from', 'filter_date_to']);
        
        return view('admin.reports', [
            'title' => 'Laporan Admin',
            'user' => $user,
            'filters' => $filters
        ]);
    }

    /**
     * View admin analytics.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminAnalytics()
    {
        $user = auth()->user();
        
        // Get analytics data for admin (similar to superadmin but with admin-specific scope)
        $totalUsers = User::whereIn('roles_id', [3, 4])->count(); // Only teachers and students
        $totalClasses = Kelas::count();
        $totalSubjects = Mapel::count();
        $totalTasks = Tugas::count();
        $totalExams = Ujian::count();
        
        // Get user statistics by role (excluding superadmin)
        $userStats = User::join('roles', 'users.roles_id', '=', 'roles.id')
            ->whereIn('users.roles_id', [2, 3, 4]) // Admin, Teacher, Student
            ->selectRaw('roles.name as role, COUNT(*) as count')
            ->groupBy('roles.name')
            ->get();
        
        // Get recent activity (admin scope)
        $recentUsers = User::whereIn('roles_id', [3, 4])->latest()->limit(5)->get();
        $recentClasses = Kelas::latest()->limit(5)->get();
        
        return view('admin.analytics', [
            'title' => 'Analitik Admin',
            'user' => $user,
            'totalUsers' => $totalUsers,
            'totalClasses' => $totalClasses,
            'totalSubjects' => $totalSubjects,
            'totalTasks' => $totalTasks,
            'totalExams' => $totalExams,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'recentClasses' => $recentClasses,
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

    /**
     * View Super Admin IoT Tasks
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminIotTasks()
    {
        $user = auth()->user();
        
        // Get IoT tasks data
        $iotTasks = \App\Models\Tugas::where('tipe', 'iot')
            ->orWhere('name', 'like', '%IoT%')
            ->orWhere('content', 'like', '%IoT%')
            ->with(['kelasMapel'])
            ->latest()
            ->get();
        
        // Get statistics
        $totalIotTasks = $iotTasks->count();
        $activeIotTasks = $iotTasks->where('status', 'active')->count();
        $completedIotTasks = $iotTasks->where('status', 'completed')->count();
        $pendingIotTasks = $iotTasks->where('status', 'pending')->count();
        
        return view('superadmin.iot-tasks', [
            'title' => 'Tugas IoT',
            'user' => $user,
            'iotTasks' => $iotTasks,
            'totalIotTasks' => $totalIotTasks,
            'activeIotTasks' => $activeIotTasks,
            'completedIotTasks' => $completedIotTasks,
            'pendingIotTasks' => $pendingIotTasks,
        ]);
    }

    /**
     * View Super Admin IoT Research
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminIotResearch()
    {
        $user = auth()->user();
        
        // Get research projects with pagination and eager loading
        $researchProjectsQuery = \App\Models\ResearchProject::with(['kelas', 'teacher', 'sensorData'])
            ->withCount('sensorData');
        
        $researchProjects = $researchProjectsQuery->paginate(10);
        
        // Get statistics
        $totalResearchProjects = \App\Models\ResearchProject::count();
        $totalDataPoints = \App\Models\IotSensorData::whereNotNull('research_project_id')->count();
        $activeProjects = \App\Models\ResearchProject::where('status', 'active')->count();
        $projectsThisWeek = \App\Models\ResearchProject::where('created_at', '>=', now()->subWeek())->count();
        
        // Get data for charts
        $sensorDataForCharts = \App\Models\IotSensorData::with('researchProject')
            ->whereNotNull('research_project_id')
            ->where('measured_at', '>=', now()->subDays(30))
            ->orderBy('measured_at')
            ->get();
        
        // Prepare chart data
        $chartData = $this->prepareSensorChartData($sensorDataForCharts);
        
        // Get status distribution
        $statusDistribution = \App\Models\ResearchProject::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get projects data for bar chart
        $projectsData = \App\Models\ResearchProject::withCount('sensorData')
            ->orderBy('sensor_data_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('superadmin.iot-research', [
            'title' => 'Penelitian IoT',
            'user' => $user,
            'researchProjects' => $researchProjects,
            'totalResearchProjects' => $totalResearchProjects,
            'totalDataPoints' => $totalDataPoints,
            'activeProjects' => $activeProjects,
            'projectsThisWeek' => $projectsThisWeek,
            'chartData' => $chartData,
            'statusDistribution' => $statusDistribution,
            'projectsData' => $projectsData,
        ]);
    }
    
    /**
     * Prepare chart data for sensor trends
     */
    private function prepareSensorChartData($sensorData)
    {
        $labels = [];
        $temperatureData = [];
        $humidityData = [];
        $soilMoistureData = [];
        
        // Group data by day
        $groupedData = $sensorData->groupBy(function($item) {
            return $item->measured_at->format('Y-m-d');
        });
        
        foreach ($groupedData as $date => $data) {
            $labels[] = \Carbon\Carbon::parse($date)->format('M d');
            
            $avgTemp = $data->avg('temperature');
            $avgHumidity = $data->avg('humidity');
            $avgSoilMoisture = $data->avg('soil_moisture');
            
            $temperatureData[] = round($avgTemp, 2);
            $humidityData[] = round($avgHumidity, 2);
            $soilMoistureData[] = round($avgSoilMoisture, 2);
        }
        
        return [
            'labels' => $labels,
            'temperature' => $temperatureData,
            'humidity' => $humidityData,
            'soil_moisture' => $soilMoistureData,
        ];
    }

    /**
     * Filter Super Admin IoT Research
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterSuperAdminIotResearch(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        // Get filter parameters
        $filters = $request->only(['filter_status', 'filter_kelas', 'filter_teacher', 'filter_date_from', 'filter_date_to']);
        
        // Build query for research projects
        $researchProjectsQuery = \App\Models\ResearchProject::with(['kelas', 'teacher', 'sensorData'])
            ->withCount('sensorData');
        
        // Apply filters
        if (!empty($filters['filter_status'])) {
            $researchProjectsQuery->where('status', $filters['filter_status']);
        }
        
        if (!empty($filters['filter_kelas'])) {
            $researchProjectsQuery->where('kelas_id', $filters['filter_kelas']);
        }
        
        if (!empty($filters['filter_teacher'])) {
            $researchProjectsQuery->where('teacher_id', $filters['filter_teacher']);
        }
        
        if (!empty($filters['filter_date_from'])) {
            $researchProjectsQuery->where('start_date', '>=', $filters['filter_date_from']);
        }
        
        if (!empty($filters['filter_date_to'])) {
            $researchProjectsQuery->where('start_date', '<=', $filters['filter_date_to']);
        }
        
        $researchProjects = $researchProjectsQuery->paginate(10);
        
        // Get statistics with filters applied
        $totalResearchProjects = \App\Models\ResearchProject::count();
        $totalDataPoints = \App\Models\IotSensorData::whereNotNull('research_project_id')->count();
        $activeProjects = \App\Models\ResearchProject::where('status', 'active')->count();
        $projectsThisWeek = \App\Models\ResearchProject::where('created_at', '>=', now()->subWeek())->count();
        
        // Get data for charts (filtered)
        $sensorDataForCharts = \App\Models\IotSensorData::with('researchProject')
            ->whereNotNull('research_project_id')
            ->where('measured_at', '>=', now()->subDays(30))
            ->orderBy('measured_at')
            ->get();
        
        // Apply project filters to sensor data
        if (!empty($filters['filter_status']) || !empty($filters['filter_kelas']) || !empty($filters['filter_teacher'])) {
            $projectIds = \App\Models\ResearchProject::query();
            
            if (!empty($filters['filter_status'])) {
                $projectIds->where('status', $filters['filter_status']);
            }
            if (!empty($filters['filter_kelas'])) {
                $projectIds->where('kelas_id', $filters['filter_kelas']);
            }
            if (!empty($filters['filter_teacher'])) {
                $projectIds->where('teacher_id', $filters['filter_teacher']);
            }
            
            $projectIds = $projectIds->pluck('id');
            $sensorDataForCharts = $sensorDataForCharts->whereIn('research_project_id', $projectIds);
        }
        
        // Prepare chart data
        $chartData = $this->prepareSensorChartData($sensorDataForCharts);
        
        // Get status distribution
        $statusDistribution = \App\Models\ResearchProject::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Get projects data for bar chart
        $projectsData = \App\Models\ResearchProject::withCount('sensorData')
            ->orderBy('sensor_data_count', 'desc')
            ->limit(10)
            ->get();
        
        return view('superadmin.iot-research', [
            'title' => 'Penelitian IoT - Filtered',
            'user' => $user,
            'researchProjects' => $researchProjects,
            'totalResearchProjects' => $totalResearchProjects,
            'totalDataPoints' => $totalDataPoints,
            'activeProjects' => $activeProjects,
            'projectsThisWeek' => $projectsThisWeek,
            'chartData' => $chartData,
            'statusDistribution' => $statusDistribution,
            'projectsData' => $projectsData,
            'filters' => $filters,
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
     * View Admin IoT Dashboard
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminIotDashboard()
    {
        $user = auth()->user();
        
        // Get IoT data
        $devices = \App\Models\IotDevice::with('latestSensorData')->get();
        $recentData = \App\Models\IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        $kelas = \App\Models\Kelas::all();
        
        // Statistics
        $totalData = \App\Models\IotSensorData::count();
        $activeDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $activeClasses = \App\Models\IotSensorData::distinct('kelas_id')->count();
        $activeProjects = \App\Models\ResearchProject::where('status', 'active')->count();
        
        return view('admin.iot-dashboard', compact('devices', 'recentData', 'kelas', 'totalData', 'activeDevices', 'activeClasses', 'activeProjects'))
            ->with('title', 'IoT Dashboard Admin');
    }

    /**
     * View Super Admin IoT Dashboard
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminIotDashboard()
    {
        $user = auth()->user();
        
        // Get IoT data
        $devices = \App\Models\IotDevice::with('latestSensorData')->get();
        $recentData = \App\Models\IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(10)
            ->get();
        $kelas = \App\Models\Kelas::all();
        
        // Statistics
        $totalData = \App\Models\IotSensorData::count();
        $activeDevices = \App\Models\IotDevice::where('status', 'online')->count();
        $activeClasses = \App\Models\IotSensorData::distinct('kelas_id')->count();
        $activeProjects = \App\Models\ResearchProject::where('status', 'active')->count();
        
        return view('superadmin.iot-dashboard', compact('devices', 'recentData', 'kelas', 'totalData', 'activeDevices', 'activeClasses', 'activeProjects'))
            ->with('title', 'IoT Dashboard Super Admin');
    }

    /**
     * View admin task management.
     *
     * @return \Illuminate\View\View
     */
    public function viewAdminTaskManagement()
    {
        $user = auth()->user();
        
        // Statistik tugas (sesuai dengan yang dibutuhkan view)
        $totalTugas = Tugas::count();
        $activeTasks = Tugas::where('isHidden', 0)->count();
        $completedTasks = Tugas::where('due', '<', now())->count();
        $activeClasses = Kelas::whereHas('KelasMapel.Tugas')->count();
        
        // Hitung per tipe tugas
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
        
        // Data untuk filter
        $classes = Kelas::all();
        $subjects = Mapel::all();
        
        // Semua tugas
        $tasks = Tugas::with(['KelasMapel.Kelas', 'KelasMapel.Mapel'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.task-management', [
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
            'filters' => []
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
        
        // Calculate statistics per exam type
        $stats = [
            'multiple_choice' => $exams->where('tipe', 1)->count(),
            'essay' => $exams->where('tipe', 2)->count(),
        ];
        
        // Ambil ujian terbaru
        $recentExams = $exams->take(8);
        
        return view('admin.exam-management', [
            'title' => 'Manajemen Ujian Admin',
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
     * Create admin exam.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAdminExam(Request $request)
    {
        // Implementation for creating exams
        return redirect()->route('admin.exam-management')
            ->with('success', 'Ujian berhasil dibuat');
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
        
        return view('admin.kelas-management', [
            'title' => 'Manajemen Kelas Admin',
            'user' => $user,
            'classes' => $classes,
            'totalClasses' => $classes->count(),
            'totalStudents' => $classes->sum('students_count'),
            'totalSubjects' => \App\Models\Mapel::count(),
            'totalTeachers' => \App\Models\User::where('roles_id', 3)->count()
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
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'level' => 'required|string|in:X,XI,XII',
            'major' => 'required|string|in:IPA,IPS,Bahasa',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            Kelas::create([
                'name' => $request->name,
                'code' => $request->code,
                'level' => $request->level,
                'major' => $request->major,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return redirect()->route('admin.class-management')
                ->with('success', 'Kelas berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->route('admin.class-management')
                ->with('error', 'Gagal membuat kelas: ' . $e->getMessage());
        }
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
        
        return view('admin.kelas-management', [
            'title' => 'Manajemen Kelas Admin',
            'user' => $user,
            'classes' => $classes,
            'totalClasses' => $classes->count(),
            'totalStudents' => $classes->sum('students_count'),
            'totalSubjects' => \App\Models\Mapel::count(),
            'totalTeachers' => \App\Models\User::where('roles_id', 3)->count(),
            'filters' => $filters
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
     * Update admin settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdminSettings(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string|max:255',
            'language' => 'required|string|max:10',
            'theme' => 'required|string|in:light,dark,auto'
        ]);

        $user = auth()->user();
        
        // Update user settings (assuming these are stored in user preferences or a separate settings table)
        $user->update([
            'timezone' => $request->timezone,
            'language' => $request->language,
            'theme' => $request->theme,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui'
        ]);
    }

    // Teacher Management Methods (Matching Superadmin)
    
    /**
     * View teacher profile.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherProfile(Request $request)
    {
        $user = auth()->user();
        
        // Get teacher's assigned data for statistics
        $assignedData = $this->getTeacherAssignedData($request);
        
        $stats = [
            'total_tasks' => 0,
            'total_exams' => 0,
            'total_materials' => 0,
            'total_students' => 0
        ];

        if ($assignedData && !empty($assignedData['kelas_mapel_ids'])) {
            $stats['total_tasks'] = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $stats['total_exams'] = \App\Models\Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $stats['total_materials'] = \App\Models\Materi::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $stats['total_students'] = \App\Models\User::whereIn('kelas_id', $assignedData['kelas_ids'])
                ->where('roles_id', 4)
                ->count();
        }

        return view('teacher.profile', [
            'title' => 'Profil Teacher',
            'user' => $user,
            'stats' => $stats
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
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000'
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'bio']));

        return redirect()->route('teacher.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update teacher password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTeacherPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
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
            $filename = 'photos/' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public', $filename);
            
            // Sync both fields for compatibility
            $user->update([
                'profile_photo' => $filename,
                'gambar' => basename($filename)
            ]);
            
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
        
        // Check if user is teacher
        if ($user->roles_id != 3) {
            abort(403, 'Unauthorized access. Hanya guru yang dapat mengakses halaman ini.');
        }
        
        // Get teacher's assigned classes and subjects (with fallback for teachers without assignments)
        $assignedData = $this->getTeacherAssignedData(request());
        
        // Get students in teacher's classes (if teacher has assignments)
        $students = collect();
        if ($assignedData && !empty($assignedData['kelas_ids'])) {
            $students = User::whereIn('kelas_id', $assignedData['kelas_ids'])
                ->where('roles_id', 4)
                ->with('kelas')
                ->orderBy('name')
                ->get();
        } else {
            // If teacher has no assignments, get all students (for notification purposes)
            $students = User::where('roles_id', 4)
                ->with('kelas')
                ->orderBy('name')
                ->get();
        }
        
        // Get notifications sent by this teacher
        $notifications = Notification::where('data', 'like', '%"teacher_id":' . $user->id . '%')
            ->orWhere('data', 'like', '%"sent_by_teacher":true%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get notification stats
        $totalNotifications = $notifications->total();
        $readNotifications = Notification::where('data', 'like', '%"teacher_id":' . $user->id . '%')
            ->where('is_read', true)
            ->count();
        $unreadNotifications = Notification::where('data', 'like', '%"teacher_id":' . $user->id . '%')
            ->where('is_read', false)
            ->count();
        $urgentNotifications = Notification::where('data', 'like', '%"teacher_id":' . $user->id . '%')
            ->where('type', 'error')
            ->count();
        
        return view('teacher.push-notification', [
            'title' => 'Push Notifikasi Teacher',
            'user' => $user,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications,
            'students' => $students,
            'assignedData' => $assignedData
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
        
        // Check if user is teacher
        if ($user->roles_id != 3) {
            abort(403, 'Unauthorized access. Hanya guru yang dapat mengakses halaman ini.');
        }
        
        return view('teacher.push-notification', [
            'title' => 'Push Notifikasi Teacher',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * Send teacher push notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendTeacherPushNotification(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is teacher
        if ($user->roles_id != 3) {
            abort(403, 'Unauthorized access. Hanya guru yang dapat mengirim notifikasi.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'recipient_type' => 'required|in:my_students,specific_students',
            'specific_students' => 'required_if:recipient_type,specific_students|array',
            'specific_students.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $notifications = [];
            $recipientCount = 0;

            if ($request->recipient_type === 'my_students') {
                // Kirim ke siswa di kelas yang diajar guru ini
                $assignedData = $this->getTeacherAssignedData($request);
                
                if (!$assignedData || empty($assignedData['kelas_ids'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Anda belum memiliki kelas yang ditugaskan.');
                }

                $students = User::whereIn('kelas_id', $assignedData['kelas_ids'])
                    ->where('roles_id', 4)
                    ->get();

                foreach ($students as $student) {
                    $notifications[] = [
                        'user_id' => $student->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['sent_by_teacher' => true, 'teacher_id' => $user->id]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $recipientCount++;
                }
            } elseif ($request->recipient_type === 'specific_students') {
                // Kirim ke siswa tertentu (dengan validasi bahwa siswa tersebut ada di kelas guru)
                $assignedData = $this->getTeacherAssignedData($request);
                
                if (!$assignedData || empty($assignedData['kelas_ids'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Anda belum memiliki kelas yang ditugaskan.');
                }

                // Validasi bahwa siswa yang dipilih ada di kelas guru
                $validStudents = User::whereIn('id', $request->specific_students)
                    ->whereIn('kelas_id', $assignedData['kelas_ids'])
                    ->where('roles_id', 4)
                    ->get();

                if ($validStudents->count() !== count($request->specific_students)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Beberapa siswa yang dipilih tidak valid atau tidak ada di kelas Anda.');
                }

                foreach ($validStudents as $student) {
                    $notifications[] = [
                        'user_id' => $student->id,
                        'title' => $request->title,
                        'body' => $request->body,
                        'excerpt' => Str::limit($request->body, 100),
                        'type' => $request->type,
                        'data' => json_encode(['sent_by_teacher' => true, 'teacher_id' => $user->id]),
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

            return redirect()->route('teacher.push-notification')
                ->with('success', "Notifikasi berhasil dikirim ke {$recipientCount} siswa");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage());
        }
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
            $query->where('name', 'like', '%' . $request->filter_search . '%')
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
        
        // Cek apakah teacher memiliki assignment
        $hasAssignment = \App\Models\EditorAccess::where('user_id', $user->id)->exists();
        
        if ($hasAssignment) {
            // Jika ada assignment, gunakan logic yang ada
            $assignedData = $this->getTeacherAssignedData(request());
            
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
                    'filters' => []
                ]);
            }
            
            // Ambil data berdasarkan assignment
            $classes = $this->getTeacherAvailableClasses(request());
            $subjects = $this->getTeacherAvailableSubjects(request());
            
            $totalTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
            $activeTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', false)->count();
            $completedTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->where('isHidden', true)->count();
            
            $tasks = \App\Models\Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Jika tidak ada assignment, tampilkan semua data (untuk testing)
            $classes = \App\Models\Kelas::all();
            $subjects = \App\Models\Mapel::all();
            
            $totalTasks = \App\Models\Tugas::count();
            $activeTasks = \App\Models\Tugas::where('isHidden', false)->count();
            $completedTasks = \App\Models\Tugas::where('isHidden', true)->count();
            
            $tasks = \App\Models\Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
                ->orderBy('created_at', 'desc')
                ->get();
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
        
        return view('teacher.material-management', [
            'title' => 'Manajemen Materi',
            'user' => $user,
            'subjects' => $subjects,
            'classes' => $classes,
            'materials' => $materials,
            'totalMaterials' => $totalMaterials,
            'totalDocuments' => $totalDocuments,
            'totalVideos' => $totalVideos,
            'totalDownloads' => $totalDownloads
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
        
        // Get teacher's assigned data
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return view('teacher.reports', [
                'title' => 'Laporan Teacher',
                'user' => $user,
                'reports' => collect(),
                'stats' => ['total_tasks' => 0, 'total_exams' => 0, 'total_materials' => 0, 'total_students' => 0],
                'classes' => collect(),
                'subjects' => collect(),
                'tasks' => collect(),
                'exams' => collect(),
                'materials' => collect(),
                'students' => collect(),
                'filters' => $request->all(),
                'chartData' => []
            ]);
        }
        
        // Fetch classes and subjects for filters
        $classes = Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
        $subjects = Mapel::whereIn('id', $assignedData['mapel_ids'])->get();
        
        $filters = $request->all();
        
        // Base queries
        $tasksQuery = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->withCount(['userTugas as total_assignments'])
            ->withCount(['userTugas as submitted_count' => function($q) {
                $q->whereIn('status', ['completed', 'graded']);
            }]);
            
        $examsQuery = Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->withCount(['userUjian as total_participants'])
            ->withCount(['userUjian as participants_count' => function($q) {
                $q->where('status', 'completed');
            }]);
            
        $materialsQuery = Materi::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel']);
            
        $studentsQuery = User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->with('kelas');
            
        // Apply filters
        if ($request->has('kelas') && !empty($request->kelas)) {
            $tasksQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('kelas_id', $request->kelas));
            $examsQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('kelas_id', $request->kelas));
            $materialsQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('kelas_id', $request->kelas));
            $studentsQuery->whereIn('kelas_id', $request->kelas);
        }
        
        if ($request->has('mapel') && !empty($request->mapel)) {
            $tasksQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('mapel_id', $request->mapel));
            $examsQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('mapel_id', $request->mapel));
            $materialsQuery->whereHas('kelasMapel', fn($q) => $q->whereIn('mapel_id', $request->mapel));
        }

        $tasks = $tasksQuery->latest()->get();
        $exams = $examsQuery->latest()->get();
        $materials = $materialsQuery->latest()->get();
        $students = $studentsQuery->get();

        // Add calculated attributes for view
        foreach ($tasks as $task) {
            $task->completion_rate = $task->total_assignments > 0 ? round(($task->submitted_count / $task->total_assignments) * 100, 1) : 0;
            $task->user_tugas_avg_nilai = UserTugas::where('tugas_id', $task->id)->whereIn('status', ['completed', 'graded'])->avg('nilai') ?? 0;
        }

        foreach ($exams as $exam) {
            $exam->participation_rate = $exam->total_participants > 0 ? round(($exam->participants_count / $exam->total_participants) * 100, 1) : 0;
            $exam->user_ujian_avg_nilai = UserUjian::where('ujian_id', $exam->id)->where('status', 'completed')->avg('nilai') ?? 0;
        }

        // Stats
        $stats = [
            'total_tasks' => $tasks->count(),
            'total_exams' => $exams->count(),
            'total_materials' => $materials->count(),
            'total_students' => $students->count(),
            'active_tasks' => $tasks->where('isHidden', false)->count(),
            'completed_tasks' => $tasks->where('isHidden', true)->count(),
            'ongoing_exams' => $exams->where('isHidden', false)->count(),
            'finished_exams' => $exams->where('isHidden', true)->count(),
            'video_materials' => $materials->filter(fn($m) => $m->file_type === 'video')->count(),
            'document_materials' => $materials->filter(fn($m) => $m->file_type === 'document')->count(),
            'avg_student_score' => $tasks->avg('user_tugas_avg_nilai') ?? 0
        ];

        $reports = collect([
            'recent_tasks' => $tasks->take(5),
            'recent_exams' => $exams->take(5),
            'recent_materials' => $materials->take(5)
        ]);

        $chartData = [
            'monthly_trend' => [],
            'task_status_distribution' => [
                'submitted' => $tasks->sum('submitted_count'),
                'pending' => $tasks->sum('total_assignments') - $tasks->sum('submitted_count'),
                'late' => 0
            ],
            'completion_by_subject' => [],
            'top_students' => []
        ];

        return view('teacher.reports', [
            'title' => 'Laporan Teacher',
            'user' => $user,
            'reports' => $reports,
            'stats' => $stats,
            'classes' => $classes,
            'subjects' => $subjects,
            'tasks' => $tasks,
            'exams' => $exams,
            'materials' => $materials,
            'students' => $students,
            'filters' => $filters,
            'chartData' => $chartData
        ]);
    }

    /**
     * Export teacher reports to Excel
     */
    public function exportTeacherReports(Request $request)
    {
        $user = auth()->user();
        $assignedData = $this->getTeacherAssignedData($request);
        
        if (!$assignedData || empty($assignedData['kelas_mapel_ids'])) {
            return redirect()->back()->with('error', 'No data available for export.');
        }

        $filters = [
            'kelas' => $request->get('kelas', []),
            'mapel' => $request->get('mapel', []),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to')
        ];

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TeacherReportsExport($assignedData, $filters),
            'teacher_reports_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    /**
     * View teacher task detail
     */
    public function viewTeacherTaskDetail($id)
    {
        $user = auth()->user();
        $assignedData = $this->getTeacherAssignedData(request());
        
        $task = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel', 'userTugas.user'])
            ->findOrFail($id);

        $submissions = $task->userTugas()->with('user')->get();

        return view('teacher.task-detail', [
            'title' => 'Task Detail - ' . $task->name,
            'user' => $user,
            'task' => $task,
            'submissions' => $submissions
        ]);
    }

    /**
     * View teacher exam detail
     */
    public function viewTeacherExamDetail($id)
    {
        $user = auth()->user();
        $assignedData = $this->getTeacherAssignedData(request());
        
        $exam = Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel', 'userUjian.user'])
            ->findOrFail($id);

        $participants = $exam->userUjian()->with('user')->get();

        return view('teacher.exam-detail', [
            'title' => 'Exam Detail - ' . $exam->name,
            'user' => $user,
            'exam' => $exam,
            'participants' => $participants
        ]);
    }

    /**
     * View teacher student detail
     */
    public function viewTeacherStudentDetail($id)
    {
        $user = auth()->user();
        $assignedData = $this->getTeacherAssignedData(request());
        
        $student = User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->with('kelas')
            ->findOrFail($id);

        $tasks = UserTugas::where('user_id', $student->id)
            ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                $q->whereIn('id', $assignedData['kelas_mapel_ids']);
            })
            ->with('tugas.kelasMapel.kelas', 'tugas.kelasMapel.mapel')
            ->get();

        $exams = UserUjian::where('user_id', $student->id)
            ->whereHas('ujian.kelasMapel', function($q) use ($assignedData) {
                $q->whereIn('id', $assignedData['kelas_mapel_ids']);
            })
            ->with('ujian.kelasMapel.kelas', 'ujian.kelasMapel.mapel')
            ->get();

        return view('teacher.student-detail', [
            'title' => 'Student Detail - ' . $student->name,
            'user' => $user,
            'student' => $student,
            'tasks' => $tasks,
            'exams' => $exams
        ]);
    }

    /**
     * Get teacher's assigned data (classes and subjects)
     */
    private function getTeacherAssignedData(Request $request)
    {
        $user = auth()->user();
        
        // Get KelasMapel where teacher is assigned via EditorAccess
        $kelasMapels = \App\Models\KelasMapel::whereHas('editorAccess', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        return [
            'kelas_mapel_ids' => $kelasMapels->pluck('id')->toArray(),
            'kelas_ids' => $kelasMapels->pluck('kelas_id')->unique()->toArray(),
            'mapel_ids' => $kelasMapels->pluck('mapel_id')->unique()->toArray(),
        ];
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

    /**
     * View teacher analytics.
     *
     * @return \Illuminate\View\View
     */
    public function viewTeacherAnalytics()
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData(request());
        
        // Calculate analytics data
        $totalStudents = User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->count();
            
        $totalTasks = Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        $totalExams = Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        // Calculate average score from user_tugas and user_ujian
        $avgScore = $this->calculateAverageScore($assignedData);
        
        // Get top performing students with real data
        $topStudents = $this->getTopPerformingStudents($assignedData);
        
        // Get recent tasks with real submission data
        $recentTasks = $this->getRecentTasksWithSubmissions($assignedData);
        
        return view('teacher.analytics', [
            'title' => 'Analytics Teacher',
            'user' => $user,
            'totalStudents' => $totalStudents,
            'totalTasks' => $totalTasks,
            'totalExams' => $totalExams,
            'avgScore' => $avgScore,
            'topStudents' => $topStudents,
            'recentTasks' => $recentTasks
        ]);
    }

    /**
     * Calculate average score from user_tugas and user_ujian
     */
    private function calculateAverageScore($assignedData)
    {
        // Get average from user_tugas
        $avgTugas = \App\Models\UserTugas::whereHas('tugas', function($query) use ($assignedData) {
            $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        })->whereNotNull('nilai')->avg('nilai');

        // Get average from user_ujian
        $avgUjian = \App\Models\UserUjian::whereHas('ujian', function($query) use ($assignedData) {
            $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        })->whereNotNull('nilai')->avg('nilai');

        // Calculate overall average
        $scores = array_filter([$avgTugas, $avgUjian]);
        return $scores ? round(array_sum($scores) / count($scores), 1) : 0;
    }

    /**
     * Get top performing students with real data
     */
    private function getTopPerformingStudents($assignedData)
    {
        $students = \App\Models\User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->with(['kelas'])
            ->get()
            ->map(function($student) use ($assignedData) {
                // Calculate average score for this student
                $avgTugas = \App\Models\UserTugas::where('user_id', $student->id)
                    ->whereHas('tugas', function($query) use ($assignedData) {
                        $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
                    })
                    ->whereNotNull('nilai')
                    ->avg('nilai');

                $avgUjian = \App\Models\UserUjian::where('user_id', $student->id)
                    ->whereHas('ujian', function($query) use ($assignedData) {
                        $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
                    })
                    ->whereNotNull('nilai')
                    ->avg('nilai');

                $scores = array_filter([$avgTugas, $avgUjian]);
                $avgScore = $scores ? round(array_sum($scores) / count($scores), 1) : 0;

                return (object)[
                    'name' => $student->name,
                    'class_name' => $student->kelas->name ?? 'Unknown',
                    'avg_score' => $avgScore
                ];
            })
            ->filter(function($student) {
                return $student->avg_score > 0;
            })
            ->sortByDesc('avg_score')
            ->take(4);

        return $students;
    }

    /**
     * Get recent tasks with real submission data
     */
    private function getRecentTasksWithSubmissions($assignedData)
    {
        return \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($task) {
                // Get real submission count
                $submissionCount = \App\Models\UserTugas::where('tugas_id', $task->id)
                    ->whereIn('status', ['submitted', 'completed', 'graded'])
                    ->count();

                // Get total students in the class
                $totalStudents = \App\Models\User::where('kelas_id', $task->kelasMapel->kelas_id)
                    ->where('roles_id', 4)
                    ->count();

                return (object)[
                    'title' => $task->name,
                    'type' => $this->getTaskTypeName($task->tipe),
                    'submission_count' => $submissionCount,
                    'total_students' => $totalStudents,
                    'status' => $task->due > now() ? 'pending' : 'completed'
                ];
            });
    }

    /**
     * Get task type name
     */
    private function getTaskTypeName($tipe)
    {
        $types = [
            1 => 'multiple_choice',
            2 => 'essay',
            3 => 'mandiri',
            4 => 'kelompok'
        ];
        return $types[$tipe] ?? 'unknown';
    }

    /**
     * API endpoint for real-time analytics data
     */
    public function getTeacherAnalyticsData()
    {
        $user = auth()->user();
        
        // Get teacher's assigned classes and subjects
        $assignedData = $this->getTeacherAssignedData(request());
        
        // Calculate all analytics data
        $totalStudents = \App\Models\User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->count();
            
        $totalTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        $totalExams = \App\Models\Ujian::whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids'])->count();
        
        $avgScore = $this->calculateAverageScore($assignedData);
        $topStudents = $this->getTopPerformingStudents($assignedData);
        $recentTasks = $this->getRecentTasksWithSubmissions($assignedData);
        
        // Get grade distribution
        $gradeDistribution = $this->getGradeDistribution($assignedData);
        
        // Get class performance
        $classPerformance = $this->getClassPerformance($assignedData);
        
        return response()->json([
            'totalStudents' => $totalStudents,
            'totalTasks' => $totalTasks,
            'totalExams' => $totalExams,
            'avgScore' => $avgScore,
            'topStudents' => $topStudents,
            'recentTasks' => $recentTasks,
            'gradeDistribution' => $gradeDistribution,
            'classPerformance' => $classPerformance,
            'lastUpdated' => now()->format('H:i:s')
        ]);
    }

    /**
     * Get grade distribution (A, B, C, D, E)
     */
    private function getGradeDistribution($assignedData)
    {
        // Get all scores from user_tugas
        $tugasScores = \App\Models\UserTugas::whereHas('tugas', function($query) use ($assignedData) {
            $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        })->whereNotNull('nilai')->pluck('nilai');

        // Get all scores from user_ujian
        $ujianScores = \App\Models\UserUjian::whereHas('ujian', function($query) use ($assignedData) {
            $query->whereIn('kelas_mapel_id', $assignedData['kelas_mapel_ids']);
        })->whereNotNull('nilai')->pluck('nilai');

        $allScores = $tugasScores->merge($ujianScores);
        
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
        
        foreach ($allScores as $score) {
            if ($score >= 90) $distribution['A']++;
            elseif ($score >= 80) $distribution['B']++;
            elseif ($score >= 70) $distribution['C']++;
            elseif ($score >= 60) $distribution['D']++;
            else $distribution['E']++;
        }
        
        return $distribution;
    }

    /**
     * Get class performance data
     */
    private function getClassPerformance($assignedData)
    {
        $classes = \App\Models\Kelas::whereIn('id', $assignedData['kelas_ids'])->get();
        
        return $classes->map(function($class) use ($assignedData) {
            // Get class-specific kelas_mapel_ids
            $classKelasMapelIds = \App\Models\KelasMapel::where('kelas_id', $class->id)
                ->whereIn('id', $assignedData['kelas_mapel_ids'])
                ->pluck('id');

            // Calculate average score for this class
            $avgTugas = \App\Models\UserTugas::whereHas('tugas', function($query) use ($classKelasMapelIds) {
                $query->whereIn('kelas_mapel_id', $classKelasMapelIds);
            })->whereNotNull('nilai')->avg('nilai');

            $avgUjian = \App\Models\UserUjian::whereHas('ujian', function($query) use ($classKelasMapelIds) {
                $query->whereIn('kelas_mapel_id', $classKelasMapelIds);
            })->whereNotNull('nilai')->avg('nilai');

            $scores = array_filter([$avgTugas, $avgUjian]);
            $avgScore = $scores ? round(array_sum($scores) / count($scores), 1) : 0;

            return [
                'class_name' => $class->name,
                'avg_score' => $avgScore
            ];
        });
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
    public function viewStudentPushNotification()
    {
        $user = auth()->user();
        
        // Get notifications for this student
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate notification counts
        $totalNotifications = Notification::where('user_id', $user->id)->count();
        $readNotifications = Notification::where('user_id', $user->id)->where('is_read', true)->count();
        $unreadNotifications = Notification::where('user_id', $user->id)->where('is_read', false)->count();
        $urgentNotifications = Notification::where('user_id', $user->id)->where('type', 'error')->count();
        
        return view('student.push-notification', [
            'title' => 'Notifikasi Saya',
            'user' => $user,
            'notifications' => $notifications,
            'totalNotifications' => $totalNotifications,
            'readNotifications' => $readNotifications,
            'unreadNotifications' => $unreadNotifications,
            'urgentNotifications' => $urgentNotifications
        ]);
    }

    /**
     * Send student push notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendStudentPushNotification(Request $request)
    {
        // Implementation for sending push notifications
        return redirect()->route('student.push-notification')
            ->with('success', 'Notifikasi berhasil dikirim');
    }

    /**
     * Filter student push notifications.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function filterStudentPushNotifications(Request $request)
    {
        $user = auth()->user();
        return view('student.push-notification', [
            'title' => 'Push Notifikasi Student',
            'user' => $user,
            'filters' => $request->all()
        ]);
    }

    /**
     * View student IoT management.
     *
     * @return \Illuminate\View\View
     */
    public function viewStudentIotManagement()
    {
        $user = auth()->user();
        return view('student.iot', [
            'title' => 'Penelitian IoT',
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
        
        // Get student's tasks
        $tasks = collect(); // Initialize empty collection
        $totalTasks = 0;
        $pendingTasks = 0;
        $completedTasks = 0;
        $overdueTasks = 0;
        $subjects = collect();
        
        try {
            // Get student's class and subjects
            if ($user->KelasMapel) {
                $subjects = $user->KelasMapel->map(function($kelasMapel) {
                    return $kelasMapel->Mapel;
                })->unique('id');
                
                // Get tasks for student's subjects
                $taskIds = $user->KelasMapel->pluck('id');
                $tasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $taskIds)
                    ->where('isHidden', 0)
                    ->with(['KelasMapel.Kelas', 'KelasMapel.Mapel', 'submissions' => function($query) use ($user) {
                        $query->where('user_id', $user->id);
                    }])
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $totalTasks = $tasks->count();
                $pendingTasks = $tasks->filter(function($task) {
                    return $task->submissions->count() == 0 && (!$task->due || $task->due > now());
                })->count();
                $completedTasks = $tasks->filter(function($task) {
                    return $task->submissions->count() > 0;
                })->count();
                $overdueTasks = $tasks->filter(function($task) {
                    return $task->submissions->count() == 0 && $task->due && $task->due < now();
                })->count();
            }
        } catch (\Exception $e) {
            // Handle error gracefully
            \Log::error('Error in viewStudentTaskManagement: ' . $e->getMessage());
        }
        
        return view('student.task-management', [
            'title' => 'Manajemen Tugas Student',
            'user' => $user,
            'tasks' => $tasks,
            'subjects' => $subjects,
            'totalTasks' => $totalTasks,
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'filters' => request()->only(['filter_subject', 'filter_status', 'filter_difficulty'])
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
        return redirect()->route('student.material-management')
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
     * Export admin data.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDataExport()
    {
        try {
            // Get all data for export
            $users = \App\Models\User::with('roles')->get();
            $classes = \App\Models\Kelas::all();
            $subjects = \App\Models\Mapel::all();
            $tasks = \App\Models\Tugas::with(['kelasMapel.mapel', 'kelasMapel.pengajar'])->get();
            $exams = \App\Models\Ujian::with(['kelasMapel.mapel', 'kelasMapel.pengajar'])->get();
            
            // Create CSV content
            $csvContent = "Data Export - " . now()->format('Y-m-d H:i:s') . "\n\n";
            
            // Users data
            $csvContent .= "USERS\n";
            $csvContent .= "ID,Name,Email,Role,Created At\n";
            foreach ($users as $user) {
                $csvContent .= $user->id . "," . 
                              '"' . $user->name . '",' . 
                              '"' . $user->email . '",' . 
                              '"' . ($user->roles->name ?? 'N/A') . '",' . 
                              $user->created_at->format('Y-m-d H:i:s') . "\n";
            }
            
            $csvContent .= "\nCLASSES\n";
            $csvContent .= "ID,Name,Description,Created At\n";
            foreach ($classes as $class) {
                $csvContent .= $class->id . "," . 
                              '"' . $class->name . '",' . 
                              '"' . ($class->description ?? '') . '",' . 
                              $class->created_at->format('Y-m-d H:i:s') . "\n";
            }
            
            $csvContent .= "\nSUBJECTS\n";
            $csvContent .= "ID,Name,Description,Created At\n";
            foreach ($subjects as $subject) {
                $csvContent .= $subject->id . "," . 
                              '"' . $subject->name . '",' . 
                              '"' . ($subject->description ?? '') . '",' . 
                              $subject->created_at->format('Y-m-d H:i:s') . "\n";
            }
            
            $csvContent .= "\nTASKS\n";
            $csvContent .= "ID,Name,Subject,Teacher,Type,Due Date,Created At\n";
            foreach ($tasks as $task) {
                $csvContent .= $task->id . "," . 
                              '"' . $task->name . '",' . 
                              '"' . ($task->kelasMapel->mapel->name ?? 'N/A') . '",' . 
                              '"' . ($task->kelasMapel->pengajar->first()->name ?? 'N/A') . '",' . 
                              '"' . ($task->tipe ?? 'Essay') . '",' . 
                              $task->due->format('Y-m-d H:i:s') . "," . 
                              $task->created_at->format('Y-m-d H:i:s') . "\n";
            }
            
            $csvContent .= "\nEXAMS\n";
            $csvContent .= "ID,Name,Subject,Teacher,Type,Duration,Created At\n";
            foreach ($exams as $exam) {
                $csvContent .= $exam->id . "," . 
                              '"' . $exam->name . '",' . 
                              '"' . ($exam->kelasMapel->mapel->name ?? 'N/A') . '",' . 
                              '"' . ($exam->kelasMapel->pengajar->first()->name ?? 'N/A') . '",' . 
                              '"' . ($exam->tipe ?? 'Essay') . '",' . 
                              $exam->duration . " minutes," . 
                              $exam->created_at->format('Y-m-d H:i:s') . "\n";
            }
            
            // Set headers for CSV download
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="admin_data_export_' . now()->format('Y-m-d_H-i-s') . '.csv"',
            ];
            
            return response($csvContent, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Backup admin data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminDataBackup()
    {
        try {
            // Simple backup implementation - in real scenario, you might want to use database backup tools
            $backupData = [
                'timestamp' => now()->toISOString(),
                'users_count' => \App\Models\User::count(),
                'classes_count' => \App\Models\Kelas::count(),
                'subjects_count' => \App\Models\Mapel::count(),
                'tasks_count' => \App\Models\Tugas::count(),
                'exams_count' => \App\Models\Ujian::count(),
                'status' => 'success'
            ];
            
            // In a real implementation, you would save this to a backup file or database
            \Log::info('Admin data backup created', $backupData);
            
            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat',
                'data' => $backupData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear admin cache.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminCacheClear()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout from all devices.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLogoutAll()
    {
        try {
            $user = auth()->user();
            
            // Revoke all tokens for the user (if using Laravel Sanctum)
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
            
            // Clear all sessions for the user
            \DB::table('sessions')->where('user_id', $user->id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout dari semua perangkat'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete admin account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminAccountDelete()
    {
        try {
            $user = auth()->user();
            
            // Delete user account
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display IoT Debug page for Super Admin
     *
     * @return \Illuminate\View\View
     */
    public function viewSuperAdminIotDebug()
    {
        $user = auth()->user();
        
        // Check if user is superadmin
        if ($user->roles_id != 1) {
            abort(403, 'Unauthorized access. Hanya superadmin yang dapat mengakses halaman debug IoT.');
        }
        
        // Get recent IoT devices for debugging
        $devices = \App\Models\IotDevice::with('latestSensorData')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Get recent sensor data for debugging
        $recentSensorData = \App\Models\IotSensorData::with(['device', 'kelas', 'user'])
            ->latest('measured_at')
            ->limit(20)
            ->get();
            
        // Get recent IoT readings for debugging
        $recentReadings = \App\Models\IotReading::with(['student', 'kelas'])
            ->latest('timestamp')
            ->limit(20)
            ->get();
        
        return view('superadmin.iot-debug', [
            'title' => 'IoT Debug & Testing',
            'user' => $user,
            'devices' => $devices,
            'recentSensorData' => $recentSensorData,
            'recentReadings' => $recentReadings
        ]);
    }

    /**
     * ESP8266 Status Views for All Roles
     */
    public function viewSuperAdminEsp8266Status()
    {
        $user = auth()->user();
        return view('components.esp8266-status', compact('user'));
    }

    public function viewAdminEsp8266Status()
    {
        $user = auth()->user();
        return view('components.esp8266-status', compact('user'));
    }

    public function viewTeacherEsp8266Status()
    {
        $user = auth()->user();
        return view('components.esp8266-status', compact('user'));
    }

    public function viewStudentEsp8266Status()
    {
        $user = auth()->user();
        return view('components.esp8266-status', compact('user'));
    }

    /**
     * Edit Teacher Material
     */
    public function editTeacherMaterial($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        
        // Check if teacher owns this material or has access
        if ($material->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini');
        }
        
        // Get available subjects and classes for the teacher
        $subjects = $this->getTeacherAvailableSubjects(request());
        $classes = $this->getTeacherAvailableClasses(request());
        
        return view('material-edit', [
            'title' => 'Edit Materi',
            'user' => $user,
            'userRole' => 'teacher',
            'material' => $material,
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }

    /**
     * Update Teacher Material
     */
    public function updateTeacherMaterial(Request $request, $id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        
        // Check if teacher owns this material
        if ($material->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini');
        }
        
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
            $filePath = $material->file_path;
            $fileName = $material->file_name;
            $fileSize = $material->file_size;
            $fileType = $material->file_type;
            
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($material->file_path) {
                    \Storage::disk('public')->delete($material->file_path);
                }
                
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                $filePath = $file->store('materials', 'public');
            }

            // Handle thumbnail upload
            $thumbnailPath = $material->thumbnail_path;
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($material->thumbnail_path) {
                    \Storage::disk('public')->delete($material->thumbnail_path);
                }
                
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
            }

            // Update the material
            $material->update([
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
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
            ]);

            return redirect()->route('teacher.material-management')
                ->with('success', 'Materi berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui materi: ' . $e->getMessage());
        }
    }

    /**
     * Delete Teacher Material
     */
    public function deleteTeacherMaterial($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        
        // Check if teacher owns this material
        if ($material->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus materi ini');
        }
        
        try {
            // Delete files
            if ($material->file_path) {
                \Storage::disk('public')->delete($material->file_path);
            }
            if ($material->thumbnail_path) {
                \Storage::disk('public')->delete($material->thumbnail_path);
            }

            $material->delete();

            return redirect()->route('teacher.material-management')
                ->with('success', 'Materi berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }

    /**
     * Edit Super Admin Material
     */
    public function editSuperAdminMaterial($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        $subjects = \App\Models\Mapel::all();
        $classes = \App\Models\Kelas::all();
        
        return view('material-edit', [
            'title' => 'Edit Materi',
            'user' => $user,
            'userRole' => 'superadmin',
            'material' => $material,
            'subjects' => $subjects,
            'classes' => $classes
        ]);
    }

    /**
     * Update Super Admin Material
     */
    public function updateSuperAdminMaterial(Request $request, $id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        
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
            $filePath = $material->file_path;
            $fileName = $material->file_name;
            $fileSize = $material->file_size;
            $fileType = $material->file_type;
            
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($material->file_path) {
                    \Storage::disk('public')->delete($material->file_path);
                }
                
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                $filePath = $file->store('materials', 'public');
            }

            // Handle thumbnail upload
            $thumbnailPath = $material->thumbnail_path;
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($material->thumbnail_path) {
                    \Storage::disk('public')->delete($material->thumbnail_path);
                }
                
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public');
            }

            // Update the material
            $material->update([
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
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
            ]);

            return redirect()->route('superadmin.material-management')
                ->with('success', 'Materi berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui materi: ' . $e->getMessage());
        }
    }

    /**
     * Delete Super Admin Material
     */
    public function deleteSuperAdminMaterial($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::findOrFail($id);
        
        try {
            // Delete files
            if ($material->file_path) {
                \Storage::disk('public')->delete($material->file_path);
            }
            if ($material->thumbnail_path) {
                \Storage::disk('public')->delete($material->thumbnail_path);
            }

            $material->delete();

            return redirect()->route('superadmin.material-management')
                ->with('success', 'Materi berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus materi: ' . $e->getMessage());
        }
    }

    /**
     * View Super Admin Material Detail
     */
    public function viewSuperAdminMaterialDetail($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::with(['class', 'subject', 'creator'])->findOrFail($id);
        
        // Get users who have read this material (without year/student filter)
        $readers = \DB::table('material_views')
            ->join('users', 'material_views.user_id', '=', 'users.id')
            ->join('roles', 'users.roles_id', '=', 'roles.id')
            ->where('material_views.material_id', $id)
            ->select('users.*', 'roles.name as role_name', 'material_views.created_at as read_at')
            ->orderBy('material_views.created_at', 'desc')
            ->get();

        return view('superadmin.material-detail', compact('material', 'readers', 'user'));
    }

    /**
     * View Admin Material Detail
     */
    public function viewAdminMaterialDetail($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::with(['class', 'subject', 'creator'])->findOrFail($id);
        
        // Get users who have read this material (without year/student filter)
        $readers = \DB::table('material_views')
            ->join('users', 'material_views.user_id', '=', 'users.id')
            ->join('roles', 'users.roles_id', '=', 'roles.id')
            ->where('material_views.material_id', $id)
            ->select('users.*', 'roles.name as role_name', 'material_views.created_at as read_at')
            ->orderBy('material_views.created_at', 'desc')
            ->get();

        return view('admin.material-detail', compact('material', 'readers', 'user'));
    }

    /**
     * View Teacher Material Detail
     */
    public function viewTeacherMaterialDetail($id)
    {
        $user = auth()->user();
        $material = \App\Models\Material::with(['class', 'subject', 'creator'])->findOrFail($id);
        
        // Get users who have read this material (without year/student filter)
        $readers = \DB::table('material_views')
            ->join('users', 'material_views.user_id', '=', 'users.id')
            ->join('roles', 'users.roles_id', '=', 'roles.id')
            ->where('material_views.material_id', $id)
            ->select('users.*', 'roles.name as role_name', 'material_views.created_at as read_at')
            ->orderBy('material_views.created_at', 'desc')
            ->get();

        return view('teacher.material-detail', compact('material', 'readers', 'user'));
    }

    /**
     * Prepare chart data for teacher reports analytics
     */
    private function prepareTeacherReportChartData($assignedData, $filters)
    {
        // Monthly trend data (6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $avgScore = \App\Models\UserTugas::whereIn('status', ['completed', 'graded'])
                ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                    $q->whereIn('id', $assignedData['kelas_mapel_ids']);
                })
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->avg('nilai') ?? 0;
            
            $monthlyTrend[] = [
                'month' => $date->format('M Y'),
                'avg_score' => round($avgScore, 1)
            ];
        }

        // Completion rate by subject
        $completionBySubject = \App\Models\Mapel::whereIn('id', $assignedData['mapel_ids'])
            ->with(['kelasMapel' => function($q) use ($assignedData) {
                $q->whereIn('id', $assignedData['kelas_mapel_ids']);
            }])
            ->get()
            ->map(function($subject) {
                $totalTasks = \App\Models\Tugas::whereIn('kelas_mapel_id', $subject->kelasMapel->pluck('id'))->count();
                $completedTasks = \App\Models\UserTugas::whereIn('status', ['completed', 'graded'])
                    ->whereHas('tugas.kelasMapel', function($q) use ($subject) {
                        $q->where('mapel_id', $subject->id);
                    })
                    ->count();
                
                return [
                    'subject' => $subject->nama_mapel,
                    'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0
                ];
            });

        // Task status distribution
        $taskStatusDistribution = [
            'submitted' => \App\Models\UserTugas::whereIn('status', ['submitted', 'completed', 'graded'])
                ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                    $q->whereIn('id', $assignedData['kelas_mapel_ids']);
                })
                ->count(),
            'pending' => \App\Models\UserTugas::where('status', 'pending')
                ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                    $q->whereIn('id', $assignedData['kelas_mapel_ids']);
                })
                ->count(),
            'late' => \App\Models\UserTugas::where('status', 'pending')
                ->whereHas('tugas', function($q) {
                    $q->where('due', '<', now());
                })
                ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                    $q->whereIn('id', $assignedData['kelas_mapel_ids']);
                })
                ->count()
        ];

        // Top 10 students
        $topStudents = \App\Models\User::whereIn('kelas_id', $assignedData['kelas_ids'])
            ->where('roles_id', 4)
            ->with('kelas')
            ->get()
            ->map(function($student) use ($assignedData) {
                $avgScore = \App\Models\UserTugas::where('user_id', $student->id)
                    ->whereIn('status', ['completed', 'graded'])
                    ->whereHas('tugas.kelasMapel', function($q) use ($assignedData) {
                        $q->whereIn('id', $assignedData['kelas_mapel_ids']);
                    })
                    ->avg('nilai') ?? 0;
                
                return [
                    'name' => $student->name,
                    'class' => $student->kelas->nama_kelas ?? 'N/A',
                    'avg_score' => round($avgScore, 1)
                ];
            })
            ->sortByDesc('avg_score')
            ->take(10)
            ->values();

        return [
            'monthly_trend' => $monthlyTrend,
            'completion_by_subject' => $completionBySubject,
            'task_status_distribution' => $taskStatusDistribution,
            'top_students' => $topStudents
        ];
    }
}

