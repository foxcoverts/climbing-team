<?php

namespace App\Actions;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\Change;
use App\Models\ChangeAttendee;
use App\Models\ChangeComment;
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
        User|string $attendee,
        AttendeeStatus|string $status,
        ?string $comment = null
    ): ?Change {
        // Prepare data
        $attendee_id = is_string($attendee) ? $attendee : $attendee->id;
        $status = is_string($status) ? AttendeeStatus::tryFrom($status) : $status;
        $author = $this->user ?? $attendee;
        $author_id = is_string($author) ? $author : $author->id;

        // Validate
        $attendance = $this->booking->attendees()->find($attendee_id)?->attendance;
        if (($attendance?->status == $status) && ($attendance?->comment == $comment)) {
            return null;
        }

        // Perform action
        $this->booking->attendees()->syncWithoutDetaching([
            $attendee_id => [
                'status' => $status->value,
                'comment' => $comment ?? $attendance?->comment,
            ],
        ]);

        // Record change
        if ($attendance?->status != $status) {
            $change = new Change;
            $change->author_id = $author_id;
            $this->booking->changes()->save($change);

            $change_attendee = new ChangeAttendee;
            $change_attendee->attendee_id = $attendee_id;
            $change_attendee->attendee_status = $status;
            if (!empty($comment) && ($attendance?->comment != $comment)) {
                $change_attendee->attendee_comment = $comment;
            }
            $change->attendees()->save($change_attendee);
        } else if (!empty($comment) && ($attendance?->comment != $comment)) {
            $change = new Change;
            $change->author_id = $author_id;
            $this->booking->changes()->save($change);

            $change_comment = new ChangeComment;
            $change_comment->body = $comment;
            $change->comments()->save($change_comment);
        }

        return $change;
    }
}
