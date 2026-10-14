<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class CatalogReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::query()->updateOrCreate(
            ['slug' => 'softglam'],
            ['name' => 'SoftGlam', 'is_active' => true],
        );

        foreach ([
            'earrings' => 'Earrings',
            'necklaces' => 'Necklaces',
            'bracelets' => 'Bracelets',
            'rings' => 'Rings',
            'makeup' => 'Makeup',
            'skincare' => 'Skincare',
        ] as $code => $name) {
            ProductType::query()->updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'is_active' => true],
            );
        }

        $jewellery = Category::query()->updateOrCreate(
            ['slug' => 'jewellery'],
            ['name' => 'Jewellery', 'is_active' => true, 'sort_order' => 0],
        );
        $beauty = Category::query()->updateOrCreate(
            ['slug' => 'beauty'],
            ['name' => 'Beauty', 'is_active' => true, 'sort_order' => 1],
        );

        foreach ([
            [$jewellery, 'earrings', 'Earrings', 0],
            [$jewellery, 'necklaces', 'Necklaces', 1],
            [$jewellery, 'bracelets', 'Bracelets', 2],
            [$jewellery, 'rings', 'Rings', 3],
            [$beauty, 'makeup', 'Makeup', 0],
            [$beauty, 'skincare', 'Skincare', 1],
        ] as [$parent, $slug, $name, $sortOrder]) {
            Category::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'parent_id' => $parent->id,
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $sortOrder,
                ],
            );
        }
    }
}
