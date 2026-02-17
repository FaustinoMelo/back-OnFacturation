<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $productId],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'type' => ['nullable', 'in:product,service'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'track_stock' => ['nullable', 'boolean'],
            'has_variants' => ['nullable', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto',
            'name.max' => 'O nome não pode ter mais de 255 caracteres',
            'price.numeric' => 'O preço deve ser um número',
            'price.min' => 'O preço não pode ser negativo',
            'sku.unique' => 'Este SKU já foi utilizado',
            'tax_id.exists' => 'O imposto selecionado não existe',
            'category_id.exists' => 'A categoria selecionada não existe',
        ];
    }
}
