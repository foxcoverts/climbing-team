<?php

namespace App\Listeners;

use App\Enums\AttendeeStatus;
use App\Events\BookingCancelled;
use App\Mail\BookingCancelled as MailBookingCancelled;
use Illuminate\Support\Facades\Mail;

class SendBookingCancelledEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingCancelled $event): void
    {
        foreach ($event->booking->attendees as $attendee) {
            if ($attendee->attendance->status !== AttendeeStatus::Declined) {
                Mail::to($attendee->email)
                    ->send(new MailBookingCancelled(
                        $event->booking,
                        $attendee,
                        $event->reason
                    ));
            }
        }
    }
}
