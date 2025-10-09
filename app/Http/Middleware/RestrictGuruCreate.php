<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictGuruCreate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is guru (roles_id == 2)
        if (auth()->check() && auth()->user()->roles_id == 2) {
            // Get the route name
            $routeName = $request->route()->getName();
            
            // List of restricted routes for guru
            $restrictedRoutes = [
                'tambahKelas',
                'validateNamaKelas',
                'tambahPengajar',
                'validateDataPengajar',
                'tambahKelasPengajar',
                'validateDataPengajarKelas',
                'tambahSiswa',
                'validateDataSiswa',
                'tambahKelasSiswa',
                'validateDataSiswaKelas',
                'createSuperAdminClass',
                'createSuperAdminSubject',
                'createSuperAdminUser',
                'createAdmin',
                'createTeacher',
                'createStudent',
            ];
            
            // Check if current route is restricted
            if (in_array($routeName, $restrictedRoutes)) {
                return redirect()->route('access-denied');
            }
            
            // Check for create methods in URL
            $path = $request->path();
            if (str_contains($path, 'create') || str_contains($path, 'tambah') || str_contains($path, 'add')) {
                return redirect()->route('access-denied');
            }
        }
        
        return $next($request);
    }
}
