<?php

namespace App\Policies;

use App\Models\Qualification;
use App\Models\User;

class QualificationPolicy
{
    /**
     * Determine whether the user can manage any qualifications.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader() || $user->isQualificationManager();
    }

    /**
     * Determine whether the user can view any qualifications.
     */
    public function viewAny(User $user, User $model): bool
    {
        return $user->can('view', $user) && (
            $user->can('manage', Qualification::class) || ($user->id == $model->id)
        );
    }

    /**
     * Determine whether the user can view the qualification.
     */
    public function view(User $user, Qualification $qualification): bool
    {
        return $user->can('view', $user) && (
            $user->can('manage', Qualification::class) || ($user->id == $qualification->user_id)
        );
    }

    /**
     * Determine whether the user can create qualifications.
     */
    public function create(User $user): bool
    {
        return $user->can('view', $user) && $user->can('manage', Qualification::class);
    }

    /**
     * Determine whether the user can update the qualification.
     */
    public function update(User $user, Qualification $qualification): bool
    {
        return $user->can('view', $user) && $user->can('manage', Qualification::class);
    }

    /**
     * Determine whether the user can delete the qualification.
     */
    public function delete(User $user, Qualification $qualification): bool
    {
        return $user->can('view', $user) && $user->can('manage', Qualification::class);
    }
}
