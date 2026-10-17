<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => [
                'amount' => $this->unit_price_amount,
                'currency' => $this->currency,
            ],
            'line_total' => [
                'amount' => $this->quantity * $this->unit_price_amount,
                'currency' => $this->currency,
            ],
            'variant' => [
                'id' => $this->variant->id,
                'sku' => $this->variant->sku,
                'title' => $this->variant->title,
                'in_stock' => $this->variant->availableQuantity() >= $this->quantity,
                'product' => [
                    'slug' => $this->variant->product->slug,
                    'title' => $this->variant->product->title,
                ],
            ],
        ];
    }
}
