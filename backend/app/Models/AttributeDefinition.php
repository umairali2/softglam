<?php

namespace App\Models;

use App\AttributeDataType;
use Database\Factories\AttributeDefinitionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['code', 'label', 'data_type', 'description', 'unit', 'allowed_values', 'validation_rules', 'is_filterable', 'is_searchable'])]
class AttributeDefinition extends Model
{
    /** @use HasFactory<AttributeDefinitionFactory> */
    use HasFactory, HasUuids;

    public function productTypes(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class)
            ->withPivot(['is_required', 'sort_order']);
    }

    protected function casts(): array
    {
        return [
            'data_type' => AttributeDataType::class,
            'allowed_values' => 'array',
            'validation_rules' => 'array',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
        ];
    }
}
