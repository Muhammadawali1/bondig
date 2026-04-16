<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNipIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika belum login
        if (! $user) {
            abort(403, 'Unauthorized');
        }

        // Jika NIP tidak sesuai
        if ($user->nip !== '123456789') {
            abort(403, 'Your NIP is not allowed.');
        }

        return $next($request);
    }
}