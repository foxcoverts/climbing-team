<?php

namespace App\Notifications;

use App\Enums\BookingAttendeeStatus;
use App\Mail\BookingInvite as MailBookingInvite;
use App\Models\Booking;
use App\Models\NotificationSettings;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;

class BookingInvite extends Notification
{
    use Queueable;

    protected static string $notification_setting = 'invite_mail';

    protected static string $mailable = MailBookingInvite::class;

    public function __construct(
        public Booking $booking,
        public array $changes = [],
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        if ($notifiable instanceof MustVerifyEmail && ! $notifiable->hasVerifiedEmail()) {
            return [];
        }
        if ($this->isAttendanceDeclined($notifiable)) {
            return [];
        }
        if (! NotificationSettings::check($notifiable, $this->booking, static::$notification_setting)) {
            return [];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): Mailable
    {
        return (new (static::$mailable)($this->booking, $notifiable, $this->changes))
            ->to($notifiable->email, $notifiable->name);
    }

    protected function isAttendanceDeclined(User $notifiable): bool
    {
        if (! $notifiable->hasAttribute('attendance')) {
            $notifiable = $this->booking->attendees()->find($notifiable->id);
        }
        if (! $notifiable) {
            return true;
        }
        if ($notifiable->attendance->status === BookingAttendeeStatus::Declined) {
            return true;
        }

        return false;
    }
}
