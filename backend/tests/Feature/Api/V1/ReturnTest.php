<?php

namespace Tests\Feature\Api\V1;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReturnTest extends TestCase
{
    use LazilyRefreshDatabase;

    private Order $order;

    private Cart $cart;

    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $this->cart = Cart::factory()->create(['guest_token' => (string) Str::uuid()]);
        $this->withCookie('cart_token', $this->cart->guest_token);
        $this->order = Order::factory()->create([
            'status' => 'fulfilled',
            'total_amount' => 500000,
            'cart_id' => $this->cart->id,
        ]);
        $this->staff = User::factory()->create(['email' => 'staff@softglam.pk', 'password' => 'password', 'is_staff' => true]);
    }

    public function test_customer_can_request_a_return_on_a_fulfilled_order(): void
    {
        $this->postJson("/api/v1/orders/{$this->order->order_number}/returns", [
            'reason' => 'Item arrived damaged',
        ])->assertCreated()->assertJsonPath('data.status', 'requested');

        $this->assertDatabaseHas('order_returns', [
            'order_id' => $this->order->id,
            'status' => 'requested',
        ]);
    }

    public function test_pending_orders_cannot_be_returned(): void
    {
        $this->order->update(['status' => 'pending_verification']);

        $this->postJson("/api/v1/orders/{$this->order->order_number}/returns", ['reason' => 'Too early'])
            ->assertUnprocessable();
    }

    public function test_strangers_cannot_request_a_return(): void
    {
        $this->withCookie('cart_token', (string) Str::uuid());

        $this->postJson("/api/v1/orders/{$this->order->order_number}/returns", ['reason' => 'Not mine'])
            ->assertNotFound();
    }

    public function test_staff_can_approve_then_refund_a_return(): void
    {
        $return = OrderReturn::factory()->create(['order_id' => $this->order->id, 'status' => 'requested']);

        $this->postJson('/api/v1/auth/login', ['email' => $this->staff->email, 'password' => 'password']);
        $this->postJson("/api/v1/admin/returns/{$return->id}/resolve", ['action' => 'approve', 'note' => 'Photos verified'])
            ->assertOk()->assertJsonPath('data.status', 'approved');

        $this->postJson("/api/v1/admin/returns/{$return->id}/resolve", [
            'action' => 'refund',
            'refund_amount' => 200000,
        ])->assertOk()->assertJsonPath('data.status', 'refunded');

        $return->refresh();
        $this->assertSame(200000, $return->refund_amount);
        $this->assertSame($this->staff->id, $return->handled_by);
        $this->assertDatabaseHas('order_events', ['order_id' => $this->order->id, 'type' => 'return_refunded']);
    }

    public function test_refund_cannot_exceed_order_total_and_resolved_returns_are_locked(): void
    {
        $return = OrderReturn::factory()->create(['order_id' => $this->order->id, 'status' => 'approved']);
        $this->postJson('/api/v1/auth/login', ['email' => $this->staff->email, 'password' => 'password']);

        $this->postJson("/api/v1/admin/returns/{$return->id}/resolve", [
            'action' => 'refund',
            'refund_amount' => 9999999,
        ])->assertUnprocessable();

        $return->update(['status' => 'refunded']);
        $this->postJson("/api/v1/admin/returns/{$return->id}/resolve", ['action' => 'approve'])
            ->assertUnprocessable();
    }
}
