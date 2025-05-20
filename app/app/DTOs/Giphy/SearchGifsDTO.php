<?php

namespace App\DTOs\Giphy;

/**
 * Data Transfer Object for GIF search parameters.
 */
class SearchGifsDTO
{
    /**
     * @param string $query  Search term
     * @param int|null $limit  Result limit (maximum 50)
     * @param int|null $offset  Pagination offset
     */
    public function __construct(
        public readonly string $query,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null,
    ) {
    }
}
