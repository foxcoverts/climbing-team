<?php

namespace App\Mail;

use App\Enums\BookingStatus;
use App\iCal\Domain\Enum\CalendarMethod;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class BookingCancelled extends BookingInvite
{
    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public User $attendee,
        public string $reason,
    ) {
        parent::__construct($booking, $attendee, [
            'status' => BookingStatus::Cancelled->value,
            'reason' => $reason,
        ]);
    }

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
                'reason' => $this->reason,
            ]
        );
    }
}
