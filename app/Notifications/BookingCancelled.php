<?php

namespace App\Notifications;

use App\Mail\BookingCancelled as MailBookingCancelled;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable;

class BookingCancelled extends BookingInvite
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $reason = '',
    ) {
        parent::__construct($booking);
    }

    protected static string $notification_setting = 'cancel_mail';

    protected static string $mailable = MailBookingCancelled::class;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): Mailable
    {
        return (new MailBookingCancelled($this->booking, $notifiable, $this->reason))
            ->to($notifiable->email, $notifiable->name);
    }
}
