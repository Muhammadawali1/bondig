<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user exists and is active
        if (!$user || $user->status !== 'active') {
            // Log out the user if they're logged in but inactive
            if ($user) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return $request->expectsJson()
                ? abort(403, 'Your account is not active. Please contact administrator.')
                : Redirect::route('login')
                    ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Check if user account is suspended
        if ($user->status === 'suspended') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $request->expectsJson()
                ? abort(403, 'Your account has been suspended.')
                : Redirect::route('login')
                    ->with('error', 'Akun Anda telah ditangguhkan. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
