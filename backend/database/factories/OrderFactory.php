<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'SG-'.Str::upper(Str::random(8)),
            'cart_id' => Cart::factory(),
            'user_id' => null,
            'status' => OrderStatus::PendingVerification,
            'payment_method' => 'cod',
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => '03001234567',
            'shipping_address' => [
                'recipient_name' => fake()->name(),
                'phone' => '03001234567',
                'line1' => fake()->streetAddress(),
                'city' => 'Karachi',
            ],
            'subtotal_amount' => 250000,
            'shipping_amount' => 30000,
            'total_amount' => 280000,
            'currency' => 'PKR',
            'verification_deadline_at' => now()->addDay(),
        ];
    }
}
