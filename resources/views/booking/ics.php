<?php

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingStatus;
use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Domain\Enum\Classification;
use App\iCal\Domain\Enum\Transparency;
use App\iCal\Domain\ValueObject\Sequence;
use App\iCal\Presentation\Factory\CalendarFactory;
use App\iCal\Presentation\Factory\EventFactory;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Carbon\Factory as CarbonFactory;
use Eluceo\iCal\Domain\Entity\Attendee;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\Enum\CalendarUserType;
use Eluceo\iCal\Domain\Enum\EventStatus;
use Eluceo\iCal\Domain\Enum\ParticipationStatus;
use Eluceo\iCal\Domain\Enum\RoleType;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Domain\ValueObject\Uri;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

if (! isset($method) || ! $method instanceof CalendarMethod) {
    $method = CalendarMethod::Publish;
}
if (! isset($rsvp) || ! is_bool($rsvp)) {
    $rsvp = $method === CalendarMethod::Request;
}

$calendar = new Calendar;
$calendar->setMethod($method);

if (! empty($name)) {
    $calendar->setName($name);
}
if (! empty($description)) {
    $calendar->setDescription($description);
}
if (! empty($refreshInterval)) {
    $calendar->setRefreshInterval($refreshInterval);
}

$timeZones = [];
foreach ($bookings as $booking) {
    $dateFactory = new CarbonFactory([
        'locale' => config('app.locale', 'en_GB'),
        'timezone' => $booking->timezone ?? CarbonTimeZone::UTC,
    ]);

    $timeZoneId = $booking->timezone?->getName() ?? 'UTC';
    if (! array_key_exists($timeZoneId, $timeZones)) {
        $timeZones[$timeZoneId]['min'] = Carbon::now();
        $timeZones[$timeZoneId]['max'] = Carbon::now();
    }
    if ($booking->start_at->isBefore($timeZones[$timeZoneId]['min'])) {
        $timeZones[$timeZoneId]['min'] = $booking->start_at->toDateTimeImmutable();
    }
    if ($booking->end_at->isAfter($timeZones[$timeZoneId]['max'])) {
        $timeZones[$timeZoneId]['max'] = $booking->end_at->toDateTimeImmutable();
    }

    $textDescription = $booking->group_name;
    if (is_string($booking->notes)) {
        $textDescription .= "\n\n".$booking->notes;
    }
    $textDescription .= "\n\n".route('booking.show', $booking);

    $htmlDescription =
        '<!DOCTYPE html><html lang="en"><body>'.
        Str::markdown($textDescription, [
            'renderer' => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break' => '<br \\>',
            ],
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ])
        .'</body></html>';

    $organiser = new Organizer(
        new EmailAddress($booking->uid),
        config('mail.from.name')
    );

    $event = new Event(new UniqueIdentifier($booking->uid));
    $event
        ->setClassification(Classification::Private)
        ->setHtmlDescription($htmlDescription, $textDescription)
        ->setSequence(new Sequence($booking->sequence))
        ->setTransparency(Transparency::Opaque);
    $event
        ->setOccurrence(
            new TimeSpan(
                new DateTime($dateFactory->make($booking->start_at), true),
                new DateTime($dateFactory->make($booking->end_at), true)
            )
        )
        ->setSummary($booking->activity)
        ->setLocation(
            new Location($booking->location)
        )
        ->setUrl(
            new Uri(route('booking.show', $booking))
        )
        ->setOrganizer($organiser)
        ->setLastModified(
            new Timestamp($booking->updated_at)
        );

    if (is_string($booking->lead_instructor_notes) && Gate::check('lead', $booking)) {
        $event->setComment($booking->lead_instructor_notes);
    }

    $event->setStatus(match ($booking->status) {
        BookingStatus::Tentative => EventStatus::TENTATIVE(),
        BookingStatus::Confirmed => EventStatus::CONFIRMED(),
        BookingStatus::Cancelled => EventStatus::CANCELLED(),
    });

    if (($method !== CalendarMethod::Publish) && ($attendee = $booking->attendees->find($user))) {
        $emailAddress = new EmailAddress($attendee->uid);
        if ($attendee->is($user)) {
            try {
                $emailAddress = new EmailAddress($attendee->email);
            } catch (InvalidArgumentException $e) {
            }
        }

        $evAttendee = (new Attendee($emailAddress))
            ->setCalendarUserType(CalendarUserType::INDIVIDUAL())
            ->setDisplayName($attendee->name);

        if ($rsvp) {
            $evAttendee->setResponseNeededFromAttendee(true);
        }

        if ($attendee->id === $booking->lead_instructor_id) {
            $evAttendee->setRole(RoleType::CHAIR());
        } else {
            $evAttendee->setRole(RoleType::REQ_PARTICIPANT());
        }

        $evAttendee->setParticipationStatus(
            match ($attendee->attendance->status) {
                BookingAttendeeStatus::NeedsAction => ParticipationStatus::NEEDS_ACTION(),
                BookingAttendeeStatus::Accepted => ParticipationStatus::ACCEPTED(),
                BookingAttendeeStatus::Tentative => ParticipationStatus::TENTATIVE(),
                BookingAttendeeStatus::Declined => ParticipationStatus::DECLINED(),
            }
        );

        $event->addAttendee($evAttendee);
    }

    $calendar->addEvent($event);
}

foreach ($timeZones as $timeZoneId => $dates) {
    if ($timeZoneId == 'UTC') {
        continue;
    }

    $timeZone = TimeZone::createFromPhpDateTimeZone(
        new CarbonTimeZone($timeZoneId),
        $dates['min'],
        $dates['max'],
    );
    $calendar->addTimeZone($timeZone);
    $calendar->setTimeZone($timeZone);
}

$eventFactory = new EventFactory;
$calendarFactory = new CalendarFactory(eventFactory: $eventFactory);
echo $calendarFactory->createCalendar($calendar);
