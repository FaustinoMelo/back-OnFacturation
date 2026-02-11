<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'method' => $this->method->value,
            'reference' => $this->reference,
            'payment_date' => $this->payment_date->format('Y-m-d'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}

