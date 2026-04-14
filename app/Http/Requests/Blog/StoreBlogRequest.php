<?php

namespace App\Http\Requests\Blog;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string|min:10',  // Sanitize in Model or here with purifier
'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi|max:8192', // 8MB
'media_type' => 'required_with:media|in:image,video',
        ];
    }
}
