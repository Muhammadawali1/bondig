<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DivisiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Check if user is authenticated
        if (!$user) {
            Log::warning('Divisi access - not authenticated', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            return redirect('/login');
        }

        // For pegawai and atasan, ensure they can only access their own divisi data
        if (in_array($user->role, ['pegawai', 'atasan'])) {
            // Check if accessing bon data
            if ($request->route('id')) {
                $bonId = $request->route('id');
                $bon = \App\Models\BonBarang::find($bonId);
                
                if ($bon && $bon->divisi !== $user->divisi) {
                    Log::warning('Divisi access violation - wrong divisi', [
                        'user_id' => $user->id,
                        'user_divisi' => $user->divisi,
                        'bon_divisi' => $bon->divisi,
                        'bon_id' => $bonId,
                        'ip' => $request->ip(),
                        'url' => $request->fullUrl(),
                    ]);
                    
                    abort(403, 'Anda tidak memiliki akses ke data divisi lain.');
                }
            }
        }

        return $next($request);
    }
}
