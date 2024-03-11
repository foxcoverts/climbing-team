<?php

namespace App\Policies;

use App\Enums\Accreditation;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return (!$user->isGuest()) ||
            $user->isBookingManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        if ($booking->trashed()) {
            return $user->isBookingManager();
        } else if (!$user->isGuest()) {
            return true;
        } else if ($booking->attendees()->find($user)) {
            return true;
        } else {
            return $user->isBookingManager();
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can update attendance on the booking.
     */
    public function respond(User $user, Booking $booking, User $model): bool
    {
        if ($attendee = $booking->attendees()->find($model)) {
            $attendance = $attendee->attendance;
        } else {
            $attendance = Attendance::build($booking, $model);
        }

        return $user->can('update', $attendance);
    }

    /**
     * Determine whether the user can comment on the booking.
     */
    public function comment(User $user, Booking $booking): bool
    {
        return $user->can('view', $booking);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can view trashed models.
     */
    public function viewTrashed(User $user): bool
    {
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return $user->isBookingManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return ($user->isTeamLeader()) &&
            $user->isBookingManager();
    }
}
