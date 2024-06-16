<?php

namespace App\iCal\Domain\Entity;

use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

class Todo
{
    private UniqueIdentifier $uniqueIdentifier;

    private ?Organizer $organizer = null;

    private ?string $summary = null;

    private ?string $description = null;

    private ?Location $location = null;

    public function __construct(?UniqueIdentifier $uniqueIdentifier = null)
    {
        $this->uniqueIdentifier = $uniqueIdentifier ?? UniqueIdentifier::createRandom();
    }

    public function getUniqueIdentifier(): UniqueIdentifier
    {
        return $this->uniqueIdentifier;
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
}
