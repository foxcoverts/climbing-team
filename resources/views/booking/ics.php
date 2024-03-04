<?php

use App\Enums\AttendeeStatus;
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
use Illuminate\Support\Facades\Request;

$calendar = new Calendar();
$calendar->setMethod(CalendarMethod::Publish);

$organiser = new Organizer(
    new EmailAddress('climbing@foxcoverts.org.uk'),
    'Fox Coverts Climbing Team'
);

$domain = Request::getHost();
if ($domain == 'localhost') {
    $domain = 'climbing.foxcoverts.org.uk';
}

foreach ($bookings as $booking) {
    $uid = sprintf('%s@%s', $booking->id, $domain);

    $description = $booking->group_name;
    if (is_string($booking->notes)) {
        $description .= "\n" . $booking->notes;
    }

    $event = new Event(new UniqueIdentifier($uid));
    $event->setSequence(new Sequence(0));
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

    switch ($booking->status) {
        case \App\Enums\BookingStatus::Tentative:
            $event->setStatus(EventStatus::TENTATIVE());
            break;
        case \App\Enums\BookingStatus::Confirmed:
            $event->setStatus(EventStatus::CONFIRMED());
            break;
        case \App\Enums\BookingStatus::Cancelled:
            $event->setStatus(EventStatus::CANCELLED());
            break;
    }

    foreach ($booking->attendees as $attendee) {
        $emailAddress = new EmailAddress(sprintf('%s@%s', $attendee->id, $domain));
        if ($attendee->is($user)) {
            try {
                $emailAddress = new EmailAddress($attendee->email);
            } catch (InvalidArgumentException $e) {
            }
        }

        $evAttendee = (new Attendee($emailAddress))
            ->setCalendarUserType(CalendarUserType::INDIVIDUAL())
            ->setDisplayName($attendee->name);

        if ($attendee->hasVerifiedEmail()) {
            $evAttendee->setResponseNeededFromAttendee(true);
        }

        if ($attendee->is($booking->lead_instructor)) {
            $evAttendee->setRole(RoleType::CHAIR());
        } else {
            $evAttendee->setRole(RoleType::REQ_PARTICIPANT());
        }

        switch ($attendee->attendance->status) {
            case AttendeeStatus::NeedsAction:
                $evAttendee->setParticipationStatus(ParticipationStatus::NEEDS_ACTION());
                break;
            case AttendeeStatus::Accepted:
                $evAttendee->setParticipationStatus(ParticipationStatus::ACCEPTED());
                break;
            case AttendeeStatus::Tentative:
                $evAttendee->setParticipationStatus(ParticipationStatus::TENTATIVE());
                break;
            case AttendeeStatus::Declined:
                $evAttendee->setParticipationStatus(ParticipationStatus::DECLINED());
                break;
        }

        $event->addAttendee($evAttendee);
    }

    $calendar->addEvent($event);
}

$calendarFactory = new CalendarFactory();
echo $calendarFactory->createCalendar($calendar);
