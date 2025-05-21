<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Models\User;
use App\Services\Favorite\FavoriteServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    public function __construct(
        private FavoriteServiceInterface $favoriteService
    ) {
    }

    /**
     * @route POST /api/favorites
     */
    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $result = $this->favoriteService->addFavorite(
            $user,
            $validated['gif_id'],
            $validated['alias']
        );

        if (! $result['success']) {
            return response()->json(
                ['message' => $result['message']],
                $result['status_code']
            );
        }

        return response()->json(null, Response::HTTP_CREATED);
    }
}
