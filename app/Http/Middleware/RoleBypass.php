<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleBypass
{
    /**
     * Handle an incoming request.
     * 
     * This middleware bypasses role checking for testing purposes.
     * Use this ONLY for debugging and testing!
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        
        // Log the access attempt for debugging
        \Log::info('RoleBypass: Allowing access', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role_id' => $user->roles_id,
            'requested_roles' => $roles,
            'url' => $request->url()
        ]);

        // Always allow access for testing
        return $next($request);
    }
}
