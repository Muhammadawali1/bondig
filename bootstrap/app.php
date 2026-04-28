<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'user.active' => \App\Http\Middleware\CheckUserStatus::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'divisi' => \App\Http\Middleware\DivisiMiddleware::class,
            'sanitize' => \App\Http\Middleware\SanitizeInput::class,
            'rate.limit' => \App\Http\Middleware\RateLimitProtection::class,
            'force.https' => \App\Http\Middleware\ForceHttps::class,
        ]);
        
        // Apply ForceHttps middleware globally in production
        if (app()->environment('production')) {
            $middleware->append(\App\Http\Middleware\ForceHttps::class);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Force URL scheme based on environment
if (app()->environment('production')) {
    \URL::forceScheme('https');
} else {
    \URL::forceScheme('http');
}
