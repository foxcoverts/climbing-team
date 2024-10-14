<?php

namespace App\Listeners;

use App\Events\TodoChanged;
use App\Models\Change;
use App\Models\ChangeField;

class RecordTodoChanges
{
    /**
     * Handle the event.
     */
    public function handle(TodoChanged $event): void
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
                'value' => (string) $value,
            ]);
        }

        if (count($change_fields)) {
            $change = new Change;
            $change->author()->associate($event->author);
            $event->todo->changes()->save($change);
            $change->fields()->saveMany($change_fields);
        }
    }
}
