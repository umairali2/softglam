<?php

namespace Tests\Feature\Console;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PruneGuestCartsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_it_deletes_only_old_guest_carts_without_orders(): void
    {
        $stale = Cart::factory()->create(['guest_token' => (string) Str::uuid(), 'updated_at' => now()->subDays(45)]);

        $recent = Cart::factory()->create(['guest_token' => (string) Str::uuid()]);
        $customerCart = Cart::factory()->create(['guest_token' => (string) Str::uuid(), 'user_id' => User::factory()->create()->id, 'updated_at' => now()->subDays(90)]);
        $ordered = Cart::factory()->create(['guest_token' => (string) Str::uuid(), 'updated_at' => now()->subDays(90)]);
        Order::factory()->create(['cart_id' => $ordered->id]);

        $this->artisan('carts:prune')->assertSuccessful();

        $this->assertDatabaseMissing('carts', ['id' => $stale->id]);
        $this->assertDatabaseHas('carts', ['id' => $recent->id]);
        $this->assertDatabaseHas('carts', ['id' => $customerCart->id]);
        $this->assertDatabaseHas('carts', ['id' => $ordered->id]);
    }
}
