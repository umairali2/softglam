<?php

namespace App\Console\Commands;

use App\InventoryMovementType;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireUnverifiedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending COD orders past their verification deadline and release reserved stock.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expired = Order::query()
            ->where('status', OrderStatus::PendingVerification)
            ->where('verification_deadline_at', '<', now())
            ->pluck('id');

        foreach ($expired as $orderId) {
            DB::transaction(function () use ($orderId): void {
                $order = Order::query()->whereKey($orderId)->lockForUpdate()->firstOrFail();
                if ($order->status !== OrderStatus::PendingVerification) {
                    return;
                }

                $reservations = InventoryMovement::query()
                    ->where('reference_type', 'order')
                    ->where('reference_id', $order->id)
                    ->where('type', InventoryMovementType::Reservation)
                    ->get();

                foreach ($reservations as $reservation) {
                    $item = InventoryItem::query()
                        ->whereKey($reservation->inventory_item_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $quantity = min($reservation->reserved_delta, $item->reserved);
                    if ($quantity === 0) {
                        continue;
                    }

                    $reservedAfter = $item->reserved - $quantity;
                    $item->update(['reserved' => $reservedAfter]);
                    $item->movements()->create([
                        'type' => InventoryMovementType::Release,
                        'on_hand_delta' => 0,
                        'reserved_delta' => -$quantity,
                        'on_hand_after' => $item->on_hand,
                        'reserved_after' => $reservedAfter,
                        'reason_code' => 'verification_expired',
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                    ]);
                }

                $order->update(['status' => OrderStatus::Cancelled]);
            }, 3);
        }

        $this->info("Expired {$expired->count()} order(s).");

        return self::SUCCESS;
    }
}
