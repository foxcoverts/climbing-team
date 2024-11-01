<?php

namespace App\Listeners;

use App\Enums\BookingAttendeeStatus;
use App\Events\BookingConfirmed;
use App\Mail\BookingConfirmed as MailBookingConfirmed;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmedEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void
    {
        foreach ($event->booking->attendees as $attendee) {
            $this->sendMail($attendee, $event->booking, $event->changes);
        }
    }

    private function sendMail(User $attendee, Booking $booking, array $changes = []): void
    {
        if (! in_array($attendee->attendance->status, [BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative])) {
            return;
        }
        if (! NotificationSettings::check($attendee, $booking, 'confirm_mail')) {
            return;
        }

        Mail::to($attendee->email)
            ->send(new MailBookingConfirmed(
                $booking,
                $attendee,
                $changes
            ));
    }
}
