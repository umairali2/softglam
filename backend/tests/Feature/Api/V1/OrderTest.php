<?php

namespace Tests\Feature\Api\V1;

use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use LazilyRefreshDatabase;

    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $product = Product::factory()->published()->create();
        $this->variant = ProductVariant::factory()->for($product)->create(['price_amount' => 250000]);
        InventoryItem::factory()
            ->for($this->variant)
            ->for(Location::factory()->create(['is_default' => true, 'is_active' => true]))
            ->create(['on_hand' => 10]);
    }

    private function placeOrder(): Order
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
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

        return Order::query()->latest()->firstOrFail();
    }

    public function test_guest_can_view_their_own_order_via_cart_cookie(): void
    {
        $order = $this->placeOrder();

        $this->getJson("/api/v1/orders/{$order->order_number}")
            ->assertOk()
            ->assertJsonPath('data.order_number', $order->order_number)
            ->assertJsonPath('data.items.0.sku', $this->variant->sku);
    }

    public function test_a_different_guest_cannot_view_an_order(): void
    {
        $order = $this->placeOrder();

        // A different guest token resolves a different cart.
        $this->withCookie('cart_token', Str::uuid()->toString());

        $this->getJson("/api/v1/orders/{$order->order_number}")->assertNotFound();
    }

    public function test_customer_can_list_only_their_own_orders(): void
    {
        $order = $this->placeOrder(); // guest order — not linked to the user
        $user = User::factory()->create(['email' => 'umair@example.com', 'password' => 'password']);
        $order->update(['user_id' => $user->id]);
        Order::factory()->create(); // someone else's order

        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();

        $this->getJson('/api/v1/me/orders')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.order_number', $order->order_number);
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $order = Order::factory()->create();

        User::factory()->create(['email' => 'umair@example.com', 'password' => 'password']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();

        $this->getJson("/api/v1/orders/{$order->order_number}")->assertNotFound();
    }

    public function test_unknown_order_number_returns_404(): void
    {
        $this->getJson('/api/v1/orders/SG-NOTREAL')->assertNotFound();
    }

    public function test_order_history_requires_authentication(): void
    {
        $this->getJson('/api/v1/me/orders')->assertUnauthorized();
    }

    public function test_guest_can_cancel_their_own_pending_order(): void
    {
        $order = $this->placeOrder();

        $this->postJson("/api/v1/orders/{$order->order_number}/cancel")
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');

        $order->refresh();
        $this->assertSame('cancelled_by_customer', $order->status_reason);
        $this->assertNotNull($order->cancelled_at);
        $this->assertSame('customer_cancelled', InventoryMovement::query()->where('type', 'release')->sole()->reason_code);
    }

    public function test_a_different_guest_cannot_cancel_an_order(): void
    {
        $order = $this->placeOrder();
        $this->withCookie('cart_token', Str::uuid()->toString());

        $this->postJson("/api/v1/orders/{$order->order_number}/cancel")->assertNotFound();
        $this->assertSame('pending_verification', $order->fresh()->status->value);
    }

    public function test_confirmed_orders_cannot_be_self_cancelled(): void
    {
        $order = $this->placeOrder();
        $order->update(['status' => 'confirmed']);

        $this->postJson("/api/v1/orders/{$order->order_number}/cancel")
            ->assertUnprocessable();
    }
}
