<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Redirect berdasarkan role
                switch ($user->roles_id) {
                    case 1: // Super Admin
                        return redirect()->route('superadmin.dashboard');
                    case 2: // Admin
                        return redirect()->route('admin.dashboard');
                    case 3: // Teacher/Pengajar
                        return redirect()->route('teacher.dashboard');
                    case 4: // Student/Siswa
                    default:
                        return redirect()->route('student.dashboard');
                }
            }
        }

        return $next($request);
    }
}
