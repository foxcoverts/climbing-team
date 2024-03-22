<?php

namespace App\Listeners;

use App\Events\BookingChanged;
use App\Models\Change;
use App\Models\ChangeField;

class RecordBookingChanges
{
    /**
     * Handle the event.
     */
    public function handle(BookingChanged $event): void
    {
        $change_fields = [];
        foreach ($event->changes as $name => $value) {
            if (in_array($name, [
                'created_at', 'updated_at', 'sequence',
            ])) {
                continue;
            }

            $change_fields[] = new ChangeField([
                'name' => $name,
                'value' => (string)$value,
            ]);
        }

        if (count($change_fields)) {
            $change = new Change;
            $change->author()->associate($event->author);
            $event->booking->changes()->save($change);
            $change->fields()->saveMany($change_fields);
        }
    }
}
