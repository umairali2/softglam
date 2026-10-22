<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LowStockTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $location = Location::factory()->create(['is_default' => true]);
        $product = Product::factory()->published()->create(['title' => 'Daily Glow']);
        $variant = ProductVariant::factory()->for($product)->create(['sku' => 'SG-DAILY-01']);

        InventoryItem::factory()->for($variant)->for($location)
            ->create(['on_hand' => 3, 'reserved' => 1, 'low_stock_threshold' => 2]);
        InventoryItem::factory()->for(ProductVariant::factory()->for($product)->create(['sku' => 'SG-DAILY-02']))->for($location)
            ->create(['on_hand' => 20, 'reserved' => 0, 'low_stock_threshold' => 2]);

        $this->staff = User::factory()->create([
            'email' => 'staff@softglam.pk',
            'password' => 'password',
            'is_staff' => true,
        ]);
    }

    private function login(User $user): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_staff_sees_variants_at_or_below_their_threshold(): void
    {
        $this->login($this->staff);

        $this->getJson('/api/v1/admin/inventory/low-stock')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.sku', 'SG-DAILY-01')
            ->assertJsonPath('data.0.available', 2)
            ->assertJsonPath('data.0.low_stock_threshold', 2);
    }

    public function test_non_staff_cannot_access_low_stock(): void
    {
        $customer = User::factory()->create(['email' => 'c@example.com', 'password' => 'password']);
        $this->login($customer);

        $this->getJson('/api/v1/admin/inventory/low-stock')->assertForbidden();
    }

    public function test_staff_can_adjust_stock_with_a_reason(): void
    {
        $item = InventoryItem::query()->whereHas('productVariant', fn ($q) => $q->where('sku', 'SG-DAILY-02'))->sole();
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/inventory/{$item->id}/adjust", [
            'delta' => -2,
            'reason' => 'damaged',
            'note' => 'Two units water-damaged in storage',
        ])->assertCreated()
            ->assertJsonPath('data.on_hand_after', 18)
            ->assertJsonPath('data.reserved_after', 0);

        $this->assertDatabaseHas('inventory_movements', [
            'inventory_item_id' => $item->id,
            'type' => 'manual_adjustment',
            'reason_code' => 'damaged',
            'actor_id' => $this->staff->id,
        ]);
    }

    public function test_adjustment_below_reserved_is_rejected(): void
    {
        $item = InventoryItem::query()->whereHas('productVariant', fn ($q) => $q->where('sku', 'SG-DAILY-01'))->sole();
        $item->update(['reserved' => 3]);
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/inventory/{$item->id}/adjust", [
            'delta' => -10,
            'reason' => 'count_fix',
        ])->assertUnprocessable();
        $this->assertSame(3, $item->fresh()->on_hand);
    }
}
