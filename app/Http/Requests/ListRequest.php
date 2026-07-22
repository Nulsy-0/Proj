<?php

namespace App\Http\Requests;

use App\Models\Utilities;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'days' => ['array'],
            'days.*' => ['required_with:days', 'required', 'array'],
            'days.*.start_date' => ['required_with:days.*', 'date', 'nullable'],
            'days.*.days' => ['required_with:days.*', 'array'],
            'days.*.days.*' => ['required_with:days.*.days', 'string', Rule::in(Utilities::weekDaysSm()),],

            'stats' => ['nullable', 'array'],
            'stats.*' => ['required', 'array'],
            'stats.*.name' => ['required', 'string', 'max:255'],
            'stats.*.type' => ['required', 'string', 'max:255', 'in:"labels","dates","extras"'],
            'stats.*.fields' => ['required', 'array'],
            'stats.*.fields.*.name' => ['required', 'string', 'max:255'],
            'stats.*.fields.*.type' => ['required', 'string', 'max:255'],
            'stats.*.fields.*.data' => ['required', 'array'],
            'stats.*.fields.*.data.*' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'stats.*.required' => 'Each stat is required.',

            'stats.*.name.required' => 'The stat name is required.',
            'stats.*.name.string' => 'The stat name must be a string.',
            'stats.*.name.max' => 'The stat name may not be greater than :max characters.',

            'stats.*.type.required' => 'The stat type is required.',
            'stats.*.type.in' => 'The stat type must be one of: labels, dates or extras.',

            'stats.*.fields.required' => 'The stat fields are required.',

            'stats.*.fields.*.name.required' => 'The field name is required.',
            'stats.*.fields.*.type.required' => 'The field type is required.',
            'stats.*.fields.*.data.required' => 'The field data is required.',

            'stats.*.fields.*.data.*.required' => 'Each data value is required.',
            'stats.*.fields.*.data.*.string' => 'Each data value must be a string.',
        ];
    }
}
