<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use function Laravel\Prompts\error;

class AuthRequest extends FormRequest
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
    public function rules()
    {
        if ($this->routeIs('login')) {
            return [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'max:255'],
            ];
        }

        if ($this->routeIs('register')) {
            return [
                'name' => ['required', 'string', 'max:255', 'unique:users,name'],
                'password' => ['required', 'string', 'min:8', 'max:255'],
                'password_confirmation'=> ['required', 'string', 'min:8', 'max:255', 'same:password'],
                'type' => ['required', 'string', 'in:"user","admin","disabled"']
                // 'settings' => ['required', 'nullable', 'array'],
                // 'settings.type' => ['required_with:settings', 'string', 'in:user,admin,disabled'],
                // 'settings.boards' => ['array'],
                // 'settings.boards.*' => ['integer', 'exists:boards,id'],
            ];
        }

        return [];
    }
}
