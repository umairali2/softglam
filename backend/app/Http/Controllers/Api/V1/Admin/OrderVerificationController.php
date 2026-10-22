<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Orders\FulfillOrder;
use App\Actions\Orders\ReleaseOrderReservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderVerificationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->with('items')
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->oldest('verification_deadline_at')
            ->cursorPaginate(20);

        return OrderResource::collection($orders);
    }

    public function confirm(Request $request, Order $order): OrderResource
    {
        if ($order->status !== OrderStatus::PendingVerification) {
            throw ValidationException::withMessages([
                'status' => ['Only orders pending verification can be confirmed.'],
            ]);
        }

        $order->update(['status' => OrderStatus::Confirmed]);

        return new OrderResource($order->fresh()->load('items'));
    }

    public function decline(Request $request, Order $order, ReleaseOrderReservation $release): OrderResource
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($order->status !== OrderStatus::PendingVerification) {
            throw ValidationException::withMessages([
                'status' => ['Only orders pending verification can be declined.'],
            ]);
        }

        DB::transaction(function () use ($order, $release, $validated): void {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $release->handle($order, 'verification_declined');
            $order->update([
                'status' => OrderStatus::Cancelled,
                'status_reason' => $validated['reason'],
            ]);
        }, 3);

        return new OrderResource($order->fresh()->load('items'));
    }

    public function fulfill(Request $request, Order $order, FulfillOrder $fulfill): OrderResource
    {
        $validated = $request->validate([
            'courier_name' => ['nullable', 'string', 'max:80'],
            'tracking_number' => ['nullable', 'string', 'max:80'],
        ]);

        if ($order->status !== OrderStatus::Confirmed) {
            throw ValidationException::withMessages([
                'status' => ['Only confirmed orders can be fulfilled.'],
            ]);
        }

        DB::transaction(function () use ($order, $fulfill, $validated): void {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $fulfill->handle($order);
            $order->update([
                'status' => OrderStatus::Fulfilled,
                'courier_name' => $validated['courier_name'] ?? null,
                'tracking_number' => $validated['tracking_number'] ?? null,
                'fulfilled_at' => now(),
            ]);
        }, 3);

        return new OrderResource($order->fresh()->load('items'));
    }

    public function cancel(Request $request, Order $order, ReleaseOrderReservation $release): OrderResource
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if (in_array($order->status, [OrderStatus::Fulfilled, OrderStatus::Cancelled])) {
            throw ValidationException::withMessages([
                'status' => ['Fulfilled or cancelled orders cannot be cancelled.'],
            ]);
        }

        DB::transaction(function () use ($order, $release, $validated): void {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            $release->handle($order, 'order_cancelled');
            $order->update([
                'status' => OrderStatus::Cancelled,
                'status_reason' => $validated['reason'],
                'cancelled_at' => now(),
            ]);
        }, 3);

        return new OrderResource($order->fresh()->load('items'));
    }
}
