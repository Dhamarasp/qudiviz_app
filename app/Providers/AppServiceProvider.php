<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;
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

        // Paksa semua URL menjadi https
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Percayai header X-Forwarded-Proto dari proxy Railway
        Request::setTrustedProxies(
            [Request::HEADER_X_FORWARDED_FOR],
            Request::HEADER_X_FORWARDED_PROTO
        );

        // Load helpers Toast
        require_once app_path('Helpers/ToastHelper.php');
    }
}
