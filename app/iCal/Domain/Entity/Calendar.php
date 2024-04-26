<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\Enum\CalendarMethod;
use Eluceo\iCal\Domain\Entity\Calendar as EluceoCalendar;

class Calendar extends EluceoCalendar
{
    private ?string $description = null;

    private ?CalendarMethod $method = null;

    private ?string $name = null;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function unsetDescription(): static
    {
        $this->description = null;

        return $this;
    }

    public function getMethod(): CalendarMethod
    {
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

    public function getName(): string
    {
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function unsetName(): static
    {
        $this->name = null;

        return $this;
    }
}
