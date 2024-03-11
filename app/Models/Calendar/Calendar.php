<?php

namespace App\Models\Calendar;

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
     * @return array<Event>
     */
    public function getEvents(): array
    {
        $events = [];
        foreach ($this->vcalendar->VEVENT as $vevent) {
            $events[] = new Event($vevent);
        }
        return $events;
    }
}
