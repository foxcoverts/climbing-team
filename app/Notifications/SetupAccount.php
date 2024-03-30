<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class SetupAccount extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        return $this->buildMailMessage($this->accountSetupUrl($notifiable));
    }

    /**
     * Get the new user notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Account Setup Wizard'))
            ->line(Lang::get('An account has been created for you on the :app_name website. This site is designed to make managing the bookings and activities for the team as easy as possible.', ['app_name' => config('app.name')]))
            ->action(Lang::get('Setup Account'), $url)
            ->line(Lang::get('You will not receive any further notifications from the site until you complete the account setup.'));
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  User  $notifiable
     * @return string
     */
    protected function accountSetupUrl(User $notifiable)
    {
        return URL::signedRoute('setup-account', $notifiable);
    }
}
