<?php

namespace App\Notifications;

use App\Mail\BookingConfirmed as MailBookingConfirmed;
use Illuminate\Bus\Queueable;

class BookingConfirmed extends BookingInvite
{
    use Queueable;

    protected static string $notification_setting = 'confirm_mail';

    protected static string $mailable = MailBookingConfirmed::class;
}
