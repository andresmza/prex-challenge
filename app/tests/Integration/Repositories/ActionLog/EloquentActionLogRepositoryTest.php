<?php

namespace Tests\Integration\Repositories\ActionLog;

use App\DTOs\Logging\ActionLogDTO;
use App\Models\ActionLog;
use App\Repositories\ActionLog\EloquentActionLogRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class EloquentActionLogRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the repository can store an action log.
     *
     * @return void
     */
    public function test_store_creates_action_log_record(): void
    {
        $repository = new EloquentActionLogRepository();

        $dto = new ActionLogDTO(
            null,
            'test-service',
            ['test' => 'data'],
            Response::HTTP_OK,
            ['success' => true],
            '127.0.0.1'
        );

        $result = $repository->store($dto);

        $this->assertInstanceOf(ActionLog::class, $result);
        $this->assertDatabaseHas('action_logs', [
            'id' => $result->id,
            'service' => 'test-service',
            'response_code' => Response::HTTP_OK,
            'ip_address' => '127.0.0.1',
        ]);

        $this->assertEquals(['test' => 'data'], $result->request_body);
        $this->assertEquals(['success' => true], $result->response_body);
    }
}
