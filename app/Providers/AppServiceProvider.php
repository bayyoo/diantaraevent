<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS in production so generated URLs (routes, assets, etc.)
        // use https and browsers don't warn about insecure form submissions.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
