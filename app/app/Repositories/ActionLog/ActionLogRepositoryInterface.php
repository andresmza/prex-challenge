<?php

namespace App\Repositories\ActionLog;

use App\DTOs\Logging\ActionLogDTO;
use App\Models\ActionLog;

interface ActionLogRepositoryInterface
{
    /**
     * Store a new action log entry.
     *
     * @param ActionLogDTO $dto Data transfer object with log information
     * @return ActionLog
     */
    public function store(ActionLogDTO $dto): ActionLog;
}
