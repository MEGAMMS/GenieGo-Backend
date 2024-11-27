<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
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
            'email' => [
                'nullable', // Email is optional, but only one of email/username/phone is required
                'string',
                'email',
                Rule::requiredIf(!$this->hasAny(['username', 'phone'])), // Required if username and phone are missing
            ],
            'username' => [
                'nullable', 
                'string',
                Rule::requiredIf(!$this->hasAny(['email', 'phone'])), // Required if email and phone are missing
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::requiredIf(!$this->hasAny(['email', 'username'])), // Required if email and username are missing
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email, username, or phone is required.',
            'username.required' => 'Email, username, or phone is required.',
            'phone.required' => 'Email, username, or phone is required.',
        ];
    }

    
}
