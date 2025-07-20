<?php

namespace App\Policies;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;

class BookingAttendancePolicy
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
        return $user->can('manage', Booking::class);
    }

    /**
     * Determine whether the user can do a roll call for this booking.
     */
    public function rollcall(User $user, Booking $booking): bool
    {
        if ($booking->isCancelled() || ! $booking->isToday() || $user->isSuspended()) {
            return false;
        }

        return ($user->id == $booking->lead_instructor_id) || $user->can('manage', Booking::class);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookingAttendance $attendance): bool
    {
        return
            $user->can('contact', $attendance) ||
            $user->can('update', $attendance) ||
            $user->can('delete', $attendance);
    }

    /**
     * Determine whether the user can contact the attendee.
     */
    public function contact(User $user, BookingAttendance $attendance): bool
    {
        if ($user->isSuspended() || $attendance->user->isSuspended()) {
            // Suspended users cannot make contact, or be contacted.
            return false;
        }

        $booking = $attendance->booking;
        if ($booking->isCancelled() || $booking->isBeforeToday()) {
            // We may still need to contact attendees after the event on the same day, but
            // there should be no need for the lead instructor to contact anyone later.
            return false;
        }

        if ($user->can('manage', User::class)) {
            // User managers will have access to all contact details anyway.
            return true;
        }

        $user_attendance = $booking->attendees->where('id', $user->id)->first();
        if (! in_array($user_attendance?->attendance?->status,
            [BookingAttendeeStatus::Accepted, BookingAttendeeStatus::NeedsAction]
        )) {
            // Only people attending the event can get contact details
            return false;
        }

        if ($attendance->isLeadInstructor() || $attendance->isTeamLeader()) {
            // Attendees may contact the Lead Instructor or the Team Leader
            return true;
        }

        if ($user->id === $booking->lead_instructor_id) {
            // The lead instructors can contact attendees
            return in_array($attendance->status, [BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative]);
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookingAttendance $attendance): bool
    {
        $booking = $attendance->booking;
        if ($booking->isPast() || $booking->isCancelled()) {
            return false;
        }
        if ($attendance->user->isSuspended()) {
            // Suspended users cannot change their attendance.
            return false;
        }
        if ($attendance->isLeadInstructor() && $attendance->isAccepted()) {
            // The Lead Instructor cannot resign from a Booking.
            return false;
        }
        if ($user->can('manage', Booking::class)) {
            return true;
        }
        if ($attendance->user->is($user)) {
            if ($attendance->exists) {
                // If the user has been invited, or has already responded, let them change their response.
                return true;
            }
            if ($user->isGuest() || $user->isSuspended()) {
                // Guests cannot invite themselves to any bookings.
                return false;
            }

            // Permit holders can invite themselves to bookings which have not yet been confirmed,
            // Any team member or team leader can invite themselves to confirmed bookings.
            return $booking->isConfirmed() || $user->isPermitHolder();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookingAttendance $attendance): bool
    {
        if ($attendance->isLeadInstructor() && $attendance->isAccepted()) {
            // The Lead Instructor cannot resign from a Booking.
            return false;
        }

        return $user->can('manage', Booking::class);
    }
}
