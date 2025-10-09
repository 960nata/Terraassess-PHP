<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EditorAccess;

class TeacherAccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Only apply to teachers
        if ($user->roles_id !== 3) {
            return $next($request);
        }

        // Guru memiliki akses penuh ke semua kelas dan mata pelajaran
        // Tidak perlu membatasi berdasarkan assignment
        $allKelasMapel = \App\Models\KelasMapel::all()->pluck('id')->toArray();
        $allKelas = \App\Models\Kelas::all()->pluck('id')->toArray();
        $allMapel = \App\Models\Mapel::all()->pluck('id')->toArray();

        // Add all classes and subjects to request for use in controllers
        $request->merge([
            'teacher_assigned_kelas_mapel' => $allKelasMapel,
            'teacher_assigned_kelas' => $allKelas,
            'teacher_assigned_mapel' => $allMapel
        ]);

        return $next($request);
    }
}
