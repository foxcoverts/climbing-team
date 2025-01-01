<?php

namespace App\Notifications;

use App\Filament\Resources\KeyResource;
use App\Models\Key;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class KeyTransferredTo extends Notification
{
    use Queueable;

    public function __construct(
        public Key $key,
        public User $from,
    ) {}

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
        return $this->buildMailMessage();
    }

    /**
     * Get the new user notification mail message for the given URL.
     *
     * @param  string  $url
     */
    protected function buildMailMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Key Transferred'))
            ->line(Lang::get('This email is to confirm that you are now the holder of **:name**. It has been passed to you from **:user**.', ['name' => $this->key->name, 'user' => $this->from->name]))
            ->line(Lang::get('Please look after the key. If you pass it on to someone else please update the website or let the **Team Leader** know as soon as possible.'))
            ->action(Lang::get('View Key'), KeyResource::getUrl());
    }
}
