<?php

namespace App\Policies;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->role != Role::Guest) ||
            $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        if ($booking->trashed()) {
            return $user->accreditations->contains(Accreditation::ManageBookings);
        } else {
            return ($user->role != Role::Guest) ||
                $user->accreditations->contains(Accreditation::ManageBookings);
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can view trashed models.
     */
    public function viewTrashed(User $user): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return $user->accreditations->contains(Accreditation::ManageBookings);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return ($user->role == Role::TeamLeader) &&
            $user->accreditations->contains(Accreditation::ManageBookings);
    }
}
