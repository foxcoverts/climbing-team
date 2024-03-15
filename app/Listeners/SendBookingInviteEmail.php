<?php

namespace App\Listeners;

use App\Events\BookingInvite;
use App\Mail\BookingInvite as MailBookingInvite;
use Illuminate\Support\Facades\Mail;

class SendBookingInviteEmail
{
    /**
     * Handle the event.
     */
    public function handle(BookingInvite $event): void
    {
        Mail::to($event->attendee->email)
            ->send(new MailBookingInvite(
                $event->booking,
                $event->attendee
            ));
    }
}
