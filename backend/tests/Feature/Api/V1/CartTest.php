<?php

namespace Tests\Feature\Api\V1;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\ProductVariantStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
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

        $product = Product::factory()->published()->create([
            'slug' => 'daily-glow',
            'title' => 'Daily Glow',
        ]);
        $this->variant = ProductVariant::factory()->for($product)->create([
            'sku' => 'SG-DAILY-01',
            'price_amount' => 250000,
            'currency' => 'PKR',
        ]);
        InventoryItem::factory()->for($this->variant)->for(Location::factory())->create([
            'on_hand' => 5,
        ]);
    }

    public function test_guest_can_add_items_and_receives_a_cart_cookie(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 2,
        ]);

        $response
            ->assertOk()
            ->assertCookie('cart_token')
            ->assertJsonPath('data.totals.item_count', 2)
            ->assertJsonPath('data.totals.subtotal_amount', 500000)
            ->assertJsonPath('data.items.0.unit_price.amount', 250000)
            ->assertJsonPath('data.items.0.variant.sku', 'SG-DAILY-01')
            ->assertJsonPath('data.items.0.variant.product.slug', 'daily-glow');
    }

    public function test_guest_cart_persists_across_requests(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);
        $cookie = $response->getCookie('cart_token')->getValue();

        $this->withCookie('cart_token', $cookie)
            ->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.totals.item_count', 1);
    }

    public function test_adding_the_same_variant_increments_quantity(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);
        $cookie = $response->getCookie('cart_token')->getValue();

        $this->withCookie('cart_token', $cookie)
            ->postJson('/api/v1/cart/items', [
                'product_variant_id' => $this->variant->id,
                'quantity' => 2,
            ])
            ->assertOk()
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.quantity', 3);
    }

    public function test_item_quantity_can_be_updated_and_item_removed(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);
        $cookie = $response->getCookie('cart_token')->getValue();
        $itemId = $response->json('data.items.0.id');

        $this->withCookie('cart_token', $cookie)
            ->patchJson("/api/v1/cart/items/{$itemId}", ['quantity' => 4])
            ->assertOk()
            ->assertJsonPath('data.items.0.quantity', 4);

        $this->withCookie('cart_token', $cookie)
            ->deleteJson("/api/v1/cart/items/{$itemId}")
            ->assertNoContent();
        $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
    }

    public function test_quantity_beyond_available_stock_is_rejected(): void
    {
        $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 6,
        ])->assertUnprocessable()->assertJsonValidationErrors('quantity');

        $response = $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 4,
        ]);
        $cookie = $response->getCookie('cart_token')->getValue();
        $itemId = $response->json('data.items.0.id');

        $this->withCookie('cart_token', $cookie)
            ->patchJson("/api/v1/cart/items/{$itemId}", ['quantity' => 6])
            ->assertUnprocessable()->assertJsonValidationErrors('quantity');
    }

    public function test_unsellable_variants_cannot_be_added(): void
    {
        $inactive = ProductVariant::factory()->for($this->variant->product)->create([
            'status' => ProductVariantStatus::Inactive,
        ]);

        $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $inactive->id,
            'quantity' => 1,
        ])->assertNotFound();
    }

    public function test_authenticated_customer_has_a_persistent_cart(): void
    {
        User::factory()->create(['email' => 'umair@example.com', 'password' => 'password']);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();

        $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ])->assertOk();

        $cart = Cart::query()->whereNotNull('user_id')->sole();
        $this->assertCount(1, $cart->items);
    }

    public function test_a_cart_cannot_modify_another_carts_item(): void
    {
        $other = Cart::factory()->has(CartItem::factory()->state([
            'product_variant_id' => $this->variant->id,
        ]), 'items')->create();
        $otherItemId = $other->items()->sole()->id;

        $this->postJson('/api/v1/cart/items', [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);
        // Fresh guest cart — attempt to touch the other cart's item.
        $this->patchJson("/api/v1/cart/items/{$otherItemId}", ['quantity' => 2])
            ->assertNotFound();
        $this->deleteJson("/api/v1/cart/items/{$otherItemId}")->assertNotFound();
    }

    public function test_empty_cart_returns_zero_totals(): void
    {
        $this->getJson('/api/v1/cart')
            ->assertOk()
            ->assertJsonPath('data.totals.item_count', 0)
            ->assertJsonPath('data.totals.subtotal_amount', 0)
            ->assertJsonCount(0, 'data.items');
    }
}
