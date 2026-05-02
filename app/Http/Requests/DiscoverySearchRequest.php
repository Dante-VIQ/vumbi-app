<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscoverySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // No auth needed for public search
    }

// app/Http/Requests/DiscoverySearchRequest.php
public function rules(): array
{
    return [
        'search'   => 'required|string|min:2|max:100',
        'interest' => 'nullable|string|in:cuisine,art,safari,history,all',
    ];
}

public function interest(): string
{
    return $this->validated('interest', 'all');
}

    /**
     * Get the cleaned search term.
     */
    public function searchTerm(): string
    {
        return ucwords(trim($this->validated('search')));
    }
}