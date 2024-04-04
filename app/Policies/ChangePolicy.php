<?php

namespace App\Policies;

use App\Models\User;

class ChangePolicy
{
    /**
     * Determine whether the user can view any changes.
     */
    public function viewAny(User $user)
    {
        return $user->isTeamLeader();
    }
}
