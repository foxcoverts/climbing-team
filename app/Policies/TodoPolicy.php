<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;

class TodoPolicy
{
    /**
     * Determine whether the user can manage tasks.
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader();
    }

    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage', Todo::class);
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Todo $todo): bool
    {
        return $user->can('manage', $todo);
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->can('manage', Todo::class);
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Todo $todo): bool
    {
        return $user->can('manage', $todo);
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Todo $todo): bool
    {
        return $user->can('manage', $todo);
    }

    /**
     * Determine whether the user can restore the task.
     */
    public function restore(User $user, Todo $todo): bool
    {
        return $user->can('manage', $todo);
    }

    /**
     * Determine whether the user can permanently delete the task.
     */
    public function forceDelete(User $user, Todo $todo): bool
    {
        return $user->can('manage', $todo);
    }
}
