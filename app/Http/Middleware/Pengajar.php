<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Pengajar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user is pengajar/teacher (roles_id == 3)
        if (Auth::user()->roles_id == 3) {
            return $next($request);
        } else {
            // Redirect to appropriate dashboard based on user role
            $userRoleId = Auth::user()->roles_id;
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
    }
}
