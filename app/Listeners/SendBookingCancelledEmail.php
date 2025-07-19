<?php

namespace App\Listeners;

use App\Enums\BookingAttendeeStatus;
use App\Events\BookingCancelled;
use App\Mail\BookingCancelled as MailBookingCancelled;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendBookingCancelledEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingCancelled $event): void
    {
        foreach ($event->booking->attendees as $attendee) {
            $this->sendEmail($attendee, $event->booking, $event->reason);
        }
    }

    private function sendEmail(User $attendee, Booking $booking, string $reason): void
    {
        if ($attendee->isSuspended()) {
            return;
        }
        if ($attendee->attendance->status === BookingAttendeeStatus::Declined) {
            return;
        }
        if (! NotificationSettings::check($attendee, $booking, 'cancel_mail')) {
            return;
        }

        Mail::to($attendee->email)
            ->send(new MailBookingCancelled(
                $booking,
                $attendee,
                $reason
            ));
    }
}
