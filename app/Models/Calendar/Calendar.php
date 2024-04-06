<?php

namespace App\Models\Calendar;

use Illuminate\Support\Collection;
use Sabre\VObject;
use Sabre\VObject\Document;

class Calendar
{
    public function __construct(
        protected Document $vcalendar,
    ) {
    }

    public static function loadData(string $raw, string $charset = 'UTF-8'): static
    {
        return new static(
            VObject\Reader::read($raw, VObject\Reader::OPTION_FORGIVING, $charset)
        );
    }

    public function getMethod(): string
    {
        return $this->vcalendar->METHOD;
    }

    /**
     * @return Illuminate\Support\Collection<Event>
     */
    public function getEvents(): Collection
    {
        $events = [];
        foreach ($this->vcalendar->VEVENT as $vevent) {
            $events[] = new Event($vevent);
        }

        return collect($events);
    }

    public function getRaw(): string
    {
        return $this->vcalendar->serialize();
    }
}
