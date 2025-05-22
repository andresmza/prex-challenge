<?php

namespace Tests\Unit\Services\ActionLog;

use App\DTOs\Logging\ActionLogDTO;
use App\Models\ActionLog;
use App\Repositories\ActionLog\ActionLogRepositoryInterface;
use App\Services\ActionLog\ActionLogService;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class ActionLogServiceTest extends TestCase
{
    /**
     * Test that the service delegates to the repository and filters sensitive data.
     *
     * @return void
     */
    public function test_store_delegates_to_repository_and_filters_sensitive_data(): void
    {
        $mockRepository = Mockery::mock(ActionLogRepositoryInterface::class);
        $service = new ActionLogService($mockRepository);

        $inputDTO = new ActionLogDTO(
            1,
            'test-service',
            [
                'username' => 'testuser',
                'password' => 'secret123',
                'email' => 'test@example.com',
            ],
            Response::HTTP_OK,
            ['success' => true],
            '127.0.0.1'
        );

        $mockActionLog = new ActionLog([
            'user_id' => 1,
            'service' => 'test-service',
            'request_body' => [
                'username' => 'testuser',
                'password' => '********',
                'email' => 'test@example.com',
            ],
            'response_code' => Response::HTTP_OK,
            'response_body' => ['success' => true],
            'ip_address' => '127.0.0.1',
        ]);

        $mockRepository->shouldReceive('store')
            ->once()
            ->with(Mockery::on(function ($dto) {
                return $dto instanceof ActionLogDTO &&
                       $dto->userId === 1 &&
                       $dto->service === 'test-service' &&
                       $dto->requestBody['password'] === '********' &&
                       $dto->responseCode === Response::HTTP_OK &&
                       $dto->ipAddress === '127.0.0.1';
            }))
            ->andReturn($mockActionLog);

        $result = $service->store($inputDTO);

        $this->assertInstanceOf(ActionLog::class, $result);
        $this->assertEquals('********', $result->request_body['password']);
    }
}
