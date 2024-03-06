<?php

namespace App\Mail;

class BookingConfirmed extends BookingChanged
{
    public function getTitle(): string
    {
        return 'Booking Confirmed';
    }
}
