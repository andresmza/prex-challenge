<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class PassportAuthTest extends TestCase
{
    /**
     * Assert that Passport is configured as the API guard.
     */
    public function test_passport_is_configured_as_api_guard(): void
    {
        $this->assertEquals(
            'passport',
            config('auth.guards.api.driver'),
            'The API guard is not configured to use Passport.'
        );

        $this->assertEquals(
            'users',
            config('auth.guards.api.provider'),
            'The API guard provider is not set to "users".'
        );
    }

    /**
     * Assert that token expiration is configured in AuthServiceProvider.
     */
    public function test_token_expiration_is_configured_in_auth_service_provider(): void
    {
        $filePath = app_path('Providers/AuthServiceProvider.php');

        $this->assertFileExists($filePath, 'The AuthServiceProvider.php file does not exist.');

        $fileContent = file_get_contents($filePath);

        $this->assertStringContainsString(
            'use Laravel\\Passport\\Passport;',
            $fileContent,
            'The Passport class is not imported in AuthServiceProvider.'
        );

        $this->assertStringContainsString(
            'Passport::personalAccessTokensExpireIn',
            $fileContent,
            'The token expiration is not configured using Passport::personalAccessTokensExpireIn.'
        );

        $this->assertStringContainsString(
            'addMinutes',
            $fileContent,
            'The token expiration is not configured using addMinutes().'
        );
    }

    /**
     * Assert that the /oauth/token route is registered by Passport.
     */
    public function test_passport_token_route_is_available(): void
    {
        $response = $this->get('/oauth/token');
        $status = $response->status();

        $this->assertContains(
            $status,
            [Response::HTTP_METHOD_NOT_ALLOWED, Response::HTTP_UNAUTHORIZED],
            "Unexpected status code for /oauth/token route: {$status}"
        );
    }

    /**
     * Assert that the passport config file exists.
     */
    public function test_passport_config_file_exists(): void
    {
        $this->assertFileExists(
            config_path('passport.php'),
            'The config/passport.php file is missing.'
        );
    }
}
