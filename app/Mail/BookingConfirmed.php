<?php

namespace App\Mail;

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
        public User $attendee
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __(
                "Confirmed: :activity @ :start",
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
            markdown: 'mail.booking.confirmed',
            with: [
                'date' => $this->dateString(),
                'url' => route('booking.show', [$this->booking]),
            ]
        );
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
            ])->render(),
            'invite.ics'
        )->withMime('text/calendar');
    }
}
