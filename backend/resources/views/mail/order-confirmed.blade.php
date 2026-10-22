<x-mail::message>
# Order confirmed

Your order **{{ $order->order_number }}** is confirmed and being packed.

Please keep **Rs {{ number_format($order->total_amount / 100) }}** ready — payment is collected on delivery.

<x-mail::table>
| Item | Qty |
|:-----|----:|
@foreach ($order->items as $item)
| {{ $item->product_title }}{{ $item->variant_title ? " — {$item->variant_title}" : "" }} | {{ $item->quantity }} |
@endforeach
</x-mail::table>

**Delivery to:** {{ $order->shipping_address['recipient_name'] }}, {{ $order->shipping_address['line1'] }}, {{ $order->shipping_address['city'] }}

Thanks,<br>
SoftGlam
</x-mail::message>
