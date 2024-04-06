<?php

namespace App\Notifications;

use App\Models\Key;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class KeyTransferredFrom extends Notification
{
    use Queueable;

    public function __construct(
        public Key $key,
    ) {
    }

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
            ->line(Lang::get('This email is to confirm that you are no longer the holder of **:name**. It has been passed to **:user**.', ['name' => $this->key->name, 'user' => $this->key->holder->name]))
            ->line(Lang::get('If you still have this key please contact the **Team Leader** as soon as possible.'))
            ->action(Lang::get('View Key'), route('key.index'));
    }
}
