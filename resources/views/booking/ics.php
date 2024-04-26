<?php

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Domain\Enum\Classification;
use App\iCal\Domain\ValueObject\Sequence;
use App\iCal\Presentation\Factory\CalendarFactory;
use App\iCal\Presentation\Factory\EventFactory;
use Carbon\Carbon;
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

if (! isset($method) || ! $method instanceof CalendarMethod) {
    $method = CalendarMethod::Publish;
}

$dateFactory = new CarbonFactory([
    'locale' => config('app.locale', 'en_GB'),
    'timezone' => $user->timezone,
]);

$calendar = new Calendar();
$calendar->setMethod($method);

if (! empty($name)) {
    $calendar->setName($name);
}

$minDate = $maxDate = Carbon::now();

foreach ($bookings as $booking) {
    if ($booking->start_at->isBefore($minDate)) {
        $minDate = $booking->start_at->toDateTimeImmutable();
    }
    if ($booking->end_at->isAfter($maxDate)) {
        $maxDate = $booking->end_at->toDateTimeImmutable();
    }

    $description = $booking->group_name;
    if (is_string($booking->notes)) {
        $description .= "\n\n".$booking->notes;
    }

    $organiser = new Organizer(
        new EmailAddress($booking->uid),
        config('mail.from.name')
    );

    $event = new Event(new UniqueIdentifier($booking->uid));
    $event
        ->setClassification(Classification::Private)
        ->setSequence(new Sequence($booking->sequence));
    $event
        ->setOccurrence(
            new TimeSpan(
                new DateTime($dateFactory->make($booking->start_at), true),
                new DateTime($dateFactory->make($booking->end_at), true)
            )
        )
        ->setSummary($booking->activity)
        ->setDescription($description)
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

        if (
            $attendee->hasVerifiedEmail() &&
            ($method === CalendarMethod::Request) &&
            $attendee->is($user)
        ) {
            $evAttendee->setResponseNeededFromAttendee(true);
        }

        if ($attendee->id === $booking->lead_instructor_id) {
            $evAttendee->setRole(RoleType::CHAIR());
        } else {
            $evAttendee->setRole(RoleType::REQ_PARTICIPANT());
        }

        $evAttendee->setParticipationStatus(
            match ($attendee->attendance->status) {
                AttendeeStatus::NeedsAction => ParticipationStatus::NEEDS_ACTION(),
                AttendeeStatus::Accepted => ParticipationStatus::ACCEPTED(),
                AttendeeStatus::Tentative => ParticipationStatus::TENTATIVE(),
                AttendeeStatus::Declined => ParticipationStatus::DECLINED(),
            }
        );

        $event->addAttendee($evAttendee);
    }

    $calendar->addEvent($event);
}

$calendar->addTimeZone(TimeZone::createFromPhpDateTimeZone(
    $user->timezone,
    $dateFactory->make($minDate)->toDateTimeImmutable(),
    $dateFactory->make($maxDate)->toDateTimeImmutable(),
));

$eventFactory = new EventFactory();
$calendarFactory = new CalendarFactory(eventFactory: $eventFactory);
echo $calendarFactory->createCalendar($calendar);
