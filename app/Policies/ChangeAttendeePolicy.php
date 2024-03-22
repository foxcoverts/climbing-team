<?php

namespace App\Policies;

use App\Models\ChangeAttendee;
use App\Models\User;

class ChangeAttendeePolicy
{
    public function view(User $user, ChangeAttendee $attendee)
    {
        return !$user->isGuest() || $attendee->attendee->is($user);
    }
}
