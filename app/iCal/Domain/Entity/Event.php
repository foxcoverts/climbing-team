<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\ValueObject\Sequence;
use Eluceo\iCal\Domain\Entity\Event as EluceoEvent;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

class Event extends EluceoEvent
{
    private ?Sequence $sequence = null;

    public function __construct(UniqueIdentifier $uniqueIdentifier = null)
    {
        parent::__construct($uniqueIdentifier);
    }

    public function getSequence(): Sequence
    {
        assert($this->sequence !== null);

        return $this->sequence;
    }

    public function hasSequence(): bool
    {
        return $this->sequence !== null;
    }

    public function setSequence(Sequence $sequence): static
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function unsetSequence(): static
    {
        $this->sequence = null;

        return $this;
    }
}
