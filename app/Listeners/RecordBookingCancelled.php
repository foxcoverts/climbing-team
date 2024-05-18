<?php

namespace App\Listeners;

use App\Enums\BookingStatus;
use App\Events\BookingCancelled;
use App\Models\Change;
use App\Models\ChangeComment;
use App\Models\ChangeField;

class RecordBookingCancelled
{
    public function handle(BookingCancelled $event): void
    {
        $change = new Change;
        $change->author()->associate($event->author);
        $event->booking->changes()->save($change);
        $change->fields()->save(new ChangeField([
            'name' => 'status',
            'value' => BookingStatus::Cancelled->value,
        ]));
        $change->comments()->save(new ChangeComment([
            'body' => $event->reason,
        ]));
    }
}
