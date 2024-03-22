<?php

namespace App\Listeners;

use App\Events\BookingRestored;

class RecordBookingRestored
{
    public function handle(BookingRestored $event): void
    {
        $listener = new RecordBookingChanges;
        $listener->handle($event);
    }
}
