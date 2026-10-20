<?php

namespace App\Http\Controllers\Api\V1\Admin;

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
}
