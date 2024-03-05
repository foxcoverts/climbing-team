<?php

namespace App\Mail;

use App\iCal\Domain\Enum\CalendarMethod;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public User $attendee,
        public array $changes,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __(
                "Updated invitation: :activity @ :start",
                [
                    'activity' => $this->booking->activity,
                    'start' => localDate($this->booking->start_at)->toFormattedDayDateString(),
                ]
            ),
            tags: ['confirmed'],
            metadata: [
                'booking_id' => $this->booking->id,
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.booking.invite',
            with: [
                'title' => __('Booking Confirmed'),
                'change_summary' => $this->changeSummary(),
                'status_changed' => array_key_exists('status', $this->changes)
                    ? ' (changed)' : '',
                'when' => $this->dateString(),
                'when_changed' => array_key_exists('start_at', $this->changes) || array_key_exists('end_at', $this->changes)
                    ? ' (changed)' : '',
                'location_changed' => array_key_exists('location', $this->changes)
                    ? ' (changed)' : '',
                'activity_changed' => array_key_exists('activity', $this->changes)
                    ? ' (changed)' : '',
                'lead_instructor_changed' => array_key_exists('lead_instructor_id', $this->changes)
                    ? ' (changed)' : '',
                'group_changed' => array_key_exists('group_name', $this->changes)
                    ? ' (changed)' : '',
                'notes_changed' => array_key_exists('notes', $this->changes)
                    ? ' (changed)' : '',
                'button_label' => __('View Booking'),
                'button_url' => route('booking.show', [$this->booking]),
            ]
        );
    }

    protected function changeSummary(): string
    {
        $labels = [
            'status' => __('Status'),
            'start_at' => __('When'),
            'end_at' => __('When'),
            'location' => __('Location'),
            'activity' => __('Activity'),
            'lead_instructor_id' => __('Lead Instructor'),
            'group_name' => __('Group'),
            'notes' => __('Notes'),
        ];

        return collect($this->changes)
            ->map(function ($value, $key) use ($labels) {
                if (array_key_exists($key, $labels)) {
                    return $labels[$key];
                }
                return null;
            })
            ->filter()
            ->unique()
            ->join(', ', ' and ');
    }

    protected function dateString(): string
    {
        if (localDate($this->booking->start_at)->isSameDay(localDate($this->booking->end_at))) {
            return __(':start_date from :start_time to :end_time', [
                'start_time' => localDate($this->booking->start_at)->format('H:i'),
                'start_date' => localDate($this->booking->start_at)->toFormattedDayDateString(),
                'end_time' => localDate($this->booking->end_at)->format('H:i'),
            ]);
        } else {
            return __(':start to :end', [
                'start' => localDate($this->booking->start_at)->toDayDateTimeString(),
                'end' => localDate($this->booking->end_at)->toDayDateTimeString(),
            ]);
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [$this->ical()];
    }

    protected function ical(): Attachment
    {
        return Attachment::fromData(
            fn () => view('booking.ics', [
                'bookings' => [$this->booking],
                'user' => $this->attendee,
                'method' => CalendarMethod::Request,
            ])->render(),
            'invite.ics'
        )->withMime('text/calendar');
    }
}
