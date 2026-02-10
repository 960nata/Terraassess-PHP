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

        // Get teacher's assigned classes and subjects
        $assignedKelasMapel = EditorAccess::where('user_id', $user->id)
            ->with(['kelasMapel.kelas', 'kelasMapel.mapel'])
            ->get()
            ->pluck('kelas_mapel_id')
            ->toArray();

        // If teacher has no assignments, redirect to access denied
        if (empty($assignedKelasMapel)) {
            return redirect()->route('access-denied')
                ->with('error', 'Anda belum memiliki akses ke kelas atau mata pelajaran apapun. Silakan hubungi administrator.');
        }

        // Add teacher's assigned classes to request for use in controllers
        $request->merge([
            'teacher_assigned_kelas_mapel' => $assignedKelasMapel,
            'teacher_assigned_kelas' => EditorAccess::where('user_id', $user->id)
                ->with('kelasMapel.kelas')
                ->get()
                ->pluck('kelasMapel.kelas_id')
                ->unique()
                ->toArray(),
            'teacher_assigned_mapel' => EditorAccess::where('user_id', $user->id)
                ->with('kelasMapel.mapel')
                ->get()
                ->pluck('kelasMapel.mapel_id')
                ->unique()
                ->toArray()
        ]);

        return $next($request);
    }
}
