<?php

namespace App\Mail;

use App\iCal\Domain\Enum\CalendarMethod;
use App\Models\Booking;
use App\Models\ChangeComment;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class BookingComment extends BookingInvite
{
    public function __construct(
        public ChangeComment $comment,
        public Booking $booking,
        public User $attendee,
    ) {
        parent::__construct($booking, $attendee);
    }

    public function getSubject(): string
    {
        return 'Re: :activity @ :start';
    }

    public function getTemplate(): string
    {
        return 'mail.booking.comment';
    }

    public function getTitle(): string
    {
        return 'New Comment';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->getTemplate(),
            with: array_merge([
                'author' => $this->comment->author->name,
                'author_status' => $this->getAuthorStatus(),
                'body' => $this->comment->body,
                'title' => __($this->getTitle()),
                'when' => $this->buildDateString(),
                'booking_url' => $this->getBookingUrl(),
                'comment_url' => $this->getCommentUrl(),
            ], $this->buildChangedList())
        );
    }

    public function getAuthorStatus(): string
    {
        if ($this->comment->author->isTeamLeader()) {
            return ' ('.__('Team Leader').')';
        }
        if ($this->comment->author->id === $this->booking->lead_instructor_id) {
            return ' ('.__('Lead Instructor').')';
        }

        return '';
    }

    public function getCommentUrl(): string
    {
        return route('booking.show', $this->booking).'#'.$this->comment->change_id;
    }

    public function getTags(): array
    {
        return ['comment'];
    }

    public function getCalendarMethod(): CalendarMethod
    {
        return CalendarMethod::Publish;
    }
}
