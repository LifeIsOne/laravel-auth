<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users|max:30',
            'password' => ['required', 'max:30', Password::default()],
        ];
    }
}
