<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\DashboardHelper;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(DashboardHelper::class, function ($app) {
            return new DashboardHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
