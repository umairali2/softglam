<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'title' => $this->title,
            'price' => [
                'amount' => $this->price_amount,
                'compare_at_amount' => $this->compare_at_amount,
                'currency' => $this->currency,
            ],
            'weight_g' => $this->weight_g,
            'is_default' => $this->is_default,
            'availability' => $this->whenLoaded('inventoryItems', fn (): array => [
                'status' => $this->availableQuantity() > 0 ? 'in_stock' : 'out_of_stock',
            ]),
        ];
    }
}
