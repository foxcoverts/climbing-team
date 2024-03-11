<?php

namespace App\Models\Calendar;

use App\Models\Booking;
use Sabre\VObject\Component\VEvent;

class Event
{
    public function __construct(
        protected VEvent $vevent
    ) {
    }

    public function getBooking(): Booking|null
    {
        return Booking::findByUid($this->getUid());
    }

    public function getUid(): string
    {
        return $this->vevent->UID;
    }

    /**
     * @return array<Attendee>
     */
    public function getAttendees(): array
    {
        $attendees = [];
        foreach ($this->vevent->ATTENDEE as $vattendee) {
            $attendees[] = new Attendee($vattendee);
        }
        return $attendees;
    }
}
