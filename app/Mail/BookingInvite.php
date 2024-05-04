<?php

namespace App\Mail;

use App\Enums\AttendeeStatus;
use App\iCal\Domain\Enum\CalendarMethod;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\User;
use Carbon\Factory as CarbonFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class BookingInvite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Attendance $attendance;

    protected CarbonFactory $dateFactory;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Booking $booking,
        public User $attendee,
        public array $changes = [],
    ) {
        $booking->load('lead_instructor');
        $this->attendance = $booking->attendees()->find($attendee)->attendance;
        $this->dateFactory = new CarbonFactory([
            'locale' => config('app.locale', 'en_GB'),
            'timezone' => $this->attendee->timezone,
        ]);
    }

    /**
     * The subject line for the email.
     *
     * Will be translated with `:activity` and `:start` passed in.
     */
    public function getSubject(): string
    {
        return 'Invitation: :activity @ :start';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __(
                $this->getSubject(),
                [
                    'activity' => $this->booking->activity,
                    'start' => $this->dateFactory
                        ->make($this->booking->start_at)
                        ->toFormattedDayDateString(),
                ]
            ),
            replyTo: [
                new Address($this->booking->uid),
            ],
            tags: ['invite'],
            metadata: [
                'booking_id' => $this->booking->id,
            ],
            using: [
                fn (Email $message) => $this->attachCalendarData($message),
            ],
        );
    }

    /**
     * The title line in the body of the email
     */
    public function getTitle(): string
    {
        return 'Invitation';
    }

    /**
     * The URL for the booking.
     */
    public function getBookingUrl(): string
    {
        return route('booking.show', $this->booking);
    }

    /**
     * The URL for the call to action buttons.
     */
    public function getRespondUrl(?string $action = null): string
    {
        $route = (! $action) ? 'respond' : "respond.$action";

        return URL::route($route, [
            $this->booking, $this->attendee,
            'invite' => $this->attendance->token,
            'sequence' => $this->booking->sequence,
        ]);
    }

    /**
     * The email template to use.
     */
    public function getTemplate(): string
    {
        return $this->isResponseNeededFromAttendee()
            ? 'mail.booking.invite'
            : 'mail.booking.update';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->getTemplate(),
            with: array_merge([
                'title' => __($this->getTitle()),
                'changed_summary' => $this->buildChangedSummary(),
                'when' => $this->buildDateString(),
                'booking_url' => $this->getBookingUrl(),
                'accept_url' => $this->getRespondUrl('accept'),
                'decline_url' => $this->getRespondUrl('decline'),
                'tentative_url' => $this->getRespondUrl('tentative'),
                'respond_url' => $this->getRespondUrl(),
            ], $this->buildChangedList())
        );
    }

    protected function buildChangedList(): array
    {
        $label = ' ('.__('changed').')';

        $fields = [
            'status',
            'start_at' => 'when',
            'end_at' => 'when',
            'location',
            'activity',
            'lead_instructor_id' => 'lead_instructor',
            'group_name' => 'group',
            'notes',
        ];

        $changed_list = [];

        foreach ($fields as $value) {
            $changed_key = $value.'_changed';
            $changed_list[$changed_key] = '';
        }

        foreach ($fields as $key => $value) {
            $changed_key = $value.'_changed';
            if (array_key_exists($key, $this->changes) || array_key_exists($value, $this->changes)) {
                $changed_list[$changed_key] = $label;
            }
        }

        return $changed_list;
    }

    protected function buildChangedSummary(): string
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

    protected function buildDateString(): string
    {
        $localStartAt = $this->dateFactory->make($this->booking->start_at);
        $localEndAt = $this->dateFactory->make($this->booking->end_at);

        if ($localStartAt->isSameDay($localEndAt)) {
            return __(':start_date from :start_time to :end_time', [
                'start_time' => $localStartAt->format('H:i'),
                'start_date' => $localStartAt->toFormattedDayDateString(),
                'end_time' => $localEndAt->format('H:i'),
            ]);
        } else {
            return __(':start to :end', [
                'start' => $localStartAt->toDayDateTimeString(),
                'end' => $localEndAt->toDayDateTimeString(),
            ]);
        }
    }

    /**
     * The method for the calendar attachment.
     */
    public function getCalendarMethod(): CalendarMethod
    {
        return CalendarMethod::Request;
    }

    public function isResponseNeededFromAttendee(): bool
    {
        return match ($this->attendance->status) {
            AttendeeStatus::NeedsAction,
            AttendeeStatus::Tentative => true,
            AttendeeStatus::Declined => false,
            AttendeeStatus::Accepted => $this->hasImportantChanges()
        };
    }

    /**
     * Determine whether details of the booking have changed that could affect
     * someone's attendance, for example the date, time, duration or location.
     */
    public function hasImportantChanges(): bool
    {
        $important_keys = [
            'start_at',
            'end_at',
            'location',
        ];
        foreach ($important_keys as $key) {
            if (array_key_exists($key, $this->changes)) {
                return true;
            }
        }

        return false;
    }

    protected function getCalendarMethodAsString(): string
    {
        return match ($this->getCalendarMethod()) {
            CalendarMethod::Add => 'ADD',
            CalendarMethod::Cancel => 'CANCEL',
            CalendarMethod::Counter => 'COUNTER',
            CalendarMethod::DeclineCounter => 'DECLINECOUNTER',
            CalendarMethod::Publish => 'PUBLISH',
            CalendarMethod::Refresh => 'REFRESH',
            CalendarMethod::Reply => 'REPLY',
            CalendarMethod::Request => 'REQUEST',
        };
    }

    protected function attachCalendarData(Email $message): void
    {
        // We need to include the calendar data in two different ways to
        //  satisfy different email & calendar clients. Some expect the data
        //  as an inline 'alternative' part of the email, and others expect it
        //  as an 'attachment'.
        $icsData = $this->buildCalendarData();
        $icsMethod = $this->getCalendarMethodAsString();

        // Ideally the inline data would be an 'alternative' part, before the
        //  HTML, but that requires manually handling all the email parts.
        $icsInline = new DataPart($icsData, filename: 'invite', contentType: 'text/calendar');
        $icsInline->asInline();
        $icsInline->getHeaders()->addParameterizedHeader(
            'Content-Type',
            'text/calendar',
            [
                'method' => $icsMethod,
                'charset' => 'utf-8',
                'component' => 'vevent',
            ]
        );
        $message->addPart($icsInline);

        // The attachment has filenames and is not 'inline'. This could be
        //  handled by the Laravel `attachments` function, but is included here
        //  to keep both representations together in a similar format.
        $icsDownload = new DataPart($icsData, filename: 'invite.ics', contentType: 'text/calendar');
        $icsDownload->getHeaders()->addParameterizedHeader(
            'Content-Type',
            'text/calendar',
            [
                'method' => $icsMethod,
                'charset' => 'utf-8',
                'component' => 'vevent',
                'name' => 'invite.ics',
            ]
        );
        $message->addPart($icsDownload);
    }

    protected function buildCalendarData(): string
    {
        return view('booking.ics', [
            'bookings' => [$this->booking],
            'user' => $this->attendee,
            'method' => $this->getCalendarMethod(),
            'rsvp' => $this->isResponseNeededFromAttendee(),
        ])->render();
    }
}
