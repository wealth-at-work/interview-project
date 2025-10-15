<?php

namespace App\Providers;

use App\Services\Interfaces\MovieLookup;
use App\Services\OmdbLookup;
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
        $this->app->bind(MovieLookup::class,OmdbLookup::class);
    }
}
