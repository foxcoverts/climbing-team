<?php

namespace App\Listeners;

use App\Enums\BookingAttendeeStatus;
use App\Events\BookingConfirmed;
use App\Mail\BookingConfirmed as MailBookingConfirmed;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmedEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void
    {
        foreach ($event->booking->attendees as $attendee) {
            if (in_array($attendee->attendance->status, [BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative])) {
                Mail::to($attendee->email)
                    ->send(new MailBookingConfirmed(
                        $event->booking,
                        $attendee,
                        $event->changes
                    ));
            }
        }
    }
}
