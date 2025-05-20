<?php

namespace App\Http\Requests;

use App\DTOs\Giphy\SearchGifsDTO;
use Illuminate\Foundation\Http\FormRequest;

class SearchGifsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['required', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'offset' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function toDTO(): SearchGifsDTO
    {
        $v = $this->validated();

        return new SearchGifsDTO(
            query: $v['query'],
            limit: $v['limit'] ?? null,
            offset: $v['offset'] ?? null,
        );
    }
}
