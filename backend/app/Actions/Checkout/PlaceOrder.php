<?php

namespace App\Actions\Checkout;

use App\InventoryMovementType;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\InventoryItem;
use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use App\OrderStatus;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PlaceOrder
{
    public const SHIPPING_AMOUNT = 30000;

    public const FREE_SHIPPING_THRESHOLD = 500000;

    public const COD_MAX_TOTAL = 1500000;

    public const VERIFICATION_HOURS = 24;

    /**
     * @param  array{recipient_name: string, phone: string, line1: string, line2?: ?string, area?: ?string, city: string, postal_code?: ?string}  $address
     */
    public function handle(
        Cart $cart,
        ?User $user,
        string $contactEmail,
        string $contactPhone,
        array $address,
    ): Order {
        $cart->loadMissing([
            'items.variant:id,product_id,sku,title,status,price_amount,currency',
            'items.variant.product:id,slug,title,status,published_at',
        ]);

        $existing = Order::query()->where('cart_id', $cart->id)->first();
        if ($existing !== null) {
            return $existing->load(['items', 'events']);
        }

        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => ['Your cart is empty.']]);
        }

        foreach ($cart->items as $item) {
            if ($item->variant->status->value !== 'active' || ! $item->variant->product->published_at || $item->variant->product->published_at->isFuture() || $item->variant->product->status->value !== 'active') {
                throw ValidationException::withMessages([
                    'cart' => ["{$item->variant->product->title} is no longer available."],
                ]);
            }
        }

        $items = $cart->items->sortBy('product_variant_id')->values();
        $subtotal = $items->sum(fn ($item): int => $item->quantity * $item->variant->price_amount);
        $shipping = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0 : self::SHIPPING_AMOUNT;
        $total = $subtotal + $shipping;

        if ($total > self::COD_MAX_TOTAL) {
            throw ValidationException::withMessages([
                'payment_method' => ['Cash on delivery is available for orders up to Rs 15,000.'],
            ]);
        }

        try {
            return DB::transaction(function () use ($cart, $user, $contactEmail, $contactPhone, $address, $items, $subtotal, $shipping, $total): Order {
                $location = Location::query()
                    ->where('is_default', true)
                    ->where('is_active', true)
                    ->firstOrFail();

                $order = Order::query()->create([
                    'order_number' => $this->orderNumber(),
                    'cart_id' => $cart->id,
                    'user_id' => $user?->id,
                    'status' => OrderStatus::PendingVerification,
                    'payment_method' => 'cod',
                    'contact_email' => $contactEmail,
                    'contact_phone' => $contactPhone,
                    'shipping_address' => $address,
                    'subtotal_amount' => $subtotal,
                    'shipping_amount' => $shipping,
                    'total_amount' => $total,
                    'currency' => 'PKR',
                    'verification_deadline_at' => now()->addHours(self::VERIFICATION_HOURS),
                ]);

                foreach ($items as $item) {
                    $variant = $item->variant;
                    $inventoryItem = InventoryItem::query()->firstOrCreate([
                        'product_variant_id' => $variant->id,
                        'location_id' => $location->id,
                    ]);
                    $inventoryItem = InventoryItem::query()->whereKey($inventoryItem->id)->lockForUpdate()->firstOrFail();

                    if ($inventoryItem->on_hand - $inventoryItem->reserved < $item->quantity) {
                        throw ValidationException::withMessages([
                            'cart' => ["{$variant->product->title} does not have enough stock for this order."],
                        ]);
                    }

                    $reservedAfter = $inventoryItem->reserved + $item->quantity;
                    $inventoryItem->update(['reserved' => $reservedAfter]);
                    $inventoryItem->movements()->create([
                        'actor_id' => $user?->id,
                        'type' => InventoryMovementType::Reservation,
                        'on_hand_delta' => 0,
                        'reserved_delta' => $item->quantity,
                        'on_hand_after' => $inventoryItem->on_hand,
                        'reserved_after' => $reservedAfter,
                        'reason_code' => 'checkout',
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                    ]);

                    $order->items()->create([
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'product_title' => $variant->product->title,
                        'variant_title' => $variant->title,
                        'product_slug' => $variant->product->slug,
                        'quantity' => $item->quantity,
                        'unit_price_amount' => $variant->price_amount,
                        'line_total_amount' => $item->quantity * $variant->price_amount,
                        'currency' => $variant->currency,
                    ]);
                }

                $cart->items()->delete();

                $order->recordEvent('placed', $user, 'cod_pending_verification');

                Mail::to($contactEmail)->queue(new OrderPlacedMail($order));

                return $order->load(['items', 'events']);
            }, 3);
        } catch (QueryException $exception) {
            $existing = Order::query()->where('cart_id', $cart->id)->first();
            if ($existing !== null) {
                return $existing->load(['items', 'events']);
            }

            throw $exception;
        }
    }

    private function orderNumber(): string
    {
        do {
            $number = 'SG-'.Str::upper(Str::random(8));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
