<?php

namespace App\Listeners;

use App\Events\BookingInvite;
use App\Mail\BookingInvite as MailBookingInvite;
use App\Models\NotificationSettings;
use Illuminate\Support\Facades\Mail;

class SendBookingInviteEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingInvite $event): void
    {
        if ($event->attendee->isSuspended()) {
            return;
        }
        if ($event->booking->isCancelled() || $event->booking->isPast()) {
            return;
        }
        if (! NotificationSettings::check($event->attendee, $event->booking, 'invite_mail')) {
            return;
        }

        Mail::to($event->attendee->email)
            ->send(new MailBookingInvite(
                $event->booking,
                $event->attendee
            ));
    }
}
