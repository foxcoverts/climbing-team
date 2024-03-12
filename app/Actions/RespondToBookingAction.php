<?php

namespace App\Actions;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\Change;
use App\Models\User;

class RespondToBookingAction
{

    public function __construct(
        public Booking $booking,
        public ?User $user = null
    ) {
    }

    public function __invoke(
        User|string $attendee,
        AttendeeStatus|string $status,
        ?string $comment = null
    ): Change {
        // Prepare data
        $attendee_id = is_string($attendee) ? $attendee : $attendee->id;
        $status = is_string($status) ? AttendeeStatus::tryFrom($status) : $status;
        $author = $this->user ?? $attendee;
        $author_id = is_string($author) ? $author : $author->id;

        // Perform action
        $this->booking->attendees()->syncWithoutDetaching([
            $attendee_id => ['status' => $status->value],
        ]);

        // Record change
        $change = new Change();
        $change->author_id = $author_id;
        $this->booking->changes()->save($change);

        $change_attendee = new Change\Attendee;
        $change_attendee->attendee_id = $attendee_id;
        $change_attendee->attendee_status = $status;
        $change->attendees()->save($change_attendee);

        if (!is_null($comment)) {
            $change_comment = new Change\Comment;
            $change_comment->body = $comment;
            $change->comments()->save($change_comment);
        }

        return $change;
    }
}
