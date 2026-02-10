<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GranularRbacService;
use Symfony\Component\HttpFoundation\Response;

class GranularRbacProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $component = null, string $action = null): Response
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // If component and action are specified, check granular permission
        if ($component && $action) {
            if (!GranularRbacService::hasPermission($component, $action)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Forbidden',
                        'message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'
                    ], 403);
                }
                
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

        // If only component is specified, check view permission
        if ($component && !$action) {
            if (!GranularRbacService::canView($component)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Forbidden',
                        'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini.'
                    ], 403);
                }
                
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

        return $next($request);
    }
}
