<?php

namespace App\Http\Requests\Auth;

use App\Enums\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'gender' => ['sometimes', 'string', Rule::in(Gender::toArray())],
            'birthday' => ['sometimes', 'date'],
            'bio' => ['sometimes', 'string'],
            'password' => ['required', 'confirmed', 'string', 'min:8'],
        ];
    }
}
