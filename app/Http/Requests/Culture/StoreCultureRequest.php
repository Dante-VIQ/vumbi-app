<?php

namespace App\Http\Requests\Culture;

use Illuminate\Foundation\Http\FormRequest;

class StoreCultureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'detail'   => 'required|string',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'Image must not be larger than 2MB.',
        ];
    }
}