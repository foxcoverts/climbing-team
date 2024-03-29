<?php

namespace App\Policies;

use App\Enums\BookingStatus;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Shortcut to check if the user should be able to manage any booking.
     *
     * @param User $user
     * @return boolean
     */
    public function manage(User $user): bool
    {
        return $user->isTeamLeader() || $user->isBookingManager();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, ?BookingStatus $status = null): bool
    {
        switch ($status) {
            case BookingStatus::Confirmed:
                return $this->manage($user) || !$user->isGuest();

            case BookingStatus::Tentative:
            case BookingStatus::Cancelled:
                return $this->manage($user);

            default:
                return $this->manage($user) || !$user->isGuest();
        }
    }

    /**
     * Determine whether the user can view the Bookings they have been invited to.
     */
    public function viewOwn(User $user): bool
    {
        return $user->exists;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        if ($booking->trashed()) {
            return $this->manage($user);
        } else if ($booking->attendees->find($user)) {
            return true;
        } else {
            return $this->viewAny($user, $booking->status);
        }
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
    public function update(User $user, Booking $booking): bool
    {
        return $this->manage($user);
    }

    /**
     * Determine whether the user can update attendance on the booking.
     */
    public function respond(User $user, Booking $booking, User $model): bool
    {
        if ($attendee = $booking->attendees->find($model)) {
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
        if ($booking->isPast() || $booking->isCancelled() || $user->isGuest()) {
            return false;
        }
        if ($this->manage($user)) {
            return true;
        }
        if ($booking->attendees()->find($user)) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $this->manage($user);
    }

    /**
     * Determine whether the user can view trashed models.
     */
    public function viewTrashed(User $user): bool
    {
        return $this->manage($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return $this->manage($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return $user->isTeamLeader() &&
            $this->manage($user);
    }
}
