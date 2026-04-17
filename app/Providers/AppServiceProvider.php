<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Disabled HTTPS enforcement due to 500 error on login with Railway SSL termination
        // if (env('FORCE_HTTPS', false)) {
        //     \URL::forceScheme('https');
        // }
    }
}
