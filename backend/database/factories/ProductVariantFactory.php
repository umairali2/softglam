<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\ProductVariantStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => fake()->unique()->bothify('SG-####-??'),
            'barcode' => null,
            'title' => null,
            'status' => ProductVariantStatus::Active,
            'price_amount' => fake()->numberBetween(100000, 2500000),
            'compare_at_amount' => null,
            'currency' => 'PKR',
            'weight_g' => fake()->randomFloat(3, 1, 500),
            'is_default' => true,
        ];
    }
}
