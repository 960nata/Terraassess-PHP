<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class HandleCsrfError extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // Log the CSRF error
            \Log::warning('CSRF Token Mismatch', [
                'url' => $request->url(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
            ]);

            // If it's an AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'CSRF token mismatch',
                    'message' => 'Session expired. Please refresh the page and try again.',
                    'code' => 419
                ], 419);
            }

            // For regular requests, redirect back with error message
            return redirect()->back()
                ->with('login-error', 'Session expired. Please refresh the page and try again.')
                ->withInput($request->except('password', '_token'));
        }
    }
}
