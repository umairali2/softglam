<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CartResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $cart->loadMissing([
            'items.variant:id,product_id,sku,title,status,price_amount,currency',
            'items.variant.product:id,slug,title,status,published_at',
            'items.variant.inventoryItems:id,product_variant_id,location_id,on_hand,reserved',
            'items.variant.inventoryItems.location:id,is_active',
        ]);

        return (new CartResource($cart))->response()->setStatusCode(200);
    }
}
