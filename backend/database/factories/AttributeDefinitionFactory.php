<?php

namespace Database\Factories;

use App\AttributeDataType;
use App\Models\AttributeDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttributeDefinition>
 */
class AttributeDefinitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->slug(2),
            'label' => fake()->words(2, true),
            'data_type' => AttributeDataType::ShortText,
            'description' => fake()->sentence(),
            'unit' => null,
            'allowed_values' => null,
            'validation_rules' => null,
            'is_filterable' => false,
            'is_searchable' => false,
        ];
    }
}
