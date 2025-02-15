<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether this user can manage any users.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader() || $user->isUserManager();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ! $user->isGuest();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->is($model) || $this->manage($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->manage($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($model->isTeamLeader() && ! $user->isTeamLeader()) {
            // Only team leaders can update other team leaders.
            return false;
        }

        return $user->is($model) || $this->manage($user);
    }

    /**
     * Determine whether the user can manage the model's role and accreditations.
     */
    public function accredit(User $user, User $model): bool
    {
        // Only team leaders can manage accreditations
        return $user->isTeamLeader();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($model->isTeamLeader()) {
            // Team Leaders can only be deleted by another Team Leader.
            return $user->isTeamLeader() && ! $user->is($model);
        }

        return $user->is($model) || $this->manage($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only team leaders can restore deleted users.
        return $user->isTeamLeader() && $this->manage($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only team leaders can permanently delete users.
        return $user->isTeamLeader() && $this->manage($user);
    }
}
