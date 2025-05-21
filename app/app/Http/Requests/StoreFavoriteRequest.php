<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'gif_id' => ['required', 'string'],
            'alias' => ['required', 'string', 'max:255'],
        ];
    }
}
