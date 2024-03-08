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

$domain = parse_url(config('app.url'), PHP_URL_HOST);
if ($domain == 'localhost') {
    $domain = 'climbfoxcoverts.local';
}

$calendar = new Calendar();
$calendar->setMethod($method ?? CalendarMethod::Publish);

foreach ($bookings as $booking) {
    $uid = sprintf('booking-%s@%s', $booking->id, $domain);

    $description = $booking->group_name;
    if (is_string($booking->notes)) {
        $description .= "\n" . $booking->notes;
    }

    $organiser = new Organizer(
        new EmailAddress($uid),
        config('mail.from.name'),
        sentBy: new EmailAddress(config('mail.from.address')),
    );

    $event = new Event(new UniqueIdentifier($uid));
    $event->setSequence(new Sequence($booking->sequence));
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

    if ($attendee = $booking->attendees->find($user)) {
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

        if (
            $attendee->hasVerifiedEmail() &&
            isset($method) && ($method === CalendarMethod::Request) &&
            $attendee->is($user)
        ) {
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
