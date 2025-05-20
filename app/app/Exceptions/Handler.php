<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (GiphyApiException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->createApiErrorResponse($e);
            }
        });
    }

    /**
     * Create a standardized API error response.
     *
     * @param GiphyApiException $e The exception to convert to a response
     * @return JsonResponse
     */
    private function createApiErrorResponse(GiphyApiException $e): JsonResponse
    {
        $statusCode = $e->getCode() >= Response::HTTP_BAD_REQUEST && $e->getCode() < Response::HTTP_INTERNAL_SERVER_ERROR ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = app()->environment('production') && $e->getErrorType() === 'config'
            ? 'An error occurred while connecting to the service.'
            : $e->getMessage();

        return new JsonResponse([
            'error' => $message,
            'type' => app()->environment('production') && $e->getErrorType() === 'config'
                ? 'service_unavailable'
                : $e->getErrorType(),
            'status' => $statusCode,
        ], $statusCode);
    }
}
