<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['product_id', 'path', 'alt', 'position'])]
class ProductImage extends Model
{
    use HasUuids;

    public const DISK = 'public';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): string
    {
        return Storage::disk(self::DISK)->url($this->path);
    }

    protected static function booted(): void
    {
        static::deleting(fn (ProductImage $image) => Storage::disk(self::DISK)->delete($image->path));
    }
}
