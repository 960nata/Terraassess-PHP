<?php

namespace App\Services;

use App\Models\DataSiswa;
use App\Models\EditorAccess;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\Role;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get dashboard data for admin
     */
    public function getAdminDashboardData()
    {
        return Cache::remember('admin_dashboard_data', 300, function () { // Cache for 5 minutes
            return [
                'totalSiswa' => DataSiswa::count(),
                'totalUserSiswa' => User::where('roles_id', 3)->count(),
                'totalPengajar' => User::where('roles_id', 2)->count(),
                'totalKelas' => Kelas::count(),
                'totalMapel' => Mapel::count(),
                'totalMateri' => Materi::count(),
                'totalTugas' => Tugas::count(),
                'totalUjian' => Ujian::count(),
            ];
        });
    }

    /**
     * Get dashboard data for pengajar
     */
    public function getPengajarDashboardData($userId)
    {
        return Cache::remember("pengajar_dashboard_data_{$userId}", 300, function () use ($userId) {
            $editorAccess = EditorAccess::where('user_id', $userId)->get();
            
            $mapelKelas = [];
            $totalSiswa = 0;
            $totalSiswaUnique = [];
            $kelasMapelId = [];

            foreach ($editorAccess as $access) {
                $kelasMapel = KelasMapel::with(['kelas', 'mapel'])->find($access->kelas_mapel_id);

                if ($kelasMapel) {
                    $mapelID = $kelasMapel->mapel_id;
                    $kelasID = $kelasMapel->kelas_id;

                    // Check if mapel already exists
                    $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                    if ($mapelKey !== false) {
                        $mapelKelas[$mapelKey]['kelas'][] = $kelasMapel->kelas;
                    } else {
                        $mapelKelas[] = [
                            'mapel_id' => $mapelID,
                            'mapel' => $kelasMapel->mapel,
                            'kelas' => [$kelasMapel->kelas],
                        ];
                        $kelasMapelId[] = $kelasMapel->id;
                    }

                    // Count students
                    $siswa = DataSiswa::where('kelas_id', $kelasID)->get();
                    $totalSiswa += $siswa->count();
                    $totalSiswaUnique = array_merge($totalSiswaUnique, $siswa->pluck('id')->toArray());
                }
            }

            $totalSiswaUnique = count(array_unique($totalSiswaUnique));

            return [
                'mapelKelas' => $mapelKelas,
                'totalSiswa' => $totalSiswa,
                'totalSiswaUnique' => $totalSiswaUnique,
                'kelasMapelId' => $kelasMapelId,
                'countKelas' => $editorAccess->count(),
            ];
        });
    }

    /**
     * Get home data for siswa
     */
    public function getSiswaHomeData($userId)
    {
        return Cache::remember("siswa_home_data_{$userId}", 300, function () use ($userId) {
            $user = User::with('kelas')->findOrFail($userId);
            $kelas = $user->kelas;

            $kelasMapel = KelasMapel::with(['mapel', 'editorAccess.user'])
                ->where('kelas_id', $kelas->id)
                ->get();

            $mapelCollection = [];

            foreach ($kelasMapel as $km) {
                $editorAccess = $km->editorAccess->first();
                
                $mapelCollection[] = [
                    'mapel_name' => $km->mapel->name,
                    'mapel_id' => $km->mapel->id,
                    'deskripsi' => $km->mapel->deskripsi,
                    'gambar' => $km->mapel->gambar,
                    'pengajar_id' => $editorAccess ? $editorAccess->user->id : null,
                    'pengajar_name' => $editorAccess ? $editorAccess->user->name : '-',
                ];
            }

            return [
                'user' => $user,
                'kelas' => $kelas,
                'mapelKelas' => $mapelCollection,
            ];
        });
    }

    /**
     * Get assigned classes for user
     */
    public function getAssignedClass($userId, $roleId)
    {
        if ($roleId == 1) { // Admin
            return null;
        } elseif ($roleId == 2) { // Pengajar
            return $this->getPengajarAssignedClass($userId);
        } elseif ($roleId == 3) { // Siswa
            return $this->getSiswaAssignedClass($userId);
        }

        return null;
    }

    /**
     * Get assigned classes for pengajar
     */
    private function getPengajarAssignedClass($userId)
    {
        $editorAccess = EditorAccess::where('user_id', $userId)->get();
        $mapelKelas = [];

        foreach ($editorAccess as $access) {
            $kelasMapel = KelasMapel::with(['kelas', 'mapel'])->find($access->kelas_mapel_id);

            if ($kelasMapel) {
                $mapelID = $kelasMapel->mapel_id;
                $kelasID = $kelasMapel->kelas_id;

                $mapelKey = array_search($mapelID, array_column($mapelKelas, 'mapel_id'));

                if ($mapelKey !== false) {
                    $mapelKelas[$mapelKey]['kelas'][] = $kelasMapel->kelas;
                } else {
                    $mapelKelas[] = [
                        'mapel_id' => $mapelID,
                        'mapel' => $kelasMapel->mapel,
                        'kelas' => [$kelasMapel->kelas],
                    ];
                }
            }
        }

        return $mapelKelas;
    }

    /**
     * Get assigned classes for siswa
     */
    private function getSiswaAssignedClass($userId)
    {
        $user = User::findOrFail($userId);
        $kelasMapelId = KelasMapel::where('kelas_id', $user->kelas_id)->get();
        $mapelKelas = [];

        foreach ($kelasMapelId as $km) {
            $mapelKelas[] = [
                'mapel_id' => $km->mapel_id,
                'mapel' => Mapel::find($km->mapel_id),
                'kelas' => [Kelas::find($km->kelas_id)],
            ];
        }

        return $mapelKelas;
    }

    /**
     * Get roles name
     */
    public function getRolesName($userId)
    {
        $user = User::with('Role')->findOrFail($userId);
        return $user->Role->name;
    }

    /**
     * Clear dashboard cache
     */
    public function clearDashboardCache($userId = null, $roleId = null)
    {
        Cache::forget('admin_dashboard_data');
        
        if ($userId) {
            Cache::forget("pengajar_dashboard_data_{$userId}");
            Cache::forget("siswa_home_data_{$userId}");
        }
    }
}
