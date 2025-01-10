<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'translations' => 'required|array|size:2',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            'tags'=>'nullable'
        ];
    }
}
