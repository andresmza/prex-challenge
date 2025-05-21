<?php

namespace Tests\Unit\Services\Favorite;

use App\DTOs\Giphy\GifItemDTO;
use App\Models\User;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use App\Services\Favorite\FavoriteService;
use App\Services\Giphy\GiphyServiceInterface;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @method \Mockery\Expectation shouldReceive(string $name)
 */
class FavoriteServiceTest extends TestCase
{
    private FavoriteService $service;

    /**
     * @var MockInterface&FavoriteRepositoryInterface
     */
    private MockInterface $repository;

    /**
     * @var MockInterface&GiphyServiceInterface
     */
    private MockInterface $giphyService;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(FavoriteRepositoryInterface::class);
        $this->giphyService = Mockery::mock(GiphyServiceInterface::class);
        $this->service = new FavoriteService($this->repository, $this->giphyService);
        $this->user = User::factory()->make(['id' => 1]);
    }

    public function test_add_favorite_calls_repository(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';

        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $this->repository->shouldReceive('exists')
            ->once()
            ->with($this->user, $gifId)
            ->andReturn(false);

        $this->repository->shouldReceive('aliasExists')
            ->once()
            ->with($this->user, $alias)
            ->andReturn(false);

        $this->repository->shouldReceive('add')
            ->once()
            ->with($this->user, $gifId, $alias)
            ->andReturn(true);

        $result = $this->service->addFavorite($this->user, $gifId, $alias);

        $this->assertTrue($result['success']);
        $this->assertNull($result['message']);
    }

    public function test_add_favorite_returns_error_when_already_exists(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';

        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $this->repository->shouldReceive('exists')
            ->once()
            ->with($this->user, $gifId)
            ->andReturn(true);

        $this->repository->shouldNotReceive('aliasExists');
        $this->repository->shouldNotReceive('add');

        $result = $this->service->addFavorite($this->user, $gifId, $alias);

        $this->assertFalse($result['success']);
        $this->assertEquals('This GIF is already in your favorites', $result['message']);
    }

    public function test_add_favorite_returns_error_when_gif_does_not_exist(): void
    {
        $gifId = 'nonexistent123';
        $alias = 'My favorite GIF';

        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(null);

        $this->repository->shouldNotReceive('exists');
        $this->repository->shouldNotReceive('aliasExists');
        $this->repository->shouldNotReceive('add');

        $result = $this->service->addFavorite($this->user, $gifId, $alias);

        $this->assertFalse($result['success']);
        $this->assertEquals('This GIF does not exist in Giphy', $result['message']);
    }

    public function test_add_favorite_returns_error_when_alias_already_used(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';

        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $this->repository->shouldReceive('exists')
            ->once()
            ->with($this->user, $gifId)
            ->andReturn(false);

        $this->repository->shouldReceive('aliasExists')
            ->once()
            ->with($this->user, $alias)
            ->andReturn(true);

        $this->repository->shouldNotReceive('add');

        $result = $this->service->addFavorite($this->user, $gifId, $alias);

        $this->assertFalse($result['success']);
        $this->assertEquals('This alias is already used for another favorite', $result['message']);
    }

    public function test_add_favorite_handles_repository_failure(): void
    {
        $gifId = 'test123';
        $alias = 'My favorite GIF';

        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $this->repository->shouldReceive('exists')
            ->once()
            ->with($this->user, $gifId)
            ->andReturn(false);

        $this->repository->shouldReceive('aliasExists')
            ->once()
            ->with($this->user, $alias)
            ->andReturn(false);

        $this->repository->shouldReceive('add')
            ->once()
            ->with($this->user, $gifId, $alias)
            ->andReturn(false);

        $result = $this->service->addFavorite($this->user, $gifId, $alias);

        $this->assertFalse($result['success']);
        $this->assertEquals('Could not add favorite. Please try again.', $result['message']);
    }

    public function test_is_favorite_calls_repository_exists(): void
    {
        $gifId = 'test123';
        $this->repository->shouldReceive('exists')
            ->once()
            ->with($this->user, $gifId)
            ->andReturn(true);

        $result = $this->service->isFavorite($this->user, $gifId);

        $this->assertTrue($result);
    }

    public function test_is_alias_used_calls_repository_alias_exists(): void
    {
        $alias = 'My favorite GIF';
        $this->repository->shouldReceive('aliasExists')
            ->once()
            ->with($this->user, $alias)
            ->andReturn(true);

        $result = $this->service->isAliasUsed($this->user, $alias);

        $this->assertTrue($result);
    }

    public function test_gif_exists_returns_true_when_gif_exists(): void
    {
        $gifId = 'test123';
        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $result = $this->service->gifExists($gifId);

        $this->assertTrue($result);
    }

    public function test_gif_exists_returns_false_when_gif_does_not_exist(): void
    {
        $gifId = 'nonexistent123';
        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andReturn(null);

        $result = $this->service->gifExists($gifId);

        $this->assertFalse($result);
    }

    public function test_gif_exists_returns_false_when_api_throws_exception(): void
    {
        $gifId = 'test123';
        $this->giphyService->shouldReceive('getGifById')
            ->once()
            ->with($gifId)
            ->andThrow(new \Exception('API error'));

        $result = $this->service->gifExists($gifId);

        $this->assertFalse($result);
    }
}
