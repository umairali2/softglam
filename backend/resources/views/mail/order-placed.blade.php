<x-mail::message>
# Thank you for your order

Order **{{ $order->order_number }}** was received and is awaiting a quick confirmation call.

We'll call **{{ $order->contact_phone }}** within 24 hours to confirm your order.

<x-mail::table>
| Item | Qty | Total |
|:-----|----:|------:|
@foreach ($order->items as $item)
| {{ $item->product_title }}{{ $item->variant_title ? " — {$item->variant_title}" : "" }} | {{ $item->quantity }} | Rs {{ number_format($item->line_total_amount / 100) }} |
@endforeach
| **Subtotal** | | Rs {{ number_format($order->subtotal_amount / 100) }} |
| **Shipping** | | {{ $order->shipping_amount === 0 ? 'Free' : 'Rs '.number_format($order->shipping_amount / 100) }} |
| **Total (COD)** | | **Rs {{ number_format($order->total_amount / 100) }}** |
</x-mail::table>

**Delivery to:** {{ $order->shipping_address['recipient_name'] }}, {{ $order->shipping_address['line1'] }}, {{ $order->shipping_address['city'] }}

You can reply to this email if you need help — but please don't send payment details by email.

Thanks,<br>
SoftGlam
</x-mail::message>
