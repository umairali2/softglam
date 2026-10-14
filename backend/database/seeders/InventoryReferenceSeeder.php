<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class InventoryReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::query()->updateOrCreate(
            ['code' => 'karachi-main'],
            [
                'name' => 'Karachi Fulfillment',
                'is_active' => true,
                'is_default' => true,
            ],
        );
    }
}
