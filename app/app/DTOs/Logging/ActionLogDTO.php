<?php

namespace App\DTOs\Logging;

class ActionLogDTO
{
    /**
     * Constructor.
     *
     * @param int|null $userId User ID who made the request (null if not authenticated)
     * @param string $service Service or endpoint path
     * @param array $requestBody Request body content
     * @param int $responseCode HTTP response code
     * @param array|string|null $responseBody Response body content
     * @param string $ipAddress IP address of the client
     */
    public function __construct(
        public readonly ?int $userId,
        public readonly string $service,
        public readonly array $requestBody,
        public readonly int $responseCode,
        public readonly array|string|null $responseBody,
        public readonly string $ipAddress,
    ) {
    }
}
