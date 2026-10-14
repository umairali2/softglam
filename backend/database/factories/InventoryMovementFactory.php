<?php

namespace Database\Factories;

use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryMovement>
 */
class InventoryMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'actor_id' => null,
            'type' => InventoryMovementType::ManualAdjustment,
            'on_hand_delta' => 1,
            'reserved_delta' => 0,
            'on_hand_after' => 1,
            'reserved_after' => 0,
            'reason_code' => 'stock_count',
            'note' => null,
            'reference_type' => null,
            'reference_id' => null,
        ];
    }
}
