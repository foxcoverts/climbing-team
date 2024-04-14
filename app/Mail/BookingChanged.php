<?php

namespace App\Mail;

class BookingChanged extends BookingInvite
{
    public function getSubject(): string
    {
        return 'Updated invitation: :activity @ :start';
    }

    public function getTitle(): string
    {
        return 'Booking Changed';
    }
}
