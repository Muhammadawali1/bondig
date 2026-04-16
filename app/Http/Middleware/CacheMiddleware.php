<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    public function handle($request, Closure $next, $duration = 30)
    {
        // Generate cache key based on URL and user role
        $cacheKey = 'page_' . md5($request->fullUrl()) . '_user_' . auth()->id();
        
        // Try to get cached response
        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey));
        }
        
        // Get the response
        $response = $next($request);
        
        // Cache the response for specified duration (in seconds)
        if ($response->getStatusCode() === 200) {
            Cache::put($cacheKey, $response->getContent(), $duration);
        }
        
        return $response;
    }
}
