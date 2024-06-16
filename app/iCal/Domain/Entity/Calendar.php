<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\Collection\Todos;
use App\iCal\Domain\Collection\TodosArray;
use App\iCal\Domain\Collection\TodosGenerator;
use App\iCal\Domain\Enum\CalendarMethod;
use DateInterval;
use Eluceo\iCal\Domain\Entity\Calendar as EluceoCalendar;
use Eluceo\iCal\Domain\Entity\TimeZone;
use InvalidArgumentException;
use Iterator;

class Calendar extends EluceoCalendar
{
    private ?string $description = null;

    private ?CalendarMethod $method = null;

    private ?string $name = null;

    private ?DateInterval $refreshInterval = null;

    private Todos $todos;

    /**
     * @param  array<array-key, Event>|Iterator<Event>|Events  $events
     * @param  array<array-key, Todo>|Iterator<Todo>|Todos  $todos
     */
    public function __construct($events = [], $todos = [])
    {
        parent::__construct($events);
        $this->todos = $this->ensureTodosObject($todos);
    }

    private function ensureTodosObject($todos = []): Todos
    {
        if ($todos instanceof Todos) {
            return $todos;
        }

        if (is_array($todos)) {
            return new TodosArray($todos);
        }

        if ($todos instanceof Iterator) {
            return new TodosGenerator($todos);
        }

        throw new InvalidArgumentException('$todos must be an array, an object implementing Iterator or an instance of Todos.');
    }

    /**
     * The TimeZone for the whole calendar.
     */
    private ?TimeZone $timeZone = null;

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

    public function getRefreshInterval(): DateInterval
    {
        return $this->refreshInterval;
    }

    public function hasRefreshInterval(): bool
    {
        return $this->refreshInterval !== null;
    }

    public function setRefreshInterval(DateInterval $name): static
    {
        $this->refreshInterval = $name;

        return $this;
    }

    public function unsetRefreshInterval(): static
    {
        $this->refreshInterval = null;

        return $this;
    }

    public function getTimeZone(): TimeZone
    {
        return $this->timeZone;
    }

    public function getTimeZoneId(): string
    {
        return $this->getTimeZone()->getTimeZoneId();
    }

    public function hasTimeZone(): bool
    {
        return $this->timeZone !== null;
    }

    public function setTimeZone(TimeZone $timeZone): static
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function unsetTimeZone(): static
    {
        $this->timeZone = null;

        return $this;
    }

    public function getTodos(): Todos
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo): static
    {
        $this->todos->addTodo($todo);

        return $this;
    }
}
