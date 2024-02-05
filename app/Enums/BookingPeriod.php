<?php

namespace App\Enums;

enum BookingPeriod: string
{
    case Past = 'past';
    case Future = 'future';
}
