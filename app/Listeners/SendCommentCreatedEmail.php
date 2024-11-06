<?php

namespace App\Listeners;

use App\Enums\BookingAttendeeStatus;
use App\Enums\CommentNotificationOption;
use App\Events\CommentCreated;
use App\Mail\BookingComment as MailBookingComment;
use App\Models\Booking;
use App\Models\ChangeComment;
use App\Models\NotificationSettings;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class SendCommentCreatedEmail
{
    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        switch (get_class($event->parent)) {
            case Booking::class:
                $this->handleBooking($event);
                break;

            case Todo::class:
                // TODO
                break;
        }
    }

    protected function handleBooking(CommentCreated $event): void
    {
        $booking = $event->parent;
        foreach ($booking->attendees as $attendee) {
            $this->sendBookingMail($attendee, $booking, $event->comment);
        }
    }

    protected function sendBookingMail(User $attendee, Booking $booking, ChangeComment $comment): void
    {
        if (! Gate::forUser($attendee)->check('view', $comment)) {
            return;
        }
        if ($attendee->attendance->status === BookingAttendeeStatus::Declined) {
            return;
        }
        if ($attendee->id === $comment->author->id) {
            return;
        }
        switch (NotificationSettings::check($attendee, $booking, 'comment_mail')) {
            case CommentNotificationOption::None:
                return;

            case CommentNotificationOption::Leader:
                if ($comment->author->id === $booking->lead_instructor_id) {
                    break;
                }
                if ($comment->author->isTeamLeader()) {
                    break;
                }

                return;

            case CommentNotificationOption::Reply:
            case CommentNotificationOption::All:
                break;
        }

        Mail::to($attendee->email)
            ->send(new MailBookingComment(
                $comment,
                $booking,
                $attendee,
            ));
    }
}
