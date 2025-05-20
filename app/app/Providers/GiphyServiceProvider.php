<?php

namespace App\Providers;

use App\Services\Giphy\GiphyService;
use App\Services\Giphy\GiphyServiceInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for GIPHY API integration.
 */
class GiphyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services related to GIPHY.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(GiphyServiceInterface::class, GiphyService::class);
    }

    /**
     * Bootstrap any application services related to GIPHY.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
