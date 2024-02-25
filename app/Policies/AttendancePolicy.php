<?php

namespace App\Policies;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Booking $booking): bool
    {
        return $user->can('view', $booking);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Booking $booking): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        return ($attendance->user->is($user)) ||
            ($user->accreditations->contains(Accreditation::ManageBookings));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        if ($user->accreditations->contains(Accreditation::ManageBookings)) {
            return true;
        }
        if ($attendance->user->is($user)) {
            return ($attendance->exists) || ($user->role != Role::Guest);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }
}
