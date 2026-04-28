<?php

return [
    'disable' => env('CAPTCHA_DISABLE', false),

    'characters' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 0, 1, 2, 3, 4, 5, 6, 7, 8, 9],

    // ✅ FIX WAJIB (JANGAN NULL)
    'fontsDirectory' => public_path('assets/fonts'),
    'bgsDirectory' => public_path('assets/backgrounds'),

    'default' => [
        'length' => 5,
        'width' => 120,
        'height' => 40,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
        'sensitive' => true, // Case sensitive validation
    ],

    'flat' => [
        'length' => 5,
        'fontColors' => ['#000000'], // 🔥 clean hitam
        'width' => 120,
        'height' => 40,
        'math' => false,
        'quality' => 100,
        'lines' => 1, // 🔥 dikit biar nggak rame
        'bgImage' => false,
        'bgColor' => '#ffffff', // putih clean
        'contrast' => 0,
        'sensitive' => true, // Case sensitive validation
    ],

    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],

    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 40,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => false,
        'contrast' => -5,
    ],

    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 40,
        'quality' => 90,
    ],
];