<?php

namespace App\Console\Commands;

use App\Actions\Orders\ReleaseOrderReservation;
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
    public function handle(ReleaseOrderReservation $release): int
    {
        $expired = Order::query()
            ->where('status', OrderStatus::PendingVerification)
            ->where('verification_deadline_at', '<', now())
            ->pluck('id');

        foreach ($expired as $orderId) {
            DB::transaction(function () use ($orderId, $release): void {
                $order = Order::query()->whereKey($orderId)->lockForUpdate()->firstOrFail();
                if ($order->status !== OrderStatus::PendingVerification) {
                    return;
                }

                $release->handle($order, 'verification_expired');
                $order->update([
                    'status' => OrderStatus::Cancelled,
                    'status_reason' => 'verification_expired',
                ]);
                $order->recordEvent('expired', null, 'verification_expired');
            }, 3);
        }

        $this->info("Expired {$expired->count()} order(s).");

        return self::SUCCESS;
    }
}
