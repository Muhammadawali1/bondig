<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

/**
 * Custom captcha helper with case sensitive validation
 */
class CaptchaHelper
{
    /**
     * Check captcha with case sensitive validation
     */
    public static function check($value)
    {
        // Get the captcha from session
        $captcha = Session::get('captcha');
        
        // Debug logging
        \Log::info('CAPTCHA DEBUG - Session value: ' . json_encode($captcha));
        \Log::info('CAPTCHA DEBUG - User input: "' . $value . '"');
        \Log::info('CAPTCHA DEBUG - Comparison result: ' . ($captcha === $value ? 'MATCH' : 'NO MATCH'));
        
        if (!$captcha || !$value) {
            \Log::info('CAPTCHA DEBUG - Missing captcha or value');
            return false;
        }
        
        // Handle if captcha is an array (some captcha packages store as array)
        if (is_array($captcha)) {
            $captcha = isset($captcha['captcha']) ? $captcha['captcha'] : (string)current($captcha);
            \Log::info('CAPTCHA DEBUG - Converted from array: "' . $captcha . '"');
        }
        
        // Case sensitive comparison - must match exactly
        $result = $captcha === $value;
        \Log::info('CAPTCHA DEBUG - Final result: ' . ($result ? 'VALID' : 'INVALID'));
        
        return $result;
    }
}

/**
 * Global helper function for backward compatibility
 */
if (!function_exists('captcha_check_case_sensitive')) {
    function captcha_check_case_sensitive($value)
    {
        return \App\Helpers\CaptchaHelper::check($value);
    }
}
