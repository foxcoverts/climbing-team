<?php

namespace App\Mail;

use App\iCal\Domain\Enum\CalendarMethod;
use Illuminate\Mail\Mailables\Content;

class BookingCancelled extends BookingInvite
{
    public function getSubject(): string
    {
        return 'Cancelled invitation: :activity @ :start';
    }

    public function getTitle(): string
    {
        return 'Booking Cancelled';
    }

    public function getCalendarMethod(): CalendarMethod
    {
        return CalendarMethod::Cancel;
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.booking.cancelled',
            with: [
                'title' => __($this->getTitle()),
                'when' => $this->buildDateString(),
            ]
        );
    }
}
