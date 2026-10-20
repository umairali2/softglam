<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MergeGuestCart
{
    public function handle(?string $guestToken, User $user): void
    {
        if ($guestToken === null || $guestToken === '') {
            return;
        }

        $guestCart = Cart::query()->where('guest_token', $guestToken)->first();
        if ($guestCart === null || $guestCart->items()->doesntExist()) {
            return;
        }

        DB::transaction(function () use ($guestCart, $user): void {
            $userCart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

            foreach ($guestCart->items()->with('variant')->get() as $item) {
                $existing = $userCart->items()->where('product_variant_id', $item->product_variant_id)->first();

                if ($existing !== null) {
                    $available = $item->variant->availableQuantity();
                    $existing->update([
                        'quantity' => min($existing->quantity + $item->quantity, $available),
                    ]);
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }

            $guestCart->delete();
        }, 3);
    }
}
