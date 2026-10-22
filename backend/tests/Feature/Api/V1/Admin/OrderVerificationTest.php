<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Mail\OrderFulfilledMail;
use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\OrderStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderVerificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    private ProductVariant $variant;

    private InventoryItem $inventoryItem;

    private User $staff;

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
        $this->inventoryItem = InventoryItem::factory()
            ->for($this->variant)
            ->for(Location::factory()->create(['is_default' => true, 'is_active' => true]))
            ->create(['on_hand' => 10, 'reserved' => 0]);

        $this->staff = User::factory()->create([
            'email' => 'staff@softglam.pk',
            'password' => 'password',
            'is_staff' => true,
        ]);
    }

    private function placeOrder(int $quantity): Order
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

        return Order::query()->latest()->firstOrFail();
    }

    private function login(User $user): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_staff_can_list_the_pending_verification_queue(): void
    {
        $order = $this->placeOrder(2);
        $this->login($this->staff);

        $this->getJson('/api/v1/admin/orders?status=pending_verification')
            ->assertOk()
            ->assertJsonPath('data.0.order_number', $order->order_number);
    }

    public function test_staff_can_confirm_an_order_and_stock_stays_reserved(): void
    {
        $order = $this->placeOrder(3);
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")
            ->assertOk()
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertSame(OrderStatus::Confirmed, $order->fresh()->status);
        $this->assertSame(3, $this->inventoryItem->fresh()->reserved);
        $confirmEvent = $order->events()->where('type', 'confirmed')->sole();
        $this->assertSame($this->staff->id, $confirmEvent->actor_id);
    }

    public function test_staff_can_decline_an_order_and_stock_is_released(): void
    {
        $order = $this->placeOrder(3);
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/orders/{$order->id}/decline", [
            'reason' => 'Customer unreachable after two attempts',
        ])->assertOk()->assertJsonPath('data.status', 'cancelled');

        $order->refresh();
        $this->assertSame(OrderStatus::Cancelled, $order->status);
        $this->assertSame('Customer unreachable after two attempts', $order->status_reason);
        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
        $this->assertDatabaseHas('inventory_movements', [
            'type' => 'release',
            'reason_code' => 'verification_declined',
            'reference_id' => $order->id,
        ]);
    }

    public function test_decline_requires_a_reason(): void
    {
        $order = $this->placeOrder(1);
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/orders/{$order->id}/decline", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('reason');
        $this->assertSame(OrderStatus::PendingVerification, $order->fresh()->status);
    }

    public function test_confirmed_orders_cannot_be_confirmed_again_or_declined(): void
    {
        $order = $this->placeOrder(1);
        $this->login($this->staff);
        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertOk();

        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertUnprocessable();
        $this->postJson("/api/v1/admin/orders/{$order->id}/decline", ['reason' => 'X'])
            ->assertUnprocessable();
        $this->assertSame(1, $this->inventoryItem->fresh()->reserved);
    }

    public function test_staff_can_fulfill_a_confirmed_order(): void
    {
        $order = $this->placeOrder(3);
        $this->login($this->staff);
        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertOk();

        $this->postJson("/api/v1/admin/orders/{$order->id}/fulfill", [
            'courier_name' => 'TCS',
            'tracking_number' => '123456789',
        ])->assertOk()
            ->assertJsonPath('data.status', 'fulfilled')
            ->assertJsonPath('data.tracking_number', '123456789');

        $item = $this->inventoryItem->fresh();
        $this->assertSame(7, $item->on_hand);
        $this->assertSame(0, $item->reserved);
        $this->assertDatabaseHas('inventory_movements', [
            'type' => 'sale',
            'on_hand_delta' => -3,
            'reserved_delta' => -3,
            'reference_id' => $order->id,
        ]);
        $this->assertNotNull($order->fresh()->fulfilled_at);
        Mail::fake();
        $this->postJson("/api/v1/admin/orders/{$order->id}/fulfill")->assertUnprocessable();
    }

    public function test_pending_orders_cannot_be_fulfilled(): void
    {
        $order = $this->placeOrder(1);
        $this->login($this->staff);

        $this->postJson("/api/v1/admin/orders/{$order->id}/fulfill", [])
            ->assertUnprocessable();
        $this->assertSame(10, $this->inventoryItem->fresh()->on_hand);
        $this->assertSame(1, $this->inventoryItem->fresh()->reserved);
    }

    public function test_staff_can_cancel_a_confirmed_order_and_stock_is_released(): void
    {
        $order = $this->placeOrder(2);
        $this->login($this->staff);
        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertOk();

        $this->postJson("/api/v1/admin/orders/{$order->id}/cancel", [
            'reason' => 'Customer requested cancellation',
        ])->assertOk()->assertJsonPath('data.status', 'cancelled');

        $order->refresh();
        $this->assertSame('Customer requested cancellation', $order->status_reason);
        $this->assertNotNull($order->cancelled_at);
        $this->assertSame('cancelled', $order->events()->where('type', 'cancelled')->sole()->type);
        $this->assertSame(10, $this->inventoryItem->fresh()->on_hand);
        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
        $this->assertDatabaseHas('inventory_movements', [
            'type' => 'release',
            'reason_code' => 'order_cancelled',
            'reference_id' => $order->id,
        ]);
    }

    public function test_fulfilling_queues_a_dispatch_email_once(): void
    {
        Mail::fake();
        $order = $this->placeOrder(1);
        $this->login($this->staff);
        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertOk();

        $this->postJson("/api/v1/admin/orders/{$order->id}/fulfill", [
            'tracking_number' => 'ABC123',
        ])->assertOk();

        Mail::assertQueued(OrderFulfilledMail::class, 1);
        Mail::assertQueued(OrderFulfilledMail::class, fn ($mail) => $mail->hasTo('customer@example.com'));
    }

    public function test_fulfilled_orders_cannot_be_cancelled(): void
    {
        $order = $this->placeOrder(1);
        $this->login($this->staff);
        $this->postJson("/api/v1/admin/orders/{$order->id}/confirm")->assertOk();
        $this->postJson("/api/v1/admin/orders/{$order->id}/fulfill")->assertOk();

        $this->postJson("/api/v1/admin/orders/{$order->id}/cancel", ['reason' => 'Too late'])
            ->assertUnprocessable();
    }

    public function test_customers_and_guests_cannot_access_the_admin_queue(): void
    {
        $this->getJson('/api/v1/admin/orders')->assertUnauthorized();

        $customer = User::factory()->create(['email' => 'c@example.com', 'password' => 'password']);
        $this->login($customer);
        $this->getJson('/api/v1/admin/orders')->assertForbidden();
    }
}
