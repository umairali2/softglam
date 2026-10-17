<?php

namespace Tests\Feature\Api\V1;

use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CustomerAddressTest extends TestCase
{
    use LazilyRefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Origin' => 'http://localhost:3000',
            'Referer' => 'http://localhost:3000',
        ]);

        $this->user = User::factory()->create([
            'email' => 'umair@example.com',
            'password' => 'password',
        ]);
        $this->postJson('/api/v1/auth/login', [
            'email' => 'umair@example.com',
            'password' => 'password',
        ])->assertOk();
    }

    public function test_customer_can_create_and_list_addresses(): void
    {
        $this->postJson('/api/v1/me/addresses', [
            'recipient_name' => 'Umair Ali',
            'phone' => '03001234567',
            'line1' => 'House 12, Street 3',
            'area' => 'DHA Phase 6',
            'city' => 'Karachi',
            'is_default' => true,
        ])->assertCreated()->assertJsonPath('data.is_default', true);

        $this->getJson('/api/v1/me/addresses')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.city', 'Karachi')
            ->assertJsonMissingPath('data.0.user_id');
    }

    public function test_customer_can_update_and_delete_an_address(): void
    {
        $address = CustomerAddress::factory()->for($this->user)->create(['city' => 'Karachi']);

        $this->putJson("/api/v1/me/addresses/{$address->id}", [
            'recipient_name' => 'Umair Ali',
            'phone' => '03001234567',
            'line1' => 'Flat 4B',
            'city' => 'Karachi',
        ])->assertOk()->assertJsonPath('data.line1', 'Flat 4B');

        $this->deleteJson("/api/v1/me/addresses/{$address->id}")->assertNoContent();
        $this->assertDatabaseMissing('customer_addresses', ['id' => $address->id]);
    }

    public function test_a_customer_cannot_touch_another_customers_address(): void
    {
        $other = CustomerAddress::factory()->create();

        $this->putJson("/api/v1/me/addresses/{$other->id}", [
            'recipient_name' => 'X',
            'phone' => '03001234567',
            'line1' => 'X',
            'city' => 'Karachi',
        ])->assertNotFound();
        $this->deleteJson("/api/v1/me/addresses/{$other->id}")->assertNotFound();
    }

    public function test_setting_a_default_address_clears_the_previous_default(): void
    {
        $first = CustomerAddress::factory()->for($this->user)->create(['is_default' => true]);
        $second = CustomerAddress::factory()->for($this->user)->create();

        $this->putJson("/api/v1/me/addresses/{$second->id}", [
            'recipient_name' => $second->recipient_name,
            'phone' => $second->phone,
            'line1' => $second->line1,
            'city' => $second->city,
            'is_default' => true,
        ])->assertOk();

        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);
    }

    public function test_validation_requires_delivery_fields(): void
    {
        $this->postJson('/api/v1/me/addresses', [])->assertUnprocessable()
            ->assertJsonValidationErrors(['recipient_name', 'phone', 'line1', 'city']);
    }

    public function test_guests_cannot_manage_addresses(): void
    {
        auth('web')->logout();

        $this->getJson('/api/v1/me/addresses')->assertUnauthorized();
        $this->postJson('/api/v1/me/addresses', [])->assertUnauthorized();
    }
}
