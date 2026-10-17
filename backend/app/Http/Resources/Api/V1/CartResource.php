<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $items = $this->whenLoaded('items');

        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($items ?? collect()),
            'totals' => [
                'item_count' => $items?->sum('quantity') ?? 0,
                'subtotal_amount' => $items?->sum(fn ($item): int => $item->quantity * $item->unit_price_amount) ?? 0,
                'currency' => 'PKR',
            ],
        ];
    }
}
