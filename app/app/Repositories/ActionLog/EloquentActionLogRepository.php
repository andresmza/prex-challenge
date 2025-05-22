<?php

namespace App\Repositories\ActionLog;

use App\DTOs\Logging\ActionLogDTO;
use App\Models\ActionLog;

class EloquentActionLogRepository implements ActionLogRepositoryInterface
{
    /**
     * Store a new action log entry.
     *
     * @param ActionLogDTO $dto Data transfer object with log information
     * @return ActionLog
     */
    public function store(ActionLogDTO $dto): ActionLog
    {
        return ActionLog::create([
            'user_id' => $dto->userId,
            'service' => $dto->service,
            'request_body' => $dto->requestBody,
            'response_code' => $dto->responseCode,
            'response_body' => $dto->responseBody,
            'ip_address' => $dto->ipAddress,
        ]);
    }
}
