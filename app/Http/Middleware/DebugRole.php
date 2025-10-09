<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DebugRole
{
    /**
     * Handle an incoming request.
     * 
     * This middleware provides detailed debugging information for role-based access.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            \Log::warning('DebugRole: User not authenticated', [
                'url' => $request->url(),
                'method' => $request->method()
            ]);
            return redirect('/');
        }

        $user = Auth::user();
        $userRoleId = $user->roles_id;

        // Map role IDs to role names
        $roleMap = [
            1 => ['superadmin'],
            2 => ['admin'], 
            3 => ['teacher', 'pengajar'],
            4 => ['student', 'siswa'],
        ];

        $userRoles = $roleMap[$userRoleId] ?? [];

        // Check if user has any of the required roles
        $hasRole = false;
        $matchedRoles = [];
        
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                $hasRole = true;
                $matchedRoles[] = $role;
                break;
            }
        }

        // Detailed logging
        \Log::info('DebugRole: Access check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role_id' => $userRoleId,
            'user_roles' => $userRoles,
            'required_roles' => $roles,
            'matched_roles' => $matchedRoles,
            'has_access' => $hasRole,
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        if (!$hasRole) {
            // Log detailed failure information
            \Log::warning('DebugRole: Access denied', [
                'user_id' => $user->id,
                'user_role_id' => $userRoleId,
                'user_roles' => $userRoles,
                'required_roles' => $roles,
                'url' => $request->url()
            ]);

            // Redirect to appropriate dashboard
            $dashboardRoutes = [
                1 => 'superadmin.dashboard',
                2 => 'admin.dashboard', 
                3 => 'teacher.dashboard',
                4 => 'student.dashboard'
            ];
            
            $dashboardRoute = $dashboardRoutes[$userRoleId] ?? 'student.dashboard';
            
            return redirect()->route($dashboardRoute)
                ->with('error', 'Akses ditolak. Anda dialihkan ke dashboard Anda.');
        }

        return $next($request);
    }
}
