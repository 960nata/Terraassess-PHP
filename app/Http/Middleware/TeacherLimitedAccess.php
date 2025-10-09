<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherLimitedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        
        // Only apply to teachers accessing superadmin routes
        if ($user->roles_id == 3) {
            $allowedRoutes = [
                'superadmin.task-management',
                'superadmin.exam-management', 
                'superadmin.material-management',
                'superadmin.iot-management',
                'superadmin.tugas',
                'superadmin.tugas.index',
                'superadmin.tugas.create',
                'superadmin.tugas.store',
                'superadmin.tugas.show',
                'superadmin.tugas.edit',
                'superadmin.tugas.update',
                'superadmin.tugas.destroy',
                'superadmin.tugas.feedback',
                'superadmin.tugas.penilaian-kelompok',
                'superadmin.tugas.store-penilaian-kelompok',
                'superadmin.tasks.create.essay',
                'superadmin.tasks.create.group',
                'superadmin.tasks.create.individual',
                'superadmin.tasks.create.multiple-choice',
                'superadmin.exam-management.create',
                'superadmin.exam-management.create-essay',
                'superadmin.exam-management.create-mixed',
                'superadmin.exam-management.create-multiple-choice',
                'superadmin.exam-management.create-essay.store',
                'superadmin.exam-management.create-mixed.store',
                'superadmin.exam-management.create-multiple-choice.store',
                'superadmin.exam-management.filter',
                'superadmin.exam-management.edit',
                'superadmin.exam-management.view',
                'superadmin.exam-management.results',
                'superadmin.exam-management.delete',
                'superadmin.exam-management.publish',
                'superadmin.material-management.create',
                'superadmin.material-management.store',
                'superadmin.material-management.filter',
                'superadmin.iot-management.filter',
                'superadmin.iot-management.register',
                'superadmin.iot-management.new',
                'superadmin.iot-management.store',
                'superadmin.iot-management.edit',
                'superadmin.iot-management.update',
                'superadmin.iot-management.destroy',
                'superadmin.iot-management.connect',
                'superadmin.iot-management.disconnect',
                'superadmin.iot-management.data',
            ];
            
            $routeName = $request->route()->getName();
            
            if (!in_array($routeName, $allowedRoutes)) {
                // Redirect to teacher dashboard instead of showing 403
                return redirect()->route('teacher.dashboard')
                    ->with('error', 'Akses ditolak. Anda dialihkan ke dashboard Anda.');
            }
        }

        return $next($request);
    }
}
