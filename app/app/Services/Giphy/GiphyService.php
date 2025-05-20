<?php

namespace App\Services\Giphy;

use App\DTOs\Giphy\GifItemDTO;
use App\DTOs\Giphy\SearchGifsDTO;
use App\DTOs\Giphy\SearchGifsResultDTO;
use App\Exceptions\GiphyApiException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Implementation of the GIPHY API service.
 */
class GiphyService implements GiphyServiceInterface
{
    /**
     * Search for GIFs based on query parameters.
     *
     * @param SearchGifsDTO $dto The search parameters
     * @return SearchGifsResultDTO Collection of found GIFs
     * @throws GiphyApiException When API configuration is invalid or API errors occur
     */
    public function searchGifs(SearchGifsDTO $dto): SearchGifsResultDTO
    {
        $this->validateApiConfiguration();

        try {
            $response = Http::get(config('giphy.base_url').'/search', [
                'api_key' => config('giphy.api_key'),
                'q' => $dto->query,
                'limit' => $dto->limit ?? 25,
                'offset' => $dto->offset ?? 0,
            ])->throw();

            $data = $response->json('data');

            if (! is_array($data)) {
                throw GiphyApiException::responseFormatError();
            }

            $gifData = [];

            foreach ($data as $item) {
                $gifData[] = new GifItemDTO(
                    id: $item['id'],
                    url: $item['images']['original']['url'],
                    title: $item['title'],
                );
            }

            return new SearchGifsResultDTO($gifData);
        } catch (RequestException $e) {
            Log::error('GIPHY API request failed', [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'query' => $dto->query,
            ]);

            if ($e->getCode() === Response::HTTP_TOO_MANY_REQUESTS) {
                throw GiphyApiException::rateLimitError();
            }

            throw GiphyApiException::connectionError($e->getCode());
        } catch (GiphyApiException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error searching GIFs', [
                'message' => $e->getMessage(),
                'query' => $dto->query,
            ]);

            throw GiphyApiException::responseFormatError();
        }
    }

    /**
     * Get a specific GIF by its ID.
     *
     * @param string $gifId The unique identifier of the GIF
     * @return GifItemDTO|null The GIF data or null if not found
     * @throws GiphyApiException When API configuration is invalid or API errors occur
     */
    public function getGifById(string $gifId): ?GifItemDTO
    {
        $this->validateApiConfiguration();

        try {
            $response = Http::get(config('giphy.base_url').'/'.$gifId, [
                'api_key' => config('giphy.api_key'),
            ])->throw();

            $data = $response->json('data');

            if (! isset($data['id'], $data['images']['original']['url'], $data['title'])) {
                return null;
            }

            return new GifItemDTO(
                id: $data['id'],
                url: $data['images']['original']['url'],
                title: $data['title'],
            );
        } catch (RequestException $e) {
            Log::error('GIPHY API request failed', [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
                'gif_id' => $gifId,
            ]);

            if ($e->getCode() === Response::HTTP_NOT_FOUND) {
                return null;
            }

            if ($e->getCode() === Response::HTTP_TOO_MANY_REQUESTS) {
                throw GiphyApiException::rateLimitError();
            }

            throw GiphyApiException::connectionError($e->getCode());
        } catch (GiphyApiException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error getting GIF by ID', [
                'message' => $e->getMessage(),
                'gif_id' => $gifId,
            ]);

            throw GiphyApiException::responseFormatError();
        }
    }

    /**
     * Validate that the GIPHY API is properly configured.
     *
     * @throws GiphyApiException When API configuration is invalid
     */
    private function validateApiConfiguration(): void
    {
        $apiKey = Config::get('giphy.api_key');
        $baseUrl = Config::get('giphy.base_url');

        if (empty($apiKey) || $apiKey === 'YOUR_GIPHY_KEY' || empty($baseUrl)) {
            Log::error('GIPHY API is not properly configured');
            throw GiphyApiException::configError();
        }
    }
}
