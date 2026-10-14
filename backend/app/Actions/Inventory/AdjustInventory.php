<?php

namespace App\Actions\Inventory;

use App\Exceptions\InvalidInventoryAdjustment;
use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Location;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdjustInventory
{
    public function handle(
        ProductVariant $productVariant,
        Location $location,
        int $quantityDelta,
        string $reasonCode,
        User $actor,
        ?string $note = null,
    ): InventoryMovement {
        if ($quantityDelta === 0) {
            throw new InvalidInventoryAdjustment('Inventory adjustment must change the on-hand quantity.');
        }

        if (trim($reasonCode) === '') {
            throw new InvalidInventoryAdjustment('Inventory adjustment requires a reason code.');
        }

        return DB::transaction(function () use ($productVariant, $location, $quantityDelta, $reasonCode, $actor, $note): InventoryMovement {
            $inventoryItem = InventoryItem::query()->firstOrCreate([
                'product_variant_id' => $productVariant->id,
                'location_id' => $location->id,
            ]);

            $inventoryItem = InventoryItem::query()
                ->whereKey($inventoryItem->id)
                ->lockForUpdate()
                ->firstOrFail();

            $onHandAfter = $inventoryItem->on_hand + $quantityDelta;

            if ($onHandAfter < $inventoryItem->reserved) {
                throw new InvalidInventoryAdjustment('On-hand quantity cannot be lower than the reserved quantity.');
            }

            $inventoryItem->update(['on_hand' => $onHandAfter]);

            return $inventoryItem->movements()->create([
                'actor_id' => $actor->id,
                'type' => InventoryMovementType::ManualAdjustment,
                'on_hand_delta' => $quantityDelta,
                'reserved_delta' => 0,
                'on_hand_after' => $onHandAfter,
                'reserved_after' => $inventoryItem->reserved,
                'reason_code' => $reasonCode,
                'note' => $note,
            ]);
        }, 3);
    }
}
