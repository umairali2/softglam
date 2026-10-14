<?php

namespace Tests\Unit\Actions\Inventory;

use App\Actions\Inventory\AdjustInventory;
use App\Exceptions\InvalidInventoryAdjustment;
use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Location;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use LogicException;
use Tests\TestCase;

class AdjustInventoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_adjusts_on_hand_quantity_and_appends_a_movement(): void
    {
        $productVariant = ProductVariant::factory()->create();
        $location = Location::factory()->create();
        $actor = User::factory()->create();

        $movement = app(AdjustInventory::class)->handle(
            $productVariant,
            $location,
            12,
            'initial_count',
            $actor,
            'Opening stock count',
        );

        $this->assertSame(12, $movement->on_hand_after);
        $this->assertSame(InventoryMovementType::ManualAdjustment, $movement->type);
        $this->assertDatabaseHas('inventory_items', [
            'product_variant_id' => $productVariant->id,
            'location_id' => $location->id,
            'on_hand' => 12,
            'reserved' => 0,
        ]);
        $this->assertDatabaseHas('inventory_movements', [
            'id' => $movement->id,
            'actor_id' => $actor->id,
            'on_hand_delta' => 12,
            'reason_code' => 'initial_count',
        ]);
    }

    public function test_rejects_an_adjustment_below_reserved_stock_without_changing_inventory(): void
    {
        $inventoryItem = InventoryItem::factory()->create([
            'on_hand' => 5,
            'reserved' => 3,
        ]);

        try {
            app(AdjustInventory::class)->handle(
                $inventoryItem->productVariant,
                $inventoryItem->location,
                -3,
                'stock_count',
                User::factory()->create(),
            );
            $this->fail('The invalid adjustment was accepted.');
        } catch (InvalidInventoryAdjustment $exception) {
            $this->assertSame('On-hand quantity cannot be lower than the reserved quantity.', $exception->getMessage());
        }

        $this->assertSame(5, $inventoryItem->fresh()->on_hand);
        $this->assertDatabaseCount('inventory_movements', 0);
    }

    public function test_rejects_a_zero_quantity_adjustment(): void
    {
        $this->expectException(InvalidInventoryAdjustment::class);
        $this->expectExceptionMessage('Inventory adjustment must change the on-hand quantity.');

        app(AdjustInventory::class)->handle(
            ProductVariant::factory()->create(),
            Location::factory()->create(),
            0,
            'stock_count',
            User::factory()->create(),
        );
    }

    public function test_inventory_movements_cannot_be_updated(): void
    {
        $movement = InventoryMovement::factory()->create();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Inventory movements are immutable.');

        $movement->update(['note' => 'Changed']);
    }

    public function test_inventory_movements_cannot_be_deleted(): void
    {
        $movement = InventoryMovement::factory()->create();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Inventory movements are immutable.');

        $movement->delete();
    }

    public function test_actor_cannot_be_deleted_while_attributed_to_a_movement(): void
    {
        $actor = User::factory()->create();
        InventoryMovement::factory()->for($actor, 'actor')->create();

        $this->expectException(QueryException::class);

        $actor->delete();
    }
}
