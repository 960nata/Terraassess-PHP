<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Guru
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is guru/teacher (roles_id == 3)
        if (auth()->user()->roles_id != 3) {
            // Redirect to appropriate dashboard based on user role
            $userRoleId = auth()->user()->roles_id;
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
