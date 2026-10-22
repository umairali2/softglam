<?php

namespace App\Actions\Orders;

use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Order;

class FulfillOrder
{
    /**
     * Convert the order's reservations into sales: on-hand and reserved
     * both decrease, with append-only Sale movements. Runs inside the
     * caller's transaction; pass a row-locked order.
     */
    public function handle(Order $order): void
    {
        $reservations = InventoryMovement::query()
            ->where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->where('type', InventoryMovementType::Reservation)
            ->get();

        foreach ($reservations as $reservation) {
            $item = InventoryItem::query()
                ->whereKey($reservation->inventory_item_id)
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = min($reservation->reserved_delta, $item->reserved);
            if ($quantity === 0) {
                continue;
            }

            $onHandAfter = $item->on_hand - $quantity;
            $reservedAfter = $item->reserved - $quantity;
            $item->update(['on_hand' => $onHandAfter, 'reserved' => $reservedAfter]);
            $item->movements()->create([
                'type' => InventoryMovementType::Sale,
                'on_hand_delta' => -$quantity,
                'reserved_delta' => -$quantity,
                'on_hand_after' => $onHandAfter,
                'reserved_after' => $reservedAfter,
                'reason_code' => 'fulfillment',
                'reference_type' => 'order',
                'reference_id' => $order->id,
            ]);
        }
    }
}
