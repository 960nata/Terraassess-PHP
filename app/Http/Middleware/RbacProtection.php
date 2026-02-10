<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RbacService;
use Symfony\Component\HttpFoundation\Response;

class RbacProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $component = null): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // If component is specified, check access
        if ($component && !RbacService::hasAccess($component)) {
            return redirect()->back()
                ->with('error', 'Akses Ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.')
                ->with('access_denied', true);
        }

        return $next($request);
    }
}
