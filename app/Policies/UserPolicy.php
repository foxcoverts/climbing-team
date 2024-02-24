<?php

namespace App\Policies;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return ($user->is($model)) ||
            $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if (
            $model->role == Role::TeamLeader &&
            $user->role != Role::TeamLeader
        ) {
            // Only team leaders can update other team leaders.
            return false;
        }
        return ($user->is($model)) ||
            $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can manage the model's role and accreditations.
     *
     * @param User $user
     * @param User $model
     * @return boolean
     */
    public function manage(User $user, User $model): bool
    {
        // Only team leaders can manage roles and accreditations
        return ($user->role == Role::TeamLeader) &&
            $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if (
            $model->role == Role::TeamLeader &&
            $user->role != Role::TeamLeader
        ) {
            // Only team leaders can delete other team leaders.
            return false;
        }

        return ($user->is($model)) ||
            $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only team leaders can restore deleted users.
        return $user->role == Role::TeamLeader &&
            $user->accreditations->contains(Accreditation::ManageUsers);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only team leaders can permanently delete users.
        return $user->role == Role::TeamLeader &&
            $user->accreditations->contains(Accreditation::ManageUsers);
    }
}
