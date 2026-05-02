<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscoverySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // No auth needed for public search
    }

    public function rules(): array
    {
        return [
            'search' => 'required|string|min:2|max:100',
        ];
    }

    /**
     * Get the cleaned search term.
     */
    public function searchTerm(): string
    {
        return ucwords(trim($this->validated('search')));
    }
}