<?php

namespace App\Notifications;

use App\Mail\BookingChanged as MailBookingChanged;
use Illuminate\Bus\Queueable;

class BookingChanged extends BookingInvite
{
    use Queueable;

    protected static string $notification_setting = 'change_mail';

    protected static string $mailable = MailBookingChanged::class;
}
