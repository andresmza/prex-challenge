<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Controller for handling authentication-related actions.
 */
class AuthController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var AuthServiceInterface
     */
    private AuthServiceInterface $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Authenticate a user and return an access token.
     *
     * @param LoginRequest $request The validated login request
     * @return JsonResponse Response with access token or error message
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = $request->toDTO();

        $loginResponse = $this->authService->login($dto);

        if ($loginResponse === null) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($loginResponse, Response::HTTP_OK);
    }
}
