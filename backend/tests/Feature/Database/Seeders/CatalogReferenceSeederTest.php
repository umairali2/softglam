<?php

namespace Tests\Feature\Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductType;
use Database\Seeders\CatalogReferenceSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CatalogReferenceSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeds_the_launch_taxonomy_idempotently(): void
    {
        $this->seed(CatalogReferenceSeeder::class);
        $this->seed(CatalogReferenceSeeder::class);

        $this->assertSame(1, Brand::query()->where('slug', 'softglam')->count());
        $this->assertSame(6, ProductType::query()->count());
        $this->assertSame(8, Category::query()->count());
        $this->assertDatabaseHas('categories', [
            'slug' => 'makeup',
            'parent_id' => Category::query()->where('slug', 'beauty')->value('id'),
        ]);
    }
}
