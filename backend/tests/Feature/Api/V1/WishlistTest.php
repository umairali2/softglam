<?php

namespace Tests\Feature\Api\V1;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials();
        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);
    }

    private function login(string $email): void
    {
        $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_wishlist_requires_authentication(): void
    {
        $this->getJson('/api/v1/me/wishlist')->assertUnauthorized();
    }

    public function test_add_remove_and_list_wishlist_products(): void
    {
        $user = User::factory()->create(['email' => 'c@example.com', 'password' => 'password']);
        $this->login('c@example.com');

        $product = Product::factory()->published()->create(['title' => 'Pearl Studs']);
        ProductVariant::factory()->for($product)->create();
        $draft = Product::factory()->create(); // unpublished

        $this->postJson('/api/v1/me/wishlist', ['product_id' => $product->id])->assertNoContent();
        // Idempotent re-add
        $this->postJson('/api/v1/me/wishlist', ['product_id' => $product->id])->assertNoContent();
        $this->assertSame(1, $user->wishlistItems()->count());

        $this->postJson('/api/v1/me/wishlist', ['product_id' => $draft->id])->assertNoContent();
        // Draft products are not listed even though saved
        $this->getJson('/api/v1/me/wishlist')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Pearl Studs');

        $this->deleteJson("/api/v1/me/wishlist/{$product->id}")->assertNoContent();
        $this->getJson('/api/v1/me/wishlist')->assertJsonCount(0, 'data');
    }

    public function test_wishlists_are_scoped_per_user(): void
    {
        $other = User::factory()->create(['email' => 'a@example.com', 'password' => 'password']);
        $product = Product::factory()->published()->create();
        $other->wishlistItems()->create(['product_id' => $product->id]);

        User::factory()->create(['email' => 'b@example.com', 'password' => 'password']);
        $this->login('b@example.com');

        $this->getJson('/api/v1/me/wishlist')->assertJsonCount(0, 'data');
    }
}
