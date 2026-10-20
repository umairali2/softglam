<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Checkout\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Http\Resources\Api\V1\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request, PlaceOrder $placeOrder): JsonResponse
    {
        $cart = $request->attributes->get('cart');
        $user = $request->user('sanctum');
        $validated = $request->validated();

        if (isset($validated['address_id'])) {
            if ($user === null) {
                throw ValidationException::withMessages([
                    'address_id' => ['Sign in to use a saved address.'],
                ]);
            }
            $address = $user->addresses()->findOrFail($validated['address_id'])->only([
                'recipient_name', 'phone', 'line1', 'line2', 'area', 'city', 'postal_code',
            ]);
        } else {
            $address = $validated['address'];
        }

        $order = $placeOrder->handle(
            $cart,
            $user,
            $validated['contact_email'],
            $validated['contact_phone'],
            $address,
        );

        return (new OrderResource($order))
            ->response()
            ->setStatusCode($order->wasRecentlyCreated ? 201 : 200);
    }
}
