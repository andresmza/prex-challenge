<?php

namespace App\Services\Giphy;

use App\DTOs\Giphy\GifItemDTO;
use App\DTOs\Giphy\SearchGifsDTO;
use App\DTOs\Giphy\SearchGifsResultDTO;

interface GiphyServiceInterface
{
    /**
     * Search for GIFs based on query parameters.
     */
    public function searchGifs(SearchGifsDTO $dto): SearchGifsResultDTO;

    /**
     * Get a specific GIF by its ID.
     */
    public function getGifById(string $gifId): ?GifItemDTO;
}
