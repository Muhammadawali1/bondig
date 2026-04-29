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
        
        // Handle if captcha is an array (some captcha packages store as array)
        if (is_array($captcha)) {
            $captcha = isset($captcha['captcha']) ? $captcha['captcha'] : (string)current($captcha);
        }
        
        // Convert to string to ensure type safety
        $captcha = (string) $captcha;
        
        if (!$captcha) {
            return [
                'valid' => false,
                'error' => 'Session captcha tidak ditemukan. Silakan refresh halaman.'
            ];
        }
        
        if (!$value) {
            return [
                'valid' => false,
                'error' => 'Captcha wajib diisi.'
            ];
        }
        
        // Convert input to string as well
        $value = (string) $value;
        
        // Check if input contains invalid characters (only letters and numbers allowed)
        if (!preg_match('/^[A-Za-z0-9]+$/', $value)) {
            return [
                'valid' => false,
                'error' => 'Karakter salah. Hanya huruf dan angka yang diperbolehkan.'
            ];
        }
        
        // Check if length matches
        if (strlen($value) !== strlen($captcha)) {
            return [
                'valid' => false,
                'error' => 'Panjang captcha tidak sesuai. Harus ' . strlen($captcha) . ' karakter.'
            ];
        }
        
        // Check case sensitivity (same characters but wrong case)
        if (strtolower($value) === strtolower($captcha) && $value !== $captcha) {
            return [
                'valid' => false,
                'error' => 'Besar kecilnya huruf harus sama.'
            ];
        }
        
        // Check exact match
        if ($captcha === $value) {
            return [
                'valid' => true,
                'error' => null
            ];
        }
        
        // General error for wrong captcha
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
