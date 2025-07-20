<?php

namespace App\Policies;

use App\Models\ChangeAttendee;
use App\Models\User;

class ChangeAttendeePolicy
{
    public function view(User $user, ChangeAttendee $attendee)
    {
        if ($user->isGuest() || $user->isSuspended()) {
            return false;
        }

        return $attendee->attendee->is($user);
    }
}
