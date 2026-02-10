<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tugas;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Display the guru dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $totalPengajar = User::where('roles_id', 2)->count();
        $totalSiswa = User::where('roles_id', 3)->count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $totalTugas = Tugas::count();
        $totalUjian = Ujian::count();
        
        // Get recent activities (mock data for now)
        $recentTugas = Tugas::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($tugas) {
                return [
                    'id' => $tugas->id,
                    'title' => $tugas->judul,
                    'kelas' => $tugas->kelasMapel->kelas->name ?? 'N/A',
                    'created_at' => $tugas->created_at->format('d M Y'),
                    'status' => 'active'
                ];
            });
            
        $recentUjian = Ujian::with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($ujian) {
                return [
                    'id' => $ujian->id,
                    'title' => $ujian->judul,
                    'kelas' => $ujian->kelasMapel->kelas->name ?? 'N/A',
                    'start_date' => $ujian->tanggal_mulai ? $ujian->tanggal_mulai->format('d M Y') : 'N/A',
                    'duration' => $ujian->durasi ?? 'N/A',
                    'status' => 'active'
                ];
            });
        
        return view('dashboard.guru-dashboard', compact(
            'totalPengajar',
            'totalSiswa', 
            'totalKelas',
            'totalMapel',
            'totalTugas',
            'totalUjian',
            'recentTugas',
            'recentUjian'
        ));
    }
    
    /**
     * Display the data pengajar page.
     */
    public function dataPengajar()
    {
        $pengajar = User::where('roles_id', 2)
            ->with(['contact', 'kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->map(function($user) {
                $subjects = $user->kelasMapel->pluck('mapel.name')->unique()->toArray();
                $classes = $user->kelasMapel->pluck('kelas.name')->unique()->toArray();
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'subjects' => $subjects,
                    'classes' => $classes,
                    'status' => 'active',
                    'last_login' => $user->last_login_at ?? null
                ];
            });
            
        $totalPengajar = $pengajar->count();
        $activePengajar = $pengajar->where('status', 'active')->count();
        $pendingPengajar = 0; // Mock data
        $totalSubjects = Mapel::count();
        
        return view('guru.data-pengajar', compact(
            'pengajar',
            'totalPengajar',
            'activePengajar',
            'pendingPengajar',
            'totalSubjects'
        ));
    }
    
    /**
     * Display the data siswa page.
     */
    public function dataSiswa()
    {
        $siswa = User::where('roles_id', 3)
            ->with(['kelas', 'contact'])
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nis' => $user->nis ?? 'N/A',
                    'kelas' => $user->kelas->name ?? 'N/A',
                    'status' => 'active',
                    'average_score' => rand(60, 95), // Mock data
                    'last_login' => $user->last_login_at ?? null
                ];
            });
            
        $totalSiswa = $siswa->count();
        $activeSiswa = $siswa->where('status', 'active')->count();
        $graduatedSiswa = 0; // Mock data
        $totalKelas = Kelas::count();
        
        return view('guru.data-siswa', compact(
            'siswa',
            'totalSiswa',
            'activeSiswa',
            'graduatedSiswa',
            'totalKelas'
        ));
    }
    
    /**
     * Display the data kelas page.
     */
    public function dataKelas()
    {
        $kelas = Kelas::with(['siswa', 'waliKelas'])
            ->get()
            ->map(function($kelas) {
                return [
                    'id' => $kelas->id,
                    'name' => $kelas->name,
                    'level' => $kelas->level ?? 'x',
                    'type' => $kelas->type ?? 'ipa',
                    'student_count' => $kelas->siswa->count(),
                    'wali_kelas' => $kelas->waliKelas ? [
                        'id' => $kelas->waliKelas->id,
                        'name' => $kelas->waliKelas->name
                    ] : null,
                    'status' => 'active'
                ];
            });
            
        $totalKelas = $kelas->count();
        $totalSiswa = User::where('roles_id', 3)->count();
        $totalPengajar = User::where('roles_id', 2)->count();
        $totalMapel = Mapel::count();
        
        return view('guru.data-kelas', compact(
            'kelas',
            'totalKelas',
            'totalSiswa',
            'totalPengajar',
            'totalMapel'
        ));
    }
    
    /**
     * Display the data mapel page.
     */
    public function dataMapel()
    {
        $mapel = Mapel::with(['pengajar', 'kelasMapel.kelas'])
            ->get()
            ->map(function($mapel) {
                $pengajar = $mapel->pengajar ? [
                    'id' => $mapel->pengajar->id,
                    'name' => $mapel->pengajar->name
                ] : null;
                
                return [
                    'id' => $mapel->id,
                    'name' => $mapel->name,
                    'code' => $mapel->code ?? 'N/A',
                    'category' => $mapel->category ?? 'umum',
                    'level' => $mapel->level ?? 'all',
                    'pengajar' => $pengajar,
                    'kelas_count' => $mapel->kelasMapel->count(),
                    'status' => 'active'
                ];
            });
            
        $totalMapel = $mapel->count();
        $totalPengajar = User::where('roles_id', 2)->count();
        $totalKelas = Kelas::count();
        $totalJadwal = 0; // Mock data
        
        return view('guru.data-mapel', compact(
            'mapel',
            'totalMapel',
            'totalPengajar',
            'totalKelas',
            'totalJadwal'
        ));
    }
}
