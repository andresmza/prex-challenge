<?php

namespace App\Providers;

use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Auth\DatabaseUserAuthenticator;
use App\Services\Auth\FakeTokenIssuer;
use App\Services\Auth\FakeUserAuthenticator;
use App\Services\Auth\PassportTokenIssuer;
use App\Services\Auth\TokenIssuerInterface;
use App\Services\Auth\UserAuthenticatorInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the appropriate implementations based on environment
        if ($this->app->environment('testing') || $this->runningInTestingEnvironment()) {
            $this->app->bind(TokenIssuerInterface::class, FakeTokenIssuer::class);
            $this->app->bind(UserAuthenticatorInterface::class, FakeUserAuthenticator::class);
        } else {
            $this->app->bind(TokenIssuerInterface::class, PassportTokenIssuer::class);
            $this->app->bind(UserAuthenticatorInterface::class, DatabaseUserAuthenticator::class);
        }

        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if the application is running in the testing environment.
     *
     * @return bool
     */
    private function runningInTestingEnvironment(): bool
    {
        return isset($_SERVER['TESTING']) ||
               (PHP_SAPI === 'cli' && \Illuminate\Support\Str::contains($_SERVER['argv'][0] ?? '', 'phpunit'));
    }
}
