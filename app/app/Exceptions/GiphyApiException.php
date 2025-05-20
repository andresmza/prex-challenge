<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

/**
 * Exception thrown when there is an error with the GIPHY API.
 */
class GiphyApiException extends Exception
{
    /**
     * The error type.
     *
     * @var string
     */
    protected $errorType;

    /**
     * Create a new GIPHY API exception.
     *
     * @param string $message The exception message
     * @param string $errorType The type of error (config, connection, response, etc.)
     * @param int $code The exception code
     * @param \Throwable|null $previous The previous exception
     */
    public function __construct(string $message, string $errorType = 'unknown', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorType = $errorType;
    }

    /**
     * Get the error type.
     *
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * Create a configuration error exception.
     *
     * @return self
     */
    public static function configError(): self
    {
        return new self('GIPHY API is not properly configured', 'config', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Create a connection error exception.
     *
     * @param int $statusCode The HTTP status code
     * @return self
     */
    public static function connectionError(int $statusCode): self
    {
        return new self("Error connecting to GIPHY API: {$statusCode}", 'connection', $statusCode);
    }

    /**
     * Create a response format error exception.
     *
     * @return self
     */
    public static function responseFormatError(): self
    {
        return new self('Unexpected response format from GIPHY API', 'response_format', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Create a rate limit error exception.
     *
     * @return self
     */
    public static function rateLimitError(): self
    {
        return new self('GIPHY API rate limit exceeded', 'rate_limit', Response::HTTP_TOO_MANY_REQUESTS);
    }
}
