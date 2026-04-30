<?php

namespace App\Http\Requests\Culture;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCultureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
}