<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
                'password' => ['required', 'string', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
            ];
        }

        if ($this->routeIs('register')) {
            return [
                'name' => ['required', 'string', 'max:255', 'unique:users,name'],
                'password' => ['required', 'string', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
                'password_confirmation'=> ['required', 'same:password'],
                'state' => ['required', 'string', 'in:"user","admin","disabled"']
            ];
        }

        if ($this->routeIs('user.update')) {
            return [
                'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($this->id)],
                'password_reset' => ['nullable', 'string', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
                'state' => ['required', 'string', 'in:"user","admin","disabled"'],
                'boards' => ['nullable', 'array']
            ];
        }
    }
}
