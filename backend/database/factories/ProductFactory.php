<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductType;
use App\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'brand_id' => Brand::factory(),
            'product_type_id' => ProductType::factory(),
            'status' => ProductStatus::Draft,
            'title' => $title,
            'slug' => fake()->unique()->slug(),
            'subtitle' => fake()->sentence(4),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'country_of_origin' => 'PK',
            'published_at' => null,
            'archived_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => ProductStatus::Active,
            'published_at' => now()->subMinute(),
        ]);
    }
}
