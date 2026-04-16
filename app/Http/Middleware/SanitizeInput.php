<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get all input data
        $input = $request->all();
        
        // Sanitize each input value
        $sanitized = $this->sanitizeArray($input);
        
        // Replace the request input with sanitized data
        $request->merge($sanitized);
        
        return $next($request);
    }
    
    /**
     * Recursively sanitize array values
     */
    private function sanitizeArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->sanitizeArray($value);
            } else {
                $array[$key] = $this->sanitizeString($value);
            }
        }
        
        return $array;
    }
    
    /**
     * Sanitize string to prevent XSS and SQL injection
     */
   private function sanitizeString($value): string
{
    if (!is_string($value)) {
        return ''; // ✅ paksa jadi string
    }

    $value = preg_replace('/\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT|JAVASCRIPT|VBSCRIPT|ONLOAD|ONERROR)\b/i', '', $value);
    
    $value = strip_tags($value);
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    $value = str_replace("\0", '', $value);
    $value = trim($value);

    return $value;
}
}
