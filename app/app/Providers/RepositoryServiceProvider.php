<?php

namespace App\Providers;

use App\Repositories\Favorite\EloquentFavoriteRepository;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            FavoriteRepositoryInterface::class,
            EloquentFavoriteRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
