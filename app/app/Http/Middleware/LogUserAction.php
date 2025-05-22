<?php

namespace App\Http\Middleware;

use App\DTOs\Logging\ActionLogDTO;
use App\Services\ActionLog\ActionLogServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserAction
{
    /**
     * Constructor.
     *
     * @param ActionLogServiceInterface $actionLogService
     */
    public function __construct(
        protected ActionLogServiceInterface $actionLogService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request The incoming request
     * @param Closure $next The next middleware
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestBodyArray = $this->getRequestBody($request);

        $response = $next($request);

        try {
            $userId = null;

            try {
                if ($request->user()) {
                    $userId = $request->user()->id;
                }
            } catch (\Exception $e) {
                logger()->debug('Could not get the user: '.$e->getMessage());
            }

            $service = $this->getServiceName($request);
            $responseCode = $response->getStatusCode();
            $responseBody = $this->getResponseBody($response);
            $ip = $request->ip();

            $actionLogDTO = new ActionLogDTO(
                $userId,
                $service,
                $requestBodyArray,
                $responseCode,
                $responseBody,
                $ip
            );

            $this->actionLogService->store($actionLogDTO);
        } catch (\Exception $e) {
            logger()->error('Error logging user action: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return $response;
    }

    /**
     * Get the request body safely.
     *
     * @param Request $request
     * @return array
     */
    protected function getRequestBody(Request $request): array
    {
        try {
            $body = [];

            if ($request->isMethod('get')) {
                $body = $request->query();
            } elseif ($request->isJson()) {
                $body = $request->json()->all();
            } else {
                $body = $request->request->all();
            }

            $route = $request->route();
            $routeParams = is_object($route) ? $route->parameters() : [];

            return array_merge($routeParams, $body);
        } catch (\Exception $e) {
            logger()->warning('Could not parse request body: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get the response body safely.
     *
     * @param Response $response
     * @return array|string|null
     */
    protected function getResponseBody(Response $response): array|string|null
    {
        try {
            $content = $response->getContent();

            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }

            return $content;
        } catch (\Exception $e) {
            logger()->warning('Could not parse response body: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get the service name from the request.
     *
     * @param Request $request
     * @return string
     */
    protected function getServiceName(Request $request): string
    {
        $route = $request->route();

        if ($route && method_exists($route, 'getName') && $route->getName()) {
            return $route->getName();
        }

        if ($route && method_exists($route, 'getAction')) {
            $action = $route->getAction();
            if (is_array($action) && isset($action['controller'])) {
                return $action['controller'];
            }
        }

        return $request->path();
    }
}
