<?php

namespace App\iCal\Domain\ValueObject;

use InvalidArgumentException;

class Sequence
{
    private int $sequence;

    public function __construct(int $sequence)
    {
        if ($sequence < 0) {
            throw new InvalidArgumentException("$sequence is not a valid sequence");
        }

        $this->sequence = $sequence;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }
}
