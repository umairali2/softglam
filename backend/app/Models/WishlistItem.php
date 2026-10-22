<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'product_id'])]
class WishlistItem extends Model
{
    use HasUuids;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
