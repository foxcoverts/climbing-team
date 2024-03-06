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

    public function getButtonLabel(): string
    {
        return 'View Booking';
    }

    public function getButtonUrl(): string
    {
        return route('booking.show', $this->booking);
    }
}
