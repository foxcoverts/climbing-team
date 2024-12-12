<?php

namespace App\Models\Calendar;

use App\Models\Booking;
use App\Models\Concerns\HasNoDatabase;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Sabre\VObject\Component\VEvent;

class Event extends Model
{
    use HasNoDatabase;

    protected VEvent $vevent;

    public static function fromVEvent(VEvent $vevent): static
    {
        $event = new static;
        $event->vevent = $vevent;

        return $event;
    }

    protected function sentAt(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->vevent->DTSTAMP),
        );
    }

    /**
     * @deprecated use `sent_at` attribute instead
     */
    public function getSentAt(): Carbon
    {
        return $this->sent_at;
    }

    protected function booking(): Attribute
    {
        return Attribute::make(
            get: fn () => Booking::findByUid($this->uid)
        );
    }

    /**
     * @deprecated use `booking` attribute instead
     */
    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    protected function uid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vevent->UID
        );
    }

    /**
     * @deprecated use `uid` attribute instead.
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    public function attendees(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->vevent->ATTENDEE)
                ->map(fn ($vattendee) => Attendee::fromVAttendee($vattendee))
        );
    }

    /**
     * @deprecated use `attendees` attribute instead
     *
     * @return Collection<Attendee>
     */
    public function getAttendees(): Collection
    {
        return $this->attendees;
    }
}
