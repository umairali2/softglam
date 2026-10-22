<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

#[Fillable(['order_id', 'actor_id', 'type', 'note'])]
class OrderEvent extends Model
{
    use HasUuids;

    public const UPDATED_AT = null;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Order events are immutable.'));
        static::deleting(fn () => throw new LogicException('Order events are immutable.'));
    }

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
