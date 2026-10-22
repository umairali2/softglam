<?php

namespace App\Models;

use Database\Factories\OrderReturnFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'status', 'reason', 'resolution_note', 'handled_by', 'refund_amount', 'resolved_at'])]
class OrderReturn extends Model
{
    /** @use HasFactory<OrderReturnFactory> */
    use HasFactory, HasUuids;

    public const TABLE = 'order_returns';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected function casts(): array
    {
        return ['resolved_at' => 'datetime'];
    }
}
