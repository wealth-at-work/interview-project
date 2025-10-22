<?php

namespace App\Providers;

use App\Services\Interfaces\MovieLookup;
use App\Services\OmdbLookup;
use Illuminate\Support\ServiceProvider;
use App\Repositories\FavoriteRepository;
use App\Services\FavoriteService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository
        $this->app->singleton(FavoriteRepository::class, function ($app) {
            return new FavoriteRepository();
        });
        
        // Bind service
        $this->app->singleton(FavoriteService::class, function ($app) {
            return new FavoriteService(
                $app->make(FavoriteRepository::class)
            );
        });    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(MovieLookup::class,OmdbLookup::class);
    }
}
