<?php

namespace Database\Seeders;

use App\InventoryMovementType;
use App\Models\Brand;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductType;
use App\ProductStatus;
use App\ProductVariantStatus;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Seed the 24-product launch assortment for local development.
     */
    public function run(): void
    {
        $brand = Brand::query()->where('slug', 'softglam')->firstOrFail();
        $location = Location::query()->where('is_default', true)->firstOrFail();
        $categories = Category::query()->get()->keyBy('slug');
        $types = ProductType::query()->get()->keyBy('code');

        $descriptions = [
            'earrings' => 'Light, hypoallergenic earrings made for daily wear.',
            'necklaces' => 'A delicate everyday necklace with a lasting finish.',
            'bracelets' => 'A refined bracelet that layers beautifully.',
            'rings' => 'An adjustable ring designed for everyday comfort.',
            'makeup' => 'Authentic everyday makeup, sealed and fresh.',
            'skincare' => 'Gentle skincare for your daily routine.',
        ];

        // [category slug, product title, sku, price (paisa), compare-at (paisa|null), stock]
        $catalog = [
            ['earrings', 'Everyday Gold Hoops', 'SG-EAR-001', 185000, null, 15],
            ['earrings', 'Pearl Drop Studs', 'SG-EAR-002', 145000, 175000, 12],
            ['earrings', 'Minimal Bar Studs', 'SG-EAR-003', 125000, null, 18],
            ['earrings', 'Sculpted Huggies', 'SG-EAR-004', 195000, null, 10],
            ['necklaces', 'Fine Chain Pendant', 'SG-NCK-001', 245000, null, 12],
            ['necklaces', 'Layered Pearl Necklace', 'SG-NCK-002', 295000, 340000, 8],
            ['necklaces', 'Initial Disc Necklace', 'SG-NCK-003', 215000, null, 14],
            ['necklaces', 'Delicate Lariat Chain', 'SG-NCK-004', 225000, null, 9],
            ['bracelets', 'Slim Cuff Bracelet', 'SG-BRC-001', 165000, null, 16],
            ['bracelets', 'Pearl Bead Bracelet', 'SG-BRC-002', 155000, 185000, 13],
            ['bracelets', 'Twisted Rope Bangle', 'SG-BRC-003', 175000, null, 11],
            ['rings', 'Adjustable Signet Ring', 'SG-RNG-001', 135000, null, 20],
            ['rings', 'Dainty Stack Ring', 'SG-RNG-002', 115000, null, 22],
            ['rings', 'Pearl Accent Ring', 'SG-RNG-003', 145000, 165000, 15],
            ['makeup', 'Velvet Matte Lipstick — Rosewood', 'SG-MKP-001', 95000, null, 25],
            ['makeup', 'Everyday Tinted Lip Balm', 'SG-MKP-002', 65000, 80000, 30],
            ['makeup', 'Natural Finish Compact Powder', 'SG-MKP-003', 115000, null, 18],
            ['makeup', 'Lengthening Mascara — Black', 'SG-MKP-004', 105000, null, 22],
            ['makeup', 'Soft Blend Blush — Peach', 'SG-MKP-005', 85000, 99000, 20],
            ['skincare', 'Gentle Hydrating Cleanser', 'SG-SKN-001', 125000, null, 24],
            ['skincare', 'Vitamin C Brightening Serum', 'SG-SKN-002', 195000, 225000, 16],
            ['skincare', 'Daily Moisture Cream SPF 30', 'SG-SKN-003', 145000, null, 19],
            ['skincare', 'Hyaluronic Acid Serum', 'SG-SKN-004', 175000, null, 21],
            ['skincare', 'Overnight Repair Face Oil', 'SG-SKN-005', 185000, 210000, 14],
        ];

        foreach ($catalog as [$categorySlug, $title, $sku, $price, $compareAt, $stock]) {
            $product = Product::query()->updateOrCreate(
                ['slug' => str($title)->slug()->toString()],
                [
                    'brand_id' => $brand->id,
                    'product_type_id' => $types->get($categorySlug)->id,
                    'status' => ProductStatus::Active,
                    'title' => $title,
                    'short_description' => $descriptions[$categorySlug],
                    'country_of_origin' => 'PK',
                    'published_at' => now(),
                ],
            );
            $category = $categories->get($categorySlug);
            $product->categories()->syncWithoutDetaching([$category->id => ['sort_order' => 0]]);
            if ($category->parent_id !== null) {
                $product->categories()->syncWithoutDetaching([$category->parent_id => ['sort_order' => 0]]);
            }

            $variant = $product->variants()->updateOrCreate(
                ['sku' => $sku],
                [
                    'status' => ProductVariantStatus::Active,
                    'price_amount' => $price,
                    'compare_at_amount' => $compareAt,
                    'currency' => 'PKR',
                    'is_default' => true,
                ],
            );

            $inventoryItem = InventoryItem::query()->firstOrCreate([
                'product_variant_id' => $variant->id,
                'location_id' => $location->id,
            ]);
            if ($inventoryItem->movements()->count() === 0) {
                $inventoryItem->update(['on_hand' => $stock]);
                $inventoryItem->movements()->create([
                    'type' => InventoryMovementType::ManualAdjustment,
                    'on_hand_delta' => $stock,
                    'reserved_delta' => 0,
                    'on_hand_after' => $stock,
                    'reserved_after' => 0,
                    'reason_code' => 'initial_seed',
                ]);
            }
        }
    }
}
