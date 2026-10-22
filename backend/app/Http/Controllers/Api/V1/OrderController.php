<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()
            ->latest()
            ->cursorPaginate(10);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, string $orderNumber): OrderResource
    {
        $order = Order::query()->with(['items', 'events'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $user = $request->user('sanctum');
        $cart = $request->attributes->get('cart');
        $ownsByAccount = $user !== null && $order->user_id === $user->id;
        $ownsByGuestCart = $cart !== null && $order->cart_id === $cart->id;

        abort_unless($ownsByAccount || $ownsByGuestCart, 404);

        return new OrderResource($order);
    }
}
