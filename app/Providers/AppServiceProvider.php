<?php

namespace App\Providers;

use App\Services\CurrentDemo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrentDemo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // A demo deployment cannot accidentally inherit a real mail transport.
        config()->set('mail.default', 'log');
    }
}
