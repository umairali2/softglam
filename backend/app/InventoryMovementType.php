<?php

namespace App;

enum InventoryMovementType: string
{
    case ManualAdjustment = 'manual_adjustment';
    case Reservation = 'reservation';
    case Release = 'release';
    case Sale = 'sale';
    case Return = 'return';
    case WriteOff = 'write_off';
}
