<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'sku' => $this->sku,
            'product_title' => $this->product_title,
            'variant_title' => $this->variant_title,
            'product_slug' => $this->product_slug,
            'quantity' => $this->quantity,
            'unit_price' => ['amount' => $this->unit_price_amount, 'currency' => $this->currency],
            'line_total' => ['amount' => $this->line_total_amount, 'currency' => $this->currency],
        ];
    }
}
