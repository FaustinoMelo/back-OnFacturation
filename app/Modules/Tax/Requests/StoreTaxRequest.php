<?php

namespace App\Modules\Tax\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'rate' => ['required', 'numeric', 'min:0'],
            'tax_type' => ['nullable', 'in:inclusive,exclusive'],
            'exemption_reason_id' => ['nullable', 'exists:tax_exemption_reasons,id'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do imposto é obrigatório',
            'rate.required' => 'A taxa é obrigatória',
        ];
    }
}
