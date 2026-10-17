<?php

namespace App\Models;

use App\ProductVariantStatus;
use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_id', 'sku', 'barcode', 'title', 'status', 'price_amount', 'compare_at_amount', 'currency', 'weight_g', 'is_default'])]
class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory, HasUuids;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function availableQuantity(): int
    {
        $items = $this->relationLoaded('inventoryItems')
            ? $this->inventoryItems
            : $this->inventoryItems()->with('location:id,is_active')->get();

        return $items
            ->filter(fn (InventoryItem $item): bool => $item->location->is_active)
            ->sum(fn (InventoryItem $item): int => $item->availableQuantity());
    }

    #[Scope]
    protected function sellable(Builder $query): Builder
    {
        return $query->where('status', ProductVariantStatus::Active->value);
    }

    protected function casts(): array
    {
        return [
            'status' => ProductVariantStatus::class,
            'price_amount' => 'integer',
            'compare_at_amount' => 'integer',
            'weight_g' => 'decimal:3',
            'is_default' => 'boolean',
        ];
    }
}
