<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class PruneGuestCarts extends Command
{
    protected $signature = 'carts:prune {--days=30 : Delete guest carts inactive for this many days}';

    protected $description = 'Delete guest carts that have been inactive beyond the cookie lifetime';

    public function handle(): int
    {
        $cutoff = now()->subDays((int) $this->option('days'));

        $deleted = Cart::query()
            ->whereNull('user_id')
            ->whereNotNull('guest_token')
            ->where('updated_at', '<', $cutoff)
            ->whereDoesntHave('order')
            ->delete();

        $this->info("Pruned {$deleted} stale guest carts.");

        return self::SUCCESS;
    }
}
