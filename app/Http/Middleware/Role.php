<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        $userRoleId = $user->roles_id;

        // Map role IDs to role names
        $roleMap = [
            1 => ['superadmin'], // Superadmin only
            2 => ['admin'], // Admin can only access admin routes
            3 => ['teacher', 'pengajar'], // Teacher can access teacher routes
            4 => ['student', 'siswa'], // Student can access student routes
        ];

        $userRoles = $roleMap[$userRoleId] ?? [];

        // Check if user has any of the required roles
        $hasRole = false;
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            // Redirect to appropriate dashboard based on user role instead of showing 403
            $dashboardRoutes = [
                1 => 'superadmin.dashboard',
                2 => 'admin.dashboard', 
                3 => 'teacher.dashboard',
                4 => 'student.dashboard'
            ];
            
            $dashboardRoute = $dashboardRoutes[$userRoleId] ?? 'dashboard';
            
            return redirect()->route($dashboardRoute)
                ->with('error', 'Akses ditolak. Anda dialihkan ke dashboard Anda.');
        }

        return $next($request);
    }
}
