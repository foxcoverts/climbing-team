<?php

namespace App\Notifications;

use App\Mail\BookingInvite as MailBookingInvite;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;

class BookingInvite extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        if (! NotificationSettings::check($notifiable, $this->booking, 'invite_mail')) {
            return [];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): Mailable
    {
        return (new MailBookingInvite($this->booking, $notifiable))
            ->to($notifiable->email, $notifiable->name);
    }
}
