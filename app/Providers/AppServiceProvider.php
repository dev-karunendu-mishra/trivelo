<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ThemeService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind ThemeService to the service container
        $this->app->singleton('theme', function ($app) {
            return new ThemeService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
