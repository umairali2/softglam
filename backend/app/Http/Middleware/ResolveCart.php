<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResolveCart
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('sanctum');
        $token = null;

        if ($user !== null) {
            $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
        } else {
            $token = $request->cookie('cart_token') ?: Str::uuid()->toString();
            $cart = Cart::query()->firstOrCreate(['guest_token' => $token]);
        }

        $request->attributes->set('cart', $cart);

        $response = $next($request);

        if ($user === null && $request->cookie('cart_token') !== $cart->guest_token) {
            $response->cookie(cookie(
                'cart_token',
                $cart->guest_token,
                minutes: 60 * 24 * 30,
                httpOnly: true,
                sameSite: 'lax',
            ));
        }

        return $response;
    }
}
