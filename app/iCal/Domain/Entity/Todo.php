<?php

namespace App\iCal\Domain\Entity;

use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

class Todo
{
    private UniqueIdentifier $uniqueIdentifier;

    public function __construct(?UniqueIdentifier $uniqueIdentifier = null)
    {
        $this->uniqueIdentifier = $uniqueIdentifier ?? UniqueIdentifier::createRandom();
    }

    public function getUniqueIdentifier(): UniqueIdentifier
    {
        return $this->uniqueIdentifier;
    }
}
