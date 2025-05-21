<?php

namespace App\Services\Favorite;

use App\Models\User;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Services\Giphy\GiphyServiceInterface;
use Illuminate\Http\Response;

class FavoriteService implements FavoriteServiceInterface
{
    public function __construct(
        private FavoriteRepositoryInterface $repository,
        private GiphyServiceInterface $giphyService
    ) {
    }

    /**
     * Add a GIF to user's favorites.
     *
     * @param User $user The user adding the favorite
     * @param string $gifId The GIF ID to add as favorite
     * @param string $alias The alias for the favorite GIF
     * @return array{success: bool, message: string|null, status_code: int|null} Result with success status, message and HTTP status code if error
     */
    public function addFavorite(User $user, string $gifId, string $alias): array
    {
        if (! $this->gifExists($gifId)) {
            return [
                'success' => false,
                'message' => 'This GIF does not exist in Giphy',
                'status_code' => Response::HTTP_NOT_FOUND,
            ];
        }

        if ($this->isFavorite($user, $gifId)) {
            return [
                'success' => false,
                'message' => 'This GIF is already in your favorites',
                'status_code' => Response::HTTP_CONFLICT,
            ];
        }

        if ($this->isAliasUsed($user, $alias)) {
            return [
                'success' => false,
                'message' => 'This alias is already used for another favorite',
                'status_code' => Response::HTTP_CONFLICT,
            ];
        }

        $added = $this->repository->add($user, $gifId, $alias);

        if (! $added) {
            return [
                'success' => false,
                'message' => 'Could not add favorite. Please try again.',
                'status_code' => Response::HTTP_CONFLICT,
            ];
        }

        return [
            'success' => true,
            'message' => null,
            'status_code' => null,
        ];
    }

    /**
     * Check if a GIF is already in user's favorites.
     *
     * @param User $user The user to check favorites for
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF is already a favorite
     */
    public function isFavorite(User $user, string $gifId): bool
    {
        return $this->repository->exists($user, $gifId);
    }

    /**
     * Check if an alias is already used by the user for any favorite.
     *
     * @param User $user The user to check favorites for
     * @param string $alias The alias to check
     * @return bool True if the alias is already used
     */
    public function isAliasUsed(User $user, string $alias): bool
    {
        return $this->repository->aliasExists($user, $alias);
    }

    /**
     * Check if a GIF exists in the Giphy API.
     *
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF exists in Giphy
     */
    public function gifExists(string $gifId): bool
    {
        try {
            $gif = $this->giphyService->getGifById($gifId);

            return $gif !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}
