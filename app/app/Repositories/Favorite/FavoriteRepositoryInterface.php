<?php

namespace App\Repositories\Favorite;

use App\Models\User;

interface FavoriteRepositoryInterface
{
    /**
     * Add a GIF to favorites for a user.
     *
     * @param User $user The user who is adding the favorite
     * @param string $gifId The ID of the GIF to add as favorite
     * @param string $alias The alias/name for the favorite GIF
     * @return bool True if successfully added, false otherwise
     */
    public function add(User $user, string $gifId, string $alias): bool;

    /**
     * Check if a GIF is already in user's favorites.
     *
     * @param User $user The user to check favorites for
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF is already a favorite, false otherwise
     */
    public function exists(User $user, string $gifId): bool;

    /**
     * Check if an alias is already used by the user for any favorite.
     *
     * @param User $user The user to check favorites for
     * @param string $alias The alias to check
     * @return bool True if the alias is already used
     */
    public function aliasExists(User $user, string $alias): bool;
}
