<?php

namespace App\Policies;

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
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        return ($user->is($attendance->user)) ||
            ($user->is($attendance->booking->lead_instructor)) ||
            ($user->isBookingManager());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        if (
            $attendance->user->is($attendance->booking->lead_instructor) &&
            ($attendance->isAccepted())
        ) {
            // The Lead Instructor cannot resign from a Booking.
            return false;
        } else if ($user->isBookingManager()) {
            return true;
        } else if ($attendance->user->is($user)) {
            if ($attendance->exists) {
                // If the user has been invited, or has already responded, let them change their response.
                return true;
            } else if ($user->isGuest()) {
                // Guests cannot invite themselves to any bookings.
                return false;
            } else {
                // Permit holders can invite themselves to bookings which have not yet been confirmed,
                // Any team member or team leader can invite themselves to confirmed bookings.
                return ($attendance->booking->isConfirmed()) || ($user->isPermitHolder());
            }
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        if (
            $attendance->user->is($attendance->booking->lead_instructor) &&
            ($attendance->isAccepted())
        ) {
            // The Lead Instructor cannot resign from a Booking.
            return false;
        }
        return $user->isBookingManager();
    }
}
