<?php

namespace Tests\Feature\Api\V1;

use App\InventoryMovementType;
use App\Mail\OrderPlacedMail;
use App\Models\CustomerAddress;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\OrderStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use LazilyRefreshDatabase;

    private ProductVariant $variant;

    private InventoryItem $inventoryItem;

    private array $address = [
        'recipient_name' => 'Umair Ali',
        'phone' => '03001234567',
        'line1' => 'House 12, Street 3',
        'area' => 'DHA Phase 6',
        'city' => 'Karachi',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $product = Product::factory()->published()->create([
            'slug' => 'daily-glow',
            'title' => 'Daily Glow',
        ]);
        $this->variant = ProductVariant::factory()->for($product)->create([
            'sku' => 'SG-DAILY-01',
            'title' => 'Default',
            'price_amount' => 250000,
            'currency' => 'PKR',
        ]);
        $location = Location::factory()->create(['is_default' => true, 'is_active' => true]);
        $this->inventoryItem = InventoryItem::factory()
            ->for($this->variant)
            ->for($location)
            ->create(['on_hand' => 10, 'reserved' => 0]);
    }

    private function addToCart(int $quantity): self
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => $quantity,
        ]);
        if ($cookie = $response->getCookie('cart_token')) {
            $this->withCookie('cart_token', $cookie->getValue());
        }

        return $this;
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'contact_email' => 'customer@example.com',
            'contact_phone' => '03007654321',
            'address' => $this->address,
        ], $overrides);
    }

    public function test_guest_checkout_creates_a_cod_order_and_reserves_stock(): void
    {
        $this->addToCart(2);

        $response = $this->postJson('/api/v1/checkout', $this->checkoutPayload());

        $response
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending_verification')
            ->assertJsonPath('data.payment_method', 'cod')
            ->assertJsonPath('data.totals.subtotal_amount', 500000)
            ->assertJsonPath('data.totals.shipping_amount', 0)
            ->assertJsonPath('data.totals.total_amount', 500000)
            ->assertJsonPath('data.shipping_address.city', 'Karachi')
            ->assertJsonPath('data.items.0.sku', 'SG-DAILY-01')
            ->assertJsonPath('data.items.0.unit_price.amount', 250000);

        $order = Order::query()->sole();
        $this->assertSame('placed', $order->events()->sole()->type);
        $this->assertSame(OrderStatus::PendingVerification, $order->status);
        $this->assertNotNull($order->verification_deadline_at);
        $this->assertSame(2, $this->inventoryItem->fresh()->reserved);
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $order->cart_id]);
        $this->assertSame(1, InventoryMovement::query()->where('type', InventoryMovementType::Reservation)->count());
    }

    public function test_shipping_is_charged_below_the_free_threshold(): void
    {
        $this->addToCart(1);

        $this->postJson('/api/v1/checkout', $this->checkoutPayload())
            ->assertCreated()
            ->assertJsonPath('data.totals.shipping_amount', 30000)
            ->assertJsonPath('data.totals.total_amount', 280000);
    }

    public function test_cod_is_rejected_above_the_limit(): void
    {
        $this->variant->update(['price_amount' => 1600000]);
        $this->addToCart(1);

        $this->postJson('/api/v1/checkout', $this->checkoutPayload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment_method');
        $this->assertSame(0, Order::query()->count());
        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
    }

    public function test_checkout_rejects_when_stock_becomes_unavailable(): void
    {
        $this->addToCart(1);
        $this->inventoryItem->update(['on_hand' => 0]);

        $this->postJson('/api/v1/checkout', $this->checkoutPayload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');
        $this->assertSame(0, Order::query()->count());
        $this->assertSame(0, $this->inventoryItem->fresh()->reserved);
        $this->assertSame(0, InventoryMovement::query()->count());
    }

    public function test_checkout_rejects_an_empty_cart(): void
    {
        $this->postJson('/api/v1/checkout', $this->checkoutPayload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');
    }

    public function test_resubmitting_checkout_returns_the_existing_order(): void
    {
        $this->addToCart(1);
        $this->postJson('/api/v1/checkout', $this->checkoutPayload())->assertCreated();

        $this->postJson('/api/v1/checkout', $this->checkoutPayload())
            ->assertOk()
            ->assertJsonPath('data.order_number', Order::query()->sole()->order_number);
        $this->assertSame(1, Order::query()->count());
        $this->assertSame(1, $this->inventoryItem->fresh()->reserved);
    }

    public function test_placing_an_order_queues_the_confirmation_email_once(): void
    {
        Mail::fake();
        $this->addToCart(1);
        $this->postJson('/api/v1/checkout', $this->checkoutPayload())->assertCreated();
        $this->postJson('/api/v1/checkout', $this->checkoutPayload())->assertOk();

        Mail::assertQueued(OrderPlacedMail::class, 1);
        Mail::assertQueued(OrderPlacedMail::class, fn ($mail) => $mail->hasTo('customer@example.com'));
    }

    public function test_authenticated_customer_can_checkout_with_a_saved_address(): void
    {
        $user = User::factory()->create(['email' => 'umair@example.com', 'password' => 'password']);
        $address = CustomerAddress::factory()->for($user)->create(['city' => 'Karachi']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();

        $this->addToCart(1);
        $this->postJson('/api/v1/checkout', $this->checkoutPayload([
            'address_id' => $address->id,
            'address' => [],
        ]))->assertCreated();

        $order = Order::query()->sole();
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('Karachi', $order->shipping_address['city']);
        $this->assertSame($user->id, InventoryMovement::query()->sole()->actor_id);
    }

    public function test_customer_cannot_use_another_customers_saved_address(): void
    {
        $otherAddress = CustomerAddress::factory()->create();
        User::factory()->create(['email' => 'umair@example.com', 'password' => 'password']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();

        $this->addToCart(1);
        $this->postJson('/api/v1/checkout', $this->checkoutPayload([
            'address_id' => $otherAddress->id,
            'address' => [],
        ]))->assertNotFound();
    }

    public function test_order_snapshot_is_immutable_after_price_changes(): void
    {
        $this->addToCart(1);
        $this->postJson('/api/v1/checkout', $this->checkoutPayload())->assertCreated();
        $this->variant->update(['price_amount' => 999999, 'title' => 'Changed']);

        $item = Order::query()->sole()->items()->sole();
        $this->assertSame(250000, $item->unit_price_amount);
        $this->assertSame('Default', $item->variant_title);
    }
}
