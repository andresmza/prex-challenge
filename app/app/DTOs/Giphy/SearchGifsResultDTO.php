<?php

namespace App\DTOs\Giphy;

use JsonSerializable;

/**
 * Data Transfer Object for GIF search results.
 */
class SearchGifsResultDTO implements JsonSerializable
{
    /**
     * @param GifItemDTO[] $data Collection of found GIFs
     */
    public function __construct(
        public readonly array $data,
    ) {
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->data,
        ];
    }
}
