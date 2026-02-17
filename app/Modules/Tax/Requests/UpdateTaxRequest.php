<?php

namespace App\Modules\Tax\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'rate' => ['sometimes', 'numeric', 'min:0'],
            'tax_type' => ['nullable', 'in:inclusive,exclusive'],
            'exemption_reason_id' => ['nullable', 'exists:tax_exemption_reasons,id'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'rate.numeric' => 'A taxa deve ser numérica',
        ];
    }
}
