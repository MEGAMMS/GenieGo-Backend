<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => 'nullable|string|max:255',   // Query must be a string, between 3 and 255 characters
            'tags' => 'nullable|array',                    // Tags must be an array, optional
            'tags.*' => 'nullable|string|distinct|max:50',  // Each tag must be a string, distinct, and max length of 50
        ];
    }
}
