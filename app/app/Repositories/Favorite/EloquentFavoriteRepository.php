<?php

namespace App\Repositories\Favorite;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * Repository to manage favorites using Eloquent.
 */
class EloquentFavoriteRepository implements FavoriteRepositoryInterface
{
    /**
     * Add a GIF to favorites for a user.
     *
     * @param User $user The user adding the favorite
     * @param string $gifId The GIF ID to add
     * @param string $alias The alias for the favorite
     * @return bool True if successfully added
     */
    public function add(User $user, string $gifId, string $alias): bool
    {
        try {
            return (bool) Favorite::create([
                'user_id' => $user->id,
                'gif_id' => $gifId,
                'alias' => $alias,
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return false;
        }
    }

    /**
     * Check if a GIF is already in user's favorites.
     *
     * @param User $user The user to check favorites for
     * @param string $gifId The GIF ID to check
     * @return bool True if the GIF is already a favorite
     */
    public function exists(User $user, string $gifId): bool
    {
        return Favorite::where('user_id', $user->id)
            ->where('gif_id', $gifId)
            ->exists();
    }

    /**
     * Check if an alias is already used by the user for any favorite.
     *
     * @param User $user The user to check favorites for
     * @param string $alias The alias to check
     * @return bool True if the alias is already used
     */
    public function aliasExists(User $user, string $alias): bool
    {
        return Favorite::where('user_id', $user->id)
            ->where('alias', $alias)
            ->exists();
    }
}
