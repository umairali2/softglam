<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: ['support@softglam.pk'],
            subject: "Order {$this->order->order_number} confirmed",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order-confirmed',
            with: ['order' => $this->order->loadMissing('items')],
        );
    }
}
