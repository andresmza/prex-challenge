<?php

namespace Tests\Unit\Services;

use App\DTOs\Giphy\GifItemDTO;
use App\DTOs\Giphy\SearchGifsDTO;
use App\Services\Giphy\GiphyService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GiphyServiceTest extends TestCase
{
    protected GiphyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config(['giphy.api_key' => 'test_api_key']);
        config(['giphy.base_url' => 'https://api.giphy.com/v1/gifs']);

        $this->service = new GiphyService();
    }

    /**
     * Test that searchGifs method correctly maps API response to DTOs.
     */
    public function test_search_gifs_maps_response_to_dtos(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/search*' => Http::response([
                'data' => [
                    [
                        'id' => 'test1',
                        'title' => 'Test GIF 1',
                        'images' => [
                            'original' => [
                                'url' => 'https://media.giphy.com/test1.gif',
                            ],
                        ],
                    ],
                    [
                        'id' => 'test2',
                        'title' => 'Test GIF 2',
                        'images' => [
                            'original' => [
                                'url' => 'https://media.giphy.com/test2.gif',
                            ],
                        ],
                    ],
                ],
            ], Response::HTTP_OK),
        ]);

        $dto = new SearchGifsDTO(
            query: 'funny',
            limit: 2,
            offset: 0
        );

        $result = $this->service->searchGifs($dto);

        $this->assertCount(2, $result->data);
        $this->assertInstanceOf(GifItemDTO::class, $result->data[0]);
        $this->assertEquals('test1', $result->data[0]->id);
        $this->assertEquals('Test GIF 1', $result->data[0]->title);
        $this->assertEquals('https://media.giphy.com/test1.gif', $result->data[0]->url);
        $this->assertEquals('test2', $result->data[1]->id);
    }

    /**
     * Test that getGifById method correctly maps API response to DTO.
     */
    public function test_get_gif_by_id_maps_response_to_dto(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/test123*' => Http::response([
                'data' => [
                    'id' => 'test123',
                    'title' => 'Single Test GIF',
                    'images' => [
                        'original' => [
                            'url' => 'https://media.giphy.com/test123.gif',
                        ],
                    ],
                ],
            ], Response::HTTP_OK),
        ]);

        $gif = $this->service->getGifById('test123');

        $this->assertInstanceOf(GifItemDTO::class, $gif);
        $this->assertEquals('test123', $gif->id);
        $this->assertEquals('Single Test GIF', $gif->title);
        $this->assertEquals('https://media.giphy.com/test123.gif', $gif->url);
    }

    /**
     * Test that searchGifs throws a connection error exception when API call fails.
     */
    public function test_search_gifs_throws_exception_when_api_call_fails(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/search*' => Http::response(null, Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $dto = new SearchGifsDTO(
            query: 'funny',
            limit: 2,
            offset: 0
        );

        $this->expectException(\App\Exceptions\GiphyApiException::class);
        $this->expectExceptionMessage('Error connecting to GIPHY API: 500');

        $this->service->searchGifs($dto);
    }

    /**
     * Test that getGifById throws a connection error exception when API call fails with 500.
     */
    public function test_get_gif_by_id_throws_exception_when_api_call_fails(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/test123*' => Http::response(null, Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $this->expectException(\App\Exceptions\GiphyApiException::class);
        $this->expectExceptionMessage('Error connecting to GIPHY API: 500');

        $this->service->getGifById('test123');
    }

    /**
     * Test that getGifById returns null when API returns 404 (not found).
     */
    public function test_get_gif_by_id_returns_null_when_gif_not_found(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/nonexistent*' => Http::response(null, Response::HTTP_NOT_FOUND),
        ]);

        $gif = $this->service->getGifById('nonexistent');

        $this->assertNull($gif);
    }

    /**
     * Test that searchGifs throws a response format error when data is not an array.
     */
    public function test_search_gifs_throws_exception_when_response_format_invalid(): void
    {
        Http::fake([
            'https://api.giphy.com/v1/gifs/search*' => Http::response([
                'data' => 'not_an_array',
                'meta' => [
                    'status' => Response::HTTP_OK,
                    'msg' => 'OK',
                ],
            ], Response::HTTP_OK),
        ]);

        $dto = new SearchGifsDTO(
            query: 'funny',
            limit: 2,
            offset: 0
        );

        $this->expectException(\App\Exceptions\GiphyApiException::class);
        $this->expectExceptionMessage('Unexpected response format from GIPHY API');

        $this->service->searchGifs($dto);
    }
}
