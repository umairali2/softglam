<?php

namespace Tests\Feature\Console;

use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\OrderStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ExpireUnverifiedOrdersTest extends TestCase
{
    use LazilyRefreshDatabase;

    private InventoryItem $inventoryItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $product = Product::factory()->published()->create();
        $variant = ProductVariant::factory()->for($product)->create(['price_amount' => 250000]);
        $this->inventoryItem = InventoryItem::factory()
            ->for($variant)
            ->for(Location::factory()->create(['is_default' => true, 'is_active' => true]))
            ->create(['on_hand' => 10, 'reserved' => 0]);
        $this->variant = $variant;
    }

    private ProductVariant $variant;

    private function placeOrder(int $quantity): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => $quantity,
        ]);
        $this->withCookie('cart_token', $response->getCookie('cart_token')->getValue());
        $this->postJson('/api/v1/checkout', [
            'contact_email' => 'customer@example.com',
            'contact_phone' => '03007654321',
            'address' => [
                'recipient_name' => 'Umair Ali',
                'phone' => '03007654321',
                'line1' => 'House 12',
                'city' => 'Karachi',
            ],
        ])->assertCreated();
    }

    public function test_expired_order_is_cancelled_and_stock_released(): void
    {
        $this->placeOrder(3);
        $order = Order::query()->sole();
        $order->update(['verification_deadline_at' => now()->subMinute()]);

        $this->artisan('orders:expire-unverified')->assertSuccessful();

        $order->refresh();
        $this->assertSame(OrderStatus::Cancelled, $order->status);
        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
        $release = InventoryMovement::query()->where('type', InventoryMovementType::Release)->sole();
        $this->assertSame('verification_expired', $release->reason_code);
        $this->assertSame($order->id, $release->reference_id);
        $this->assertSame('expired', $order->events()->where('type', 'expired')->sole()->type);
    }

    public function test_fresh_pending_orders_are_not_touched(): void
    {
        $this->placeOrder(2);

        $this->artisan('orders:expire-unverified')->assertSuccessful();

        $order = Order::query()->sole();
        $this->assertSame(OrderStatus::PendingVerification, $order->status);
        $this->assertSame(2, $this->inventoryItem->fresh()->reserved);
    }

    public function test_running_twice_is_safe(): void
    {
        $this->placeOrder(2);
        Order::query()->sole()->update(['verification_deadline_at' => now()->subHour()]);

        $this->artisan('orders:expire-unverified')->assertSuccessful();
        $this->artisan('orders:expire-unverified')->assertSuccessful();

        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
        $this->assertSame(1, InventoryMovement::query()->where('type', InventoryMovementType::Release)->count());
    }
}
