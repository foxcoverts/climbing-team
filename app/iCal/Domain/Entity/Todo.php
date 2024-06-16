<?php

namespace App\iCal\Domain\Entity;

use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

class Todo
{
    private UniqueIdentifier $uniqueIdentifier;

    private ?Organizer $organizer = null;

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
}
