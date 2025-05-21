<?php

namespace Tests\Feature;

use App\DTOs\Giphy\GifItemDTO;
use App\Models\User;
use App\Services\Giphy\GiphyServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private $giphyServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        $this->giphyServiceMock = Mockery::mock(GiphyServiceInterface::class);
        $this->app->instance(GiphyServiceInterface::class, $this->giphyServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_user_can_add_favorite(): void
    {
        Passport::actingAs($this->user);

        $gifId = 'test123';
        $this->giphyServiceMock->shouldReceive('getGifById')
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ));

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId,
            'alias' => 'My favorite GIF',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
            'alias' => 'My favorite GIF',
        ]);
    }

    public function test_cannot_add_nonexistent_gif(): void
    {
        Passport::actingAs($this->user);

        $gifId = 'nonexistent123';
        $this->giphyServiceMock->shouldReceive('getGifById')
            ->with($gifId)
            ->andReturn(null);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId,
            'alias' => 'My favorite GIF',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'This GIF does not exist in Giphy',
        ]);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'gif_id' => $gifId,
        ]);
    }

    public function test_validation_errors(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson('/api/favorites', []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['gif_id', 'alias']);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => '',
            'alias' => 'My favorite GIF',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['gif_id']);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'test123',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['alias']);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'test123',
            'alias' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['alias']);
    }

    public function test_unauthenticated_cannot_access(): void
    {
        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'test123',
            'alias' => 'My favorite GIF',
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_cannot_add_duplicate_favorite(): void
    {
        Passport::actingAs($this->user);

        $gifId = 'test123';
        $this->giphyServiceMock->shouldReceive('getGifById')
            ->with($gifId)
            ->andReturn(new GifItemDTO(
                id: $gifId,
                url: 'https://example.com/gif.gif',
                title: 'Test GIF'
            ))
            ->times(2); // Will be called twice

        $alias = 'My favorite GIF';

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId,
            'alias' => $alias,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId,
            'alias' => 'Another alias', // Even with a different alias
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJson([
            'message' => 'This GIF is already in your favorites',
        ]);

        $this->assertDatabaseCount('favorites', 1);
    }

    public function test_cannot_add_favorite_with_duplicate_alias(): void
    {
        Passport::actingAs($this->user);

        $gifId1 = 'test123';
        $gifId2 = 'different456';

        $this->giphyServiceMock->shouldReceive('getGifById')
            ->with($gifId1)
            ->andReturn(new GifItemDTO(
                id: $gifId1,
                url: 'https://example.com/gif1.gif',
                title: 'Test GIF 1'
            ));

        $this->giphyServiceMock->shouldReceive('getGifById')
            ->with($gifId2)
            ->andReturn(new GifItemDTO(
                id: $gifId2,
                url: 'https://example.com/gif2.gif',
                title: 'Test GIF 2'
            ));

        $alias = 'My favorite GIF';

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId1,
            'alias' => $alias,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => $gifId2,
            'alias' => $alias,
        ]);

        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJson([
            'message' => 'This alias is already used for another favorite',
        ]);

        $this->assertDatabaseCount('favorites', 1);
    }
}
