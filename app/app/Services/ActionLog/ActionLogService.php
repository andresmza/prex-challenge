<?php

namespace App\Services\ActionLog;

use App\DTOs\Logging\ActionLogDTO;
use App\Models\ActionLog;
use App\Repositories\ActionLog\ActionLogRepositoryInterface;

class ActionLogService implements ActionLogServiceInterface
{
    /**
     * Constructor.
     *
     * @param ActionLogRepositoryInterface $repository
     */
    public function __construct(
        private readonly ActionLogRepositoryInterface $repository
    ) {
    }

    /**
     * Store a new action log entry.
     *
     * @param ActionLogDTO $dto Data transfer object with log information
     * @return ActionLog
     */
    public function store(ActionLogDTO $dto): ActionLog
    {
        $cleanRequest = $this->filterSensitiveData($dto->requestBody);
        $cleanResponse = is_array($dto->responseBody)
            ? $this->filterSensitiveData($dto->responseBody)
            : $dto->responseBody;

        $cleanDTO = new ActionLogDTO(
            $dto->userId,
            $dto->service,
            $cleanRequest,
            $dto->responseCode,
            $cleanResponse,
            $dto->ipAddress
        );

        return $this->repository->store($cleanDTO);
    }

    /**
     * Filter out sensitive fields from an array.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    protected function filterSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password',
            'access_token',
        ];

        foreach ($sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '********';
            }
        }

        return $data;
    }
}
