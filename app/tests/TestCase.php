<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Configure Passport for testing
        $this->configurePassport();
    }

    /**
     * Configure Passport for testing.
     *
     * @return void
     */
    protected function configurePassport(): void
    {
        Passport::useClientModel(\Laravel\Passport\Client::class);
        Passport::useTokenModel(\Laravel\Passport\Token::class);
        Passport::useAuthCodeModel(\Laravel\Passport\AuthCode::class);
        Passport::usePersonalAccessClientModel(\Laravel\Passport\PersonalAccessClient::class);
    }
}
