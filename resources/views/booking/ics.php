<?php

use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\Enum\EventStatus;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Domain\ValueObject\Uri;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Support\Facades\Request;

$calendar = new Calendar();
$calendar->addTimeZone(new TimeZone('Europe/London'));

$organiser = new Organizer(
    new EmailAddress('climbing@foxcoverts.org.uk'),
    'Fox Coverts Climbing Team'
);

$domain = Request::getHost();

foreach ($bookings as $booking) {
    $uid = sprintf('booking-%s@%s', $booking->id, $domain);

    $event = (new Event(new UniqueIdentifier($uid)))
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
        )
        ->setOccurrence(
            new TimeSpan(
                new DateTime($booking->start_at, true),
                new DateTime($booking->end_at, true)
            )
        );
    if (is_string($booking->notes)) {
        $event->setDescription($booking->notes);
    }
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
    $calendar->addEvent($event);
}

$componentFactory = new CalendarFactory();
echo $componentFactory->createCalendar($calendar);
