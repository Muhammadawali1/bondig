<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            Log::warning('Unauthorized access attempt - not authenticated', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
            return redirect('/login');
        }

        // Check if user has the required role
        if (auth()->user()->role !== $role) {
            Log::warning('Unauthorized access attempt - wrong role', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'required_role' => $role,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Redirect to appropriate dashboard or show 403
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Log successful access
        Log::info('Authorized access', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'user_divisi' => auth()->user()->divisi,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        return $next($request);
    }
}
