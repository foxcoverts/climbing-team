<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\Enum\TodoStatus;
use App\iCal\Domain\ValueObject\Sequence;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use InvalidArgumentException;

class Todo
{
    private UniqueIdentifier $uniqueIdentifier;

    private Timestamp $touchedAt;

    private ?Organizer $organizer = null;

    private ?string $summary = null;

    private ?string $description = null;

    private ?Location $location = null;

    private ?int $priority = null;

    private TodoStatus $status;

    private ?DateTime $due = null;

    private ?DateTime $start = null;

    private ?DateTime $completed = null;

    private ?Sequence $sequence = null;

    private ?Timestamp $lastModified = null;

    public function __construct(?UniqueIdentifier $uniqueIdentifier = null)
    {
        $this->uniqueIdentifier = $uniqueIdentifier ?? UniqueIdentifier::createRandom();
        $this->touchedAt = new Timestamp;
        $this->status = TodoStatus::NeedsAction;
    }

    public function getUniqueIdentifier(): UniqueIdentifier
    {
        return $this->uniqueIdentifier;
    }

    public function getTouchedAt(): Timestamp
    {
        return $this->touchedAt;
    }

    public function touch(?Timestamp $dateTime = null): self
    {
        $this->touchedAt = $dateTime ?? new Timestamp;

        return $this;
    }

    public function getOrganizer(): Organizer
    {
        assert($this->organizer !== null);

        return $this->organizer;
    }

    public function setOrganizer(?Organizer $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function hasOrganizer(): bool
    {
        return $this->organizer !== null;
    }

    /**
     * @throws TypeError when summary is not set.
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    public function hasSummary(): bool
    {
        return $this->summary !== null;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function unsetSummary(): static
    {
        $this->summary = null;

        return $this;
    }

    /**
     * @throws TypeError when description is not set.
     */
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

    /**
     * @throws TypeError when location is not set.
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    public function hasLocation(): bool
    {
        return $this->location !== null;
    }

    public function setLocation(Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function unsetLocation(): static
    {
        $this->location = null;

        return $this;
    }

    /**
     * @throws TypeError when priority is not set.
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    public function hasPriority(): bool
    {
        return $this->priority !== null;
    }

    public function setPriority(int $priority): static
    {
        if ($priority < 1 || $priority > 9) {
            throw new InvalidArgumentException('$priority must be a value between 1 and 9.');
        }

        $this->priority = $priority;

        return $this;
    }

    public function unsetPriority(): static
    {
        $this->priority = null;

        return $this;
    }

    public function setStatus(TodoStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): TodoStatus
    {
        return $this->status;
    }

    public function setDue(DateTime $due): static
    {
        $this->due = $due;

        return $this;
    }

    public function hasDue(): bool
    {
        return $this->due !== null;
    }

    /**
     * @throws TypeError when due is not set.
     */
    public function getDue(): DateTime
    {
        return $this->due;
    }

    public function unsetDue(): static
    {
        $this->due = null;

        return $this;
    }

    public function setStart(DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function hasStart(): bool
    {
        return $this->start !== null;
    }

    /**
     * @throws TypeError when start is not set.
     */
    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function unsetStart(): static
    {
        $this->start = null;

        return $this;
    }

    public function setCompleted(DateTime $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function hasCompleted(): bool
    {
        return $this->completed !== null;
    }

    /**
     * @throws TypeError when completed is not set.
     */
    public function getCompleted(): DateTime
    {
        return $this->completed;
    }

    public function unsetCompleted(): static
    {
        $this->completed = null;

        return $this;
    }

    public function setSequence(Sequence $sequence): static
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function hasSequence(): bool
    {
        return $this->sequence !== null;
    }

    /**
     * @throws TypeError when sequence is not set.
     */
    public function getSequence(): Sequence
    {
        return $this->sequence;
    }

    public function unsetSequence(): static
    {
        $this->sequence = null;

        return $this;
    }

    public function setLastModified(Timestamp $lastModified): static
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function hasLastModified(): bool
    {
        return $this->lastModified !== null;
    }

    /**
     * @throws TypeError when last modified is not set.
     */
    public function getLastModified(): Timestamp
    {
        return $this->lastModified;
    }

    public function unsetLastModified(): static
    {
        $this->lastModified = null;

        return $this;
    }
}
