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
        
        if (!$captcha || !$value) {
            return false;
        }
        
        // Case sensitive comparison - must match exactly
        return $captcha === $value;
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
