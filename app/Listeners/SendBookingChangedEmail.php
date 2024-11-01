<?php

namespace App\Listeners;

use App\Enums\BookingAttendeeStatus;
use App\Events\BookingChanged;
use App\Mail\BookingChanged as MailBookingChanged;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendBookingChangedEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingChanged $event): void
    {
        foreach ($event->booking->attendees as $attendee) {
            $this->sendMail($attendee, $event->booking, $event->changes);
        }
    }

    private function sendMail(User $attendee, Booking $booking, array $changes = []): void
    {
        if ($attendee->attendance->status === BookingAttendeeStatus::Declined) {
            return;
        }
        if (! NotificationSettings::check($attendee, $booking, 'change_mail')) {
            return;
        }

        Mail::to($attendee->email)
            ->send(new MailBookingChanged(
                $booking,
                $attendee,
                $changes,
            ));
    }
}
