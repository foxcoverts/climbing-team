<?php

namespace App\Listeners;

use App\Events\BookingCancelled;

class RecordBookingCancelled
{
    public function handle(BookingCancelled $event): void
    {
        $listener = new RecordBookingChanges;
        $listener->handle($event);
    }
}
