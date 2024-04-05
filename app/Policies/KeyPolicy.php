<?php

namespace App\Policies;

use App\Models\Key;
use App\Models\User;

class KeyPolicy
{
    /**
     * Determine whether the user can manage keys.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader();
    }

    /**
     * Determine whether the user can view any keys.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage', Key::class);
    }

    /**
     * Determine whether the user can view the key.
     */
    public function view(User $user, Key $key): bool
    {
        return $user->can('manage', $key);
    }

    /**
     * Determine whether the user can create keys.
     */
    public function create(User $user): bool
    {
        return $user->can('manage', Key::class);
    }

    /**
     * Determine whether the user can update the key.
     */
    public function update(User $user, Key $key): bool
    {
        return $user->can('manage', $key);
    }

    /**
     * Determine whether the user can delete the key.
     */
    public function delete(User $user, Key $key): bool
    {
        return $user->can('manage', $key);
    }
}
