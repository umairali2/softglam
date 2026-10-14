<?php

namespace Tests\Feature\Api\V1;

use App\Models\Brand;
use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\ProductStatus;
use App\ProductVariantStatus;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_index_returns_only_published_products_with_sellable_variants(): void
    {
        $published = Product::factory()->published()->create(['slug' => 'daily-glow']);
        ProductVariant::factory()->for($published)->create([
            'sku' => 'SG-DAILY-01',
            'price_amount' => 250000,
        ]);
        ProductVariant::factory()->for($published)->create([
            'sku' => 'SG-DAILY-02',
            'status' => ProductVariantStatus::Inactive,
        ]);
        Product::factory()->create(['slug' => 'draft-product']);
        Product::factory()->create([
            'slug' => 'future-product',
            'status' => ProductStatus::Active,
            'published_at' => now()->addDay(),
        ]);

        $response = $this->getJson('/api/v1/products');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'daily-glow')
            ->assertJsonCount(1, 'data.0.variants')
            ->assertJsonPath('data.0.variants.0.sku', 'SG-DAILY-01')
            ->assertJsonMissing(['slug' => 'draft-product'])
            ->assertJsonMissing(['slug' => 'future-product']);
    }

    public function test_show_returns_the_public_product_contract(): void
    {
        $brand = Brand::factory()->create([
            'name' => 'SoftGlam',
            'slug' => 'softglam',
        ]);
        $productType = ProductType::factory()->create([
            'code' => 'makeup',
            'name' => 'Makeup',
        ]);
        $category = Category::factory()->create([
            'name' => 'Beauty',
            'slug' => 'beauty',
        ]);
        $product = Product::factory()->published()->for($brand)->for($productType)->create([
            'title' => 'Daily Glow',
            'slug' => 'daily-glow',
            'country_of_origin' => 'PK',
        ]);
        $product->categories()->attach($category, ['sort_order' => 0]);
        ProductVariant::factory()->for($product)->create([
            'sku' => 'SG-DAILY-01',
            'price_amount' => 250000,
            'compare_at_amount' => 300000,
            'currency' => 'PKR',
        ]);

        $response = $this->getJson('/api/v1/products/daily-glow');

        $response
            ->assertOk()
            ->assertJsonPath('data.title', 'Daily Glow')
            ->assertJsonPath('data.brand.name', 'SoftGlam')
            ->assertJsonPath('data.product_type.code', 'makeup')
            ->assertJsonPath('data.categories.0.slug', 'beauty')
            ->assertJsonPath('data.variants.0.price.amount', 250000)
            ->assertJsonPath('data.variants.0.price.compare_at_amount', 300000)
            ->assertJsonPath('data.variants.0.price.currency', 'PKR')
            ->assertJsonMissingPath('data.brand.website_url');
    }

    public function test_show_exposes_safe_availability_without_inventory_quantities(): void
    {
        $product = Product::factory()->published()->create(['slug' => 'limited-product']);
        $productVariant = ProductVariant::factory()->for($product)->create();
        InventoryItem::factory()->for($productVariant)->for(Location::factory())->create([
            'on_hand' => 4,
            'reserved' => 4,
        ]);

        $response = $this->getJson('/api/v1/products/limited-product');

        $response
            ->assertOk()
            ->assertJsonPath('data.variants.0.availability.status', 'out_of_stock')
            ->assertJsonMissingPath('data.variants.0.availability.quantity')
            ->assertJsonMissingPath('data.variants.0.availability.on_hand')
            ->assertJsonMissingPath('data.variants.0.availability.reserved');
    }

    public function test_show_returns_404_for_an_unpublished_product(): void
    {
        Product::factory()->create(['slug' => 'private-draft']);

        $response = $this->getJson('/api/v1/products/private-draft');

        $response->assertNotFound();
    }
}
