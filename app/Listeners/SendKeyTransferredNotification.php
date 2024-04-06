<?php

namespace App\Listeners;

use App\Events\KeyTransferred;
use App\Notifications\KeyTransferredFrom;
use App\Notifications\KeyTransferredTo;

class SendKeyTransferredNotification
{
    /**
     * Handle the event.
     */
    public function handle(KeyTransferred $event): void
    {
        $event->key->holder->notify(
            new KeyTransferredTo(
                $event->key,
                $event->from,
            )
        );
        $event->from->notify(
            new KeyTransferredFrom(
                $event->key,
            )
        );
    }
}
