<?php

namespace App\Listeners;

use App\Events\BookingInvite;
use App\Mail\BookingInvite as MailBookingInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingInviteEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

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
