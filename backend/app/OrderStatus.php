<?php

namespace App;

enum OrderStatus: string
{
    case PendingVerification = 'pending_verification';
    case Confirmed = 'confirmed';
    case Fulfilled = 'fulfilled';
    case Cancelled = 'cancelled';
}
