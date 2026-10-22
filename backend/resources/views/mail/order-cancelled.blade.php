<x-mail::message>
# Order cancelled

Order **{{ $order->order_number }}** has been cancelled. You will not be charged.

@if ($order->status_reason && ! in_array($order->status_reason, ['cancelled_by_customer', 'verification_expired']))
**Reason:** {{ $order->status_reason }}
@elseif ($order->status_reason === 'verification_expired')
We couldn't reach you within 24 hours to confirm the order, so it was released. You're welcome to place it again — payment is only taken on delivery.
@endif

If this was a mistake, reply to this email and we'll help.

Thanks,<br>
SoftGlam
</x-mail::message>
