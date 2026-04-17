<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('FORCE_HTTPS', false)) {
            $request->setTrustedProxies([$request->ip()], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);

            // Only redirect GET requests, not POST (form submissions)
            if ($request->isMethod('GET') && !$request->secure() && $request->header('X-Forwarded-Proto') !== 'https') {
                return redirect()->secure($request->getRequestUri());
            }

            // Force Laravel to generate HTTPS URLs
            \URL::forceScheme('https');
        }

        return $next($request);
    }
}
