<?php

namespace App\Modules\Invoice\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'date' => ['sometimes', 'date'],
            'due_date' => ['nullable', 'date'],
            'payment_term_id' => ['nullable', 'exists:payment_terms,id'],
            'items' => ['nullable', 'array'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.description' => ['required_with:items', 'string'],
            'items.*.quantity' => ['required_with:items', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required_with:items', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:draft,issued,paid,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required_with' => 'Os items da fatura são inválidos',
        ];
    }
}
