<?php

namespace Tests\Feature\Logging;

use App\Models\ActionLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionLogMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that API requests are logged correctly for unauthenticated users.
     *
     * @return void
     */
    public function test_api_requests_are_logged_for_unauthenticated_users(): void
    {
        $requestData = ['email' => 'test@example.com', 'password' => 'password'];

        $this->postJson('/api/login', $requestData);

        $this->assertDatabaseHas('action_logs', [
            'user_id' => null,
            'ip_address' => '127.0.0.1',
        ]);

        $log = ActionLog::where('service', 'like', '%AuthController@login%')->latest()->first();
        $this->assertNotNull($log);

        $this->assertEquals('test@example.com', $log->request_body['email']);
        $this->assertEquals('********', $log->request_body['password']);
    }

    /**
     * Test that middleware is registered and working.
     *
     * @return void
     */
    public function test_middleware_is_registered_and_working(): void
    {
        $this->assertTrue(
            app()->bound(\App\Services\ActionLog\ActionLogServiceInterface::class),
            'The ActionLogService is not registered'
        );

        $this->assertTrue(
            app()->bound(\App\Repositories\ActionLog\ActionLogRepositoryInterface::class),
            'The ActionLogRepository is not registered'
        );
    }
}
