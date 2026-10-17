<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CartItemController extends Controller
{
    public function store(StoreCartItemRequest $request): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $variant = ProductVariant::query()
            ->sellable()
            ->whereHas('product', fn ($query) => $query->published())
            ->findOrFail($request->validated('product_variant_id'));

        $item = $cart->items()->firstOrNew(['product_variant_id' => $variant->id]);
        $quantity = ($item->exists ? $item->quantity : 0) + $request->integer('quantity');
        $this->assertAvailable($variant, $quantity);

        $item->fill([
            'quantity' => $quantity,
            'unit_price_amount' => $variant->price_amount,
            'currency' => $variant->currency,
        ])->save();

        return $this->cartResource($request);
    }

    public function update(UpdateCartItemRequest $request, string $item): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $item = $cart->items()->with('variant')->findOrFail($item);
        $quantity = $request->integer('quantity');
        $this->assertAvailable($item->variant, $quantity);

        $item->update(['quantity' => $quantity]);

        return $this->cartResource($request);
    }

    public function destroy(Request $request, string $item): Response
    {
        $request->attributes->get('cart')->items()->findOrFail($item)->delete();

        return response()->noContent();
    }

    private function assertAvailable(ProductVariant $variant, int $quantity): void
    {
        if ($variant->availableQuantity() < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Only '.$variant->availableQuantity().' unit(s) are available for this item.'],
            ]);
        }
    }

    private function cartResource(Request $request): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $cart->unsetRelations();
        $cart->loadMissing([
            'items.variant:id,product_id,sku,title,status,price_amount,currency',
            'items.variant.product:id,slug,title,status,published_at',
            'items.variant.inventoryItems:id,product_variant_id,location_id,on_hand,reserved',
            'items.variant.inventoryItems.location:id,is_active',
        ]);

        return (new CartResource($cart))->response()->setStatusCode(200);
    }
}
