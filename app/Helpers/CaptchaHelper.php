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

    /**
     * Enhanced captcha validation with specific error messages
     */
    public static function validate($value)
    {
        // Get the captcha from session
        $captcha = Session::get('captcha');
        
        // Debug logging
        \Log::info('Captcha Debug - Raw session: ' . json_encode($captcha));
        \Log::info('Captcha Debug - Input: "' . $value . '"');
        
        // Handle if captcha is an array (some captcha packages store as array)
        if (is_array($captcha)) {
            $captcha = isset($captcha['captcha']) ? $captcha['captcha'] : (string)current($captcha);
        }
        
        // Convert to string to ensure type safety
        $captcha = (string) $captcha;
        
        \Log::info('Captcha Debug - Processed captcha: "' . $captcha . '"');
        
        if (!$captcha) {
            \Log::info('Captcha Debug - No captcha found');
            return [
                'valid' => false,
                'error' => 'Session captcha tidak ditemukan. Silakan refresh halaman.'
            ];
        }
        
        if (!$value) {
            \Log::info('Captcha Debug - No input value');
            return [
                'valid' => false,
                'error' => 'Captcha wajib diisi.'
            ];
        }
        
        // Convert input to string as well
        $value = (string) $value;
        
        // Check exact match first
        if ($captcha === $value) {
            \Log::info('Captcha Debug - Exact match - VALID');
            return [
                'valid' => true,
                'error' => null
            ];
        }
        
        // Check case sensitivity (same characters but wrong case)
        if (strtolower($value) === strtolower($captcha) && $value !== $captcha) {
            \Log::info('Captcha Debug - Case sensitivity error');
            return [
                'valid' => false,
                'error' => 'Besar kecilnya huruf harus sama.'
            ];
        }
        
        // General error for wrong captcha
        \Log::info('Captcha Debug - General error - wrong captcha');
        return [
            'valid' => false,
            'error' => 'Captcha salah. Harus sesuai dengan gambar.'
        ];
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
