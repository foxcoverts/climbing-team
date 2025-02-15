<?php

namespace App\Actions;

use App\Enums\BookingAttendeeResponse;
use App\Models\Booking;
use App\Models\User;
use InvalidArgumentException;

class RespondToBookingAction
{
    public function __construct(
        public Booking $booking,
        public ?User $user = null
    ) {
        if ($booking->isPast()) {
            throw new InvalidArgumentException('You cannot change attendance on past bookings.');
        }
        if ($booking->isCancelled()) {
            throw new InvalidArgumentException('You cannot change attendance on cancelled bookings.');
        }
    }

    public function __invoke(
        User $attendee,
        BookingAttendeeResponse|string $response,
        ?string $comment = null
    ): void {
        // Prepare data
        $attendee_id = $attendee->id;
        $response = is_string($response) ? BookingAttendeeResponse::tryFrom($response) : $response;
        $status = $response->toStatus();
        $author = $this->user ?? $attendee;

        // Validate
        $attendance = $this->booking->attendees()->find($attendee_id)?->attendance;
        if (($attendance?->status == $status) && ($attendance?->comment == $comment)) {
            return;
        }

        // Perform action
        $this->booking->attendees()->syncWithoutDetaching([
            $attendee_id => [
                'status' => $status->value,
                'comment' => $comment ?? $attendance?->comment,
            ],
        ]);

        // Log Activity
        $properties = [];
        data_set($properties, 'attributes.attendance.attendee_id', $attendee_id);
        data_set($properties, 'attributes.attendance.status', $status);
        data_set($properties, 'attributes.attendance.comment', $comment);
        data_set($properties, 'old.attendance.status', $attendance?->status);
        data_set($properties, 'old.attendance.comment', $attendance?->comment);

        activity()
            ->on($this->booking)
            ->by($author)
            ->withProperties($properties)
            ->event('responded')
            ->log('responded');
    }
}
