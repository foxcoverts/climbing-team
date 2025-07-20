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
        if ($user->isSuspended()) {
            return false;
        }

        return $user->isTeamLeader() || $user->isQualificationManager();
    }

    /**
     * Determine whether the user can view any qualifications.
     */
    public function viewAny(User $user, ?User $model = null): bool
    {
        if (is_null($model)) {
            return $user->can('manage', Qualification::class);
        } elseif ($user->isSuspended()) {
            return false;
        }

        return ($user->id == $model->id) ||
            (isset($model->attendance) && $user->can('view', $model->attendance)) ||
            $user->can('manage', Qualification::class) ||
            $user->can('view', $model);
    }

    /**
     * Determine whether the user can view their own qualifications.
     */
    public function viewOwn(User $user): bool
    {
        return $this->viewAny($user, $user);
    }

    /**
     * Determine whether the user can view the qualification.
     */
    public function view(User $user, Qualification $qualification): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return ($user->id == $qualification->user_id) ||
            $user->can('manage', Qualification::class) ||
            $user->can('view', $qualification->user);
    }

    /**
     * Determine whether the user can create qualifications.
     */
    public function create(User $user, ?User $model = null): bool
    {
        if (is_null($model)) {
            return $user->can('manage', Qualification::class);
        } elseif ($user->isSuspended() || $model->isSuspended()) {
            return false;
        }

        return $user->can('view', $model) && $user->can('manage', Qualification::class);
    }

    /**
     * Determine whether the user can update the qualification.
     */
    public function update(User $user, Qualification $qualification): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('view', $qualification->user) && $user->can('manage', Qualification::class);
    }

    /**
     * Determine whether the user can delete the qualification.
     */
    public function delete(User $user, Qualification $qualification): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->can('view', $qualification->user) && $user->can('manage', Qualification::class);
    }
}
