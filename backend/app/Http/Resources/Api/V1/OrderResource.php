<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => $this->status->value,
            'payment_method' => $this->payment_method,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'shipping_address' => $this->shipping_address,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'totals' => [
                'subtotal_amount' => $this->subtotal_amount,
                'shipping_amount' => $this->shipping_amount,
                'total_amount' => $this->total_amount,
                'currency' => $this->currency,
            ],
            'verification_deadline_at' => $this->verification_deadline_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
