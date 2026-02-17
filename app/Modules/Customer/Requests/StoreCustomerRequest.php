<?php

namespace App\Modules\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:50', 'unique:customers,tax_id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'size:2'],
            'customer_type' => ['nullable', 'in:individual,company'],
            'payment_term_id' => ['nullable', 'exists:payment_terms,id'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do cliente é obrigatório',
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 255 caracteres',
            'tax_id.unique' => 'Este NIF/Número fiscal já foi utilizado',
            'email.email' => 'O email deve ser válido',
            'country.size' => 'O código de país deve ter 2 caracteres',
            'payment_term_id.exists' => 'O termo de pagamento selecionado não existe',
            'credit_limit.numeric' => 'O limite de crédito deve ser um número',
            'credit_limit.min' => 'O limite de crédito não pode ser negativo',
        ];
    }
}
