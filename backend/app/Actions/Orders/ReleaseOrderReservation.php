<?php

namespace App\Actions\Orders;

use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Order;

class ReleaseOrderReservation
{
    /**
     * Release the remaining reserved stock for an order. Runs inside the
     * caller's transaction; pass a row-locked order.
     */
    public function handle(Order $order, string $reasonCode): void
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

            $reservedAfter = $item->reserved - $quantity;
            $item->update(['reserved' => $reservedAfter]);
            $item->movements()->create([
                'type' => InventoryMovementType::Release,
                'on_hand_delta' => 0,
                'reserved_delta' => -$quantity,
                'on_hand_after' => $item->on_hand,
                'reserved_after' => $reservedAfter,
                'reason_code' => $reasonCode,
                'reference_type' => 'order',
                'reference_id' => $order->id,
            ]);
        }
    }
}
