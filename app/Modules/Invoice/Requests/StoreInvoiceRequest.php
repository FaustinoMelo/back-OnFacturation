<?php

namespace App\Modules\Invoice\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'sequence_id' => ['nullable', 'exists:document_sequences,id'],
            'date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'payment_term_id' => ['nullable', 'exists:payment_terms,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'O cliente é obrigatório',
            'items.required' => 'A fatura deve ter pelo menos um item',
        ];
    }
}
