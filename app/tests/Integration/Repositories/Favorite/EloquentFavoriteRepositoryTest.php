<?php

namespace Tests\Integration\Repositories\Favorite;

use App\Models\Favorite;
use App\Models\User;
use App\Repositories\Favorite\EloquentFavoriteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentFavoriteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentFavoriteRepository $repository;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentFavoriteRepository();
        $this->user = User::factory()->create();
    }

    /**
     * @testdox It is possible to add a GIF to favorites
     */
    public function test_can_add_favorite(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';

        $result = $this->repository->add($this->user, $gifId, $alias);

        $this->assertTrue($result);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
            'alias' => $alias,
        ]);
    }

    /**
     * @testdox Adding a duplicate GIF to favorites returns false instead of throwing an exception
     */
    public function test_cannot_add_duplicate_favorite(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';
        Favorite::create([
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
            'alias' => $alias,
        ]);

        $result = $this->repository->add($this->user, $gifId, $alias);

        $this->assertFalse($result);
        $this->assertDatabaseCount('favorites', 1);
    }

    /**
     * @testdox Check if a GIF exists in user's favorites
     */
    public function test_exists_returns_true_for_existing_favorite(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';
        Favorite::create([
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
            'alias' => $alias,
        ]);

        $result = $this->repository->exists($this->user, $gifId);

        $this->assertTrue($result);
    }

    /**
     * @testdox Check if a GIF does not exist in user's favorites
     */
    public function test_exists_returns_false_for_nonexisting_favorite(): void
    {
        $gifId = 'test123';

        $result = $this->repository->exists($this->user, $gifId);

        $this->assertFalse($result);
    }

    /**
     * @testdox Check if an alias exists in user's favorites
     */
    public function test_alias_exists_returns_true_for_existing_alias(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';
        Favorite::create([
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
            'alias' => $alias,
        ]);

        $result = $this->repository->aliasExists($this->user, $alias);

        $this->assertTrue($result);
    }

    /**
     * @testdox Check if an alias does not exist in user's favorites
     */
    public function test_alias_exists_returns_false_for_nonexisting_alias(): void
    {
        $alias = 'My favorite GIF';

        $result = $this->repository->aliasExists($this->user, $alias);

        $this->assertFalse($result);
    }
}
