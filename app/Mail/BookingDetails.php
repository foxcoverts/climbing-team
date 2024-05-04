<?php

namespace App\Mail;

class BookingDetails extends BookingInvite
{
    public function getSubject(): string
    {
        return 'Updated invitation: :activity @ :start';
    }

    public function getTitle(): string
    {
        return 'Booking Details';
    }

    public function getTemplate(): string
    {
        return 'mail.booking.update';
    }
}
