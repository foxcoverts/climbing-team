<?php

namespace App\Policies;

use App\Models\NotificationSettings;
use App\Models\User;

class NotificationSettingsPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NotificationSettings $settings): bool
    {
        return $settings->user->is($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NotificationSettings $settings): bool
    {
        return $settings->user->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NotificationSettings $settings): bool
    {
        return $settings->user->is($user);
    }
}
