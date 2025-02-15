<?php

namespace App\Models\Calendar;

use App\Models\Concerns\HasNoDatabase;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Sabre\VObject;
use Sabre\VObject\Document;

class Calendar extends Model
{
    use HasNoDatabase;

    protected Document $vcalendar;

    public static function loadData(string $raw, string $charset = 'UTF-8'): static
    {
        $calendar = new static;
        $calendar->vcalendar =
            VObject\Reader::read($raw, VObject\Reader::OPTION_FORGIVING, $charset);

        return $calendar;
    }

    protected function method(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vcalendar->METHOD
        );
    }

    /**
     * @deprecated use method attribute
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    protected function events(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->vcalendar->VEVENT)
                ->map(fn ($vevent) => Event::fromVEvent($vevent))
        );
    }

    /**
     * @deprecated use `events` attribute instead
     *
     * @return Illuminate\Support\Collection<Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function raw(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vcalendar->serialize()
        );
    }

    /**
     * @deprecated use `raw` attribute instead
     */
    public function getRaw(): string
    {
        return $this->raw;
    }
}
