<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;

class RecordBookingConfirmed
{
    public function handle(BookingConfirmed $event): void
    {
        $listener = new RecordBookingChanges;
        $listener->handle($event);
    }
}
