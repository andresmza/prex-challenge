<?php

namespace App\Services\Favorite;

use App\Models\User;

interface FavoriteServiceInterface
{
    /**
     * Add a GIF to user's favorites.
     *
     * @param User $user The user adding the favorite
     * @param string $gifId The GIF ID to add as favorite
     * @param string $alias The alias for the favorite GIF
     * @return array{success: bool, message: string|null, status_code: int|null} Result with success status, message and HTTP status code if error
     */
    public function addFavorite(User $user, string $gifId, string $alias): array;

    /**
     * Check if a GIF is already in user's favorites.
     *
     * @param User $user The user to check favorites for
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF is already a favorite
     */
    public function isFavorite(User $user, string $gifId): bool;

    /**
     * Check if an alias is already used by the user for any favorite.
     *
     * @param User $user The user to check favorites for
     * @param string $alias The alias to check
     * @return bool True if the alias is already used
     */
    public function isAliasUsed(User $user, string $alias): bool;

    /**
     * Check if a GIF exists in the Giphy API.
     *
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF exists in Giphy
     */
    public function gifExists(string $gifId): bool;
}
