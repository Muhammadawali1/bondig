<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitProtection
{


 protected $limiter;
 
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
          // Convert string parameters to integers to prevent Carbon errors
        $maxAttempts = (int) $maxAttempts;
        $decayMinutes = (int) $decayMinutes;
        
        $key = $this->resolveRequestSignature($request, $prefix);
        
        // Check if IP is blocked
        if ($this->isIpBlocked($request->ip())) {
            return response()->json([
                'message' => 'IP address is blocked due to suspicious activity.',
                'retry_after' => 3600
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        
        // Rate limiting check
     if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            
            // Log suspicious activity
            $this->logSuspiciousActivity($request, 'rate_limit_exceeded');
            
            // Block IP after multiple violations
            $this->checkAndBlockIp($request->ip());
            
            return response()->json([
                'message' => 'Too many attempts. Please try again later.',
                'retry_after' => $seconds,
                'remaining_attempts' => 0
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        
       $this->limiter->hit($key, $decayMinutes);
        
        $response = $next($request);
        
        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
          $response->headers->set('X-RateLimit-Remaining', max(0, $maxAttempts - $this->limiter->attempts($key)));
        $response->headers->set('X-RateLimit-Retry-After', $this->limiter->availableIn($key));
        
        return $response;
    }
    
    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $prefix): string
    {
        $signature = sha1($prefix . '|' . $request->ip() . '|' . $request->userAgent());
        
        return 'rate_limit:' . $signature;
    }
    
    /**
     * Check if IP is blocked
     */
    protected function isIpBlocked(string $ip): bool
    {
        return Cache::has('blocked_ip:' . $ip);
    }
    
    /**
     * Block IP address
     */
    protected function blockIp(string $ip, int $duration = 3600): void
    {
        Cache::put('blocked_ip:' . $ip, true, $duration);
        
        // Log the blocking
        \Log::warning('IP Blocked due to suspicious activity', [
            'ip' => $ip,
            'duration' => $duration,
            'timestamp' => now()
        ]);
    }
    
    /**
     * Check and block IP after multiple violations
     */
    protected function checkAndBlockIp(string $ip): void
    {
        $violationsKey = 'violations:' . $ip;
        $violations = Cache::get($violationsKey, 0);
        
        $violations++;
        Cache::put($violationsKey, $violations, 3600); // 1 hour
        
        // Block IP after 5 violations
        if ($violations >= 5) {
            $this->blockIp($ip, 7200); // Block for 2 hours
        }
    }
    
    /**
     * Log suspicious activity
     */
    protected function logSuspiciousActivity(Request $request, string $type): void
    {
        \Log::warning('Suspicious Activity Detected', [
            'type' => $type,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'timestamp' => now()
        ]);
    }
}
