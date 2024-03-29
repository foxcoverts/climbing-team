<?php

use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Domain\ValueObject\Sequence;
use App\iCal\Presentation\Factory\CalendarFactory;
use Eluceo\iCal\Domain\Entity\Attendee;
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

if (!isset($method) || !$method instanceof CalendarMethod) {
    $method = CalendarMethod::Publish;
}

$calendar = new Calendar();
$calendar->setMethod($method);

foreach ($bookings as $booking) {
    $description = $booking->group_name;
    if (is_string($booking->notes)) {
        $description .= "\n\n" . $booking->notes;
    }

    $organiser = new Organizer(
        new EmailAddress($booking->uid),
        config('mail.from.name')
    );

    $event = new Event(new UniqueIdentifier($booking->uid));
    $event
        ->setSequence(new Sequence($booking->sequence));
    $event
        ->setOccurrence(
            new TimeSpan(
                new DateTime($booking->start_at, true),
                new DateTime($booking->end_at, true)
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

$calendarFactory = new CalendarFactory();
echo $calendarFactory->createCalendar($calendar);
