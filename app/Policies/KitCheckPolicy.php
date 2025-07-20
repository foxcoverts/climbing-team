<?php

namespace App\Policies;

use App\Models\KitCheck;
use App\Models\User;

class KitCheckPolicy
{
    /**
     * Determine whether the user can manage kit checks.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('manage', KitCheck::class)
            || $user->can('manage', User::class)
            || $user->isKitChecker();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KitCheck $kitCheck): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->id === $kitCheck->user_id
            || $user->can('manage', KitCheck::class)
            || $user->can('manage', User::class)
            || $user->isKitChecker();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('manage', KitCheck::class)
            || $user->isKitChecker();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KitCheck $kitCheck): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('manage', KitCheck::class)
            || ($user->isKitChecker() && $user->id == $kitCheck->checked_by_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KitCheck $kitCheck): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('manage', KitCheck::class)
            || ($user->isKitChecker() && $user->id == $kitCheck->checked_by_id);
    }
}
