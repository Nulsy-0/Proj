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
            'days.*' => ['required', 'array'],
            'days.*.start_date' => ['required', 'date'],
            'days.*.weeks' => ['array'],
            'days.*.weeks.*' => ['string', Rule::in(Utilities::weekDaysSm()),],
        ];
    }
}
