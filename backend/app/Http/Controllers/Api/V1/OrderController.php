<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Orders\ReleaseOrderReservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

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
        $order = $this->resolveOwnedOrder($request, $orderNumber);

        return new OrderResource($order);
    }

    public function requestReturn(Request $request, string $orderNumber): JsonResponse
    {
        $order = $this->resolveOwnedOrder($request, $orderNumber);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($order->status !== OrderStatus::Fulfilled) {
            throw ValidationException::withMessages([
                'status' => ['Only fulfilled orders are eligible for a return.'],
            ]);
        }

        $return = $order->returns()->create([
            'status' => 'requested',
            'reason' => $validated['reason'],
        ]);

        return response()->json(['data' => ['id' => $return->id, 'status' => $return->status]], 201);
    }

    public function cancel(Request $request, string $orderNumber, ReleaseOrderReservation $release): OrderResource
    {
        $order = $this->resolveOwnedOrder($request, $orderNumber);

        if ($order->status !== OrderStatus::PendingVerification) {
            throw ValidationException::withMessages([
                'status' => ['Only orders awaiting confirmation can be cancelled. Contact support for confirmed orders.'],
            ]);
        }

        DB::transaction(function () use ($order, $release, $user): void {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $release->handle($order, 'customer_cancelled');
            $order->update([
                'status' => OrderStatus::Cancelled,
                'status_reason' => 'cancelled_by_customer',
                'cancelled_at' => now(),
            ]);
            $order->recordEvent('cancelled', $user, 'cancelled_by_customer');
            Mail::to($order->contact_email)->queue(new OrderCancelledMail($order));
        }, 3);

        return new OrderResource($order->fresh()->load(['items', 'events']));
    }

    private function resolveOwnedOrder(Request $request, string $orderNumber): Order
    {
        $order = Order::query()->with(['items', 'events'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $user = $request->user('sanctum');
        $cart = $request->attributes->get('cart');
        $ownsByAccount = $user !== null && $order->user_id === $user->id;
        $ownsByGuestCart = $cart !== null && $order->cart_id === $cart->id;
        abort_unless($ownsByAccount || $ownsByGuestCart, 404);

        return $order;
    }
}
