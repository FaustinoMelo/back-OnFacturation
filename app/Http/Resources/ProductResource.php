<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'min_price' => $this->min_price,
            'unit' => $this->unit,
            'type' => $this->type->value,
            'track_stock' => $this->track_stock,
            'is_active' => $this->is_active,
            'tax' => new TaxResource($this->whenLoaded('tax')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}

