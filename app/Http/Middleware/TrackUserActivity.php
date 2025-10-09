<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update last activity only if it's been more than 1 minute since last update
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) >= 1) {
                $user->updateLastActivity();
            }
        }

        return $next($request);
    }
}
