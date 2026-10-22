<x-mail::message>
# Your order is on its way

Order **{{ $order->order_number }}** has been handed to the courier.

@if ($order->courier_name || $order->tracking_number)
**Tracking:** {{ $order->courier_name }}{{ $order->courier_name && $order->tracking_number ? ' — ' : '' }}{{ $order->tracking_number }}
@endif

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
