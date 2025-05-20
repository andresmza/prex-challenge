<?php

namespace App\DTOs\Giphy;

use JsonSerializable;

/**
 * Data Transfer Object for an individual GIF.
 */
class GifItemDTO implements JsonSerializable
{
    /**
     * @param string $id     Unique identifier of the GIF
     * @param string $url    URL of the original image
     * @param string $title  Title of the GIF
     */
    public function __construct(
        public readonly string $id,
        public readonly string $url,
        public readonly string $title,
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
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}
