<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\Enum\CalendarMethod;
use Eluceo\iCal\Domain\Entity\Calendar as EluceoCalendar;

class Calendar extends EluceoCalendar
{
    private ?CalendarMethod $method = null;

    public function getMethod(): CalendarMethod
    {
        assert($this->method !== null);

        return $this->method;
    }

    public function hasMethod(): bool
    {
        return $this->method !== null;
    }

    public function setMethod(CalendarMethod $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function unsetMethod(): static
    {
        $this->method = null;

        return $this;
    }
}
