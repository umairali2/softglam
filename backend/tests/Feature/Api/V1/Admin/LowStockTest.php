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
}
