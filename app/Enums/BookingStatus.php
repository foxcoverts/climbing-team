<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Tentative = 'tentative';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
