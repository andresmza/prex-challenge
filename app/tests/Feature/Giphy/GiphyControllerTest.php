<?php

namespace Tests\Feature\Giphy;

use App\DTOs\Giphy\GifItemDTO;
use App\DTOs\Giphy\SearchGifsResultDTO;
use App\Models\User;
use App\Services\Giphy\GiphyServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GiphyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->createMock(GiphyServiceInterface::class);
        $this->app->instance(GiphyServiceInterface::class, $this->mock);
    }

    public function test_search_endpoint_returns_expected_response(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $gifItems = [
            new GifItemDTO(
                id: 'test1',
                url: 'https://media.giphy.com/test1.gif',
                title: 'Test GIF 1'
            ),
            new GifItemDTO(
                id: 'test2',
                url: 'https://media.giphy.com/test2.gif',
                title: 'Test GIF 2'
            ),
        ];
        $searchResult = new SearchGifsResultDTO($gifItems);

        $this->mock->method('searchGifs')
            ->willReturn($searchResult);

        $response = $this->getJson('/api/gifs?query=funny&limit=2');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    [
                        'id' => 'test1',
                        'url' => 'https://media.giphy.com/test1.gif',
                        'title' => 'Test GIF 1',
                    ],
                    [
                        'id' => 'test2',
                        'url' => 'https://media.giphy.com/test2.gif',
                        'title' => 'Test GIF 2',
                    ],
                ],
            ]);
    }

    public function test_search_endpoint_validates_request(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/gifs');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['query']);
    }

    public function test_show_endpoint_returns_expected_response(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $gifItem = new GifItemDTO(
            id: 'test123',
            url: 'https://media.giphy.com/test123.gif',
            title: 'Test GIF 123'
        );

        $this->mock->method('getGifById')
            ->with('test123')
            ->willReturn($gifItem);

        $response = $this->getJson('/api/gifs/test123');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'id' => 'test123',
                'url' => 'https://media.giphy.com/test123.gif',
                'title' => 'Test GIF 123',
            ]);
    }

    public function test_unauthenticated_users_cannot_access_endpoints(): void
    {
        $this->getJson('/api/gifs?query=funny')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->getJson('/api/gifs/test123')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_show_endpoint_returns_404_when_gif_not_found(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->mock->method('getGifById')
            ->with('nonexistent')
            ->willReturn(null);

        $response = $this->getJson('/api/gifs/nonexistent');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'error' => 'GIF not found',
                'type' => 'not_found',
                'status' => Response::HTTP_NOT_FOUND,
            ]);
    }
}
