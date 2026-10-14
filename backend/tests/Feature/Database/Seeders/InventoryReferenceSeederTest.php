<?php

namespace Tests\Feature\Database\Seeders;

use App\Models\Location;
use Database\Seeders\InventoryReferenceSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class InventoryReferenceSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_seeds_one_default_karachi_location_idempotently(): void
    {
        $this->seed(InventoryReferenceSeeder::class);
        $this->seed(InventoryReferenceSeeder::class);

        $this->assertSame(1, Location::query()->count());
        $this->assertDatabaseHas('locations', [
            'code' => 'karachi-main',
            'name' => 'Karachi Fulfillment',
            'is_active' => true,
            'is_default' => true,
        ]);
    }
}
