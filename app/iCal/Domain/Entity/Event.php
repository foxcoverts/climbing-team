<?php

namespace App\iCal\Domain\Entity;

use App\iCal\Domain\Enum\Classification;
use App\iCal\Domain\Enum\Transparency;
use App\iCal\Domain\ValueObject\Sequence;
use Eluceo\iCal\Domain\Entity\Event as EluceoEvent;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

class Event extends EluceoEvent
{
    private ?Classification $classification = null;

    private ?string $comment = null;

    private ?string $htmlDescription = null;

    private ?Sequence $sequence = null;

    private ?Transparency $transparency = null;

    public function __construct(?UniqueIdentifier $uniqueIdentifier = null)
    {
        parent::__construct($uniqueIdentifier);
    }

    /**
     * @throws TypeError when classification is not set.
     */
    public function getClassification(): Classification
    {
        return $this->classification;
    }

    public function hasClassification(): bool
    {
        return $this->classification !== null;
    }

    public function setClassification(Classification $classification): static
    {
        $this->classification = $classification;

        return $this;
    }

    public function unsetClassification(): static
    {
        $this->classification = null;

        return $this;
    }

    /**
     * @throws TypeError when comment is not set.
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    public function hasComment(): bool
    {
        return $this->comment !== null;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function unsetComment(): static
    {
        $this->comment = null;

        return $this;
    }

    public function getHtmlDescription(): string
    {
        return $this->htmlDescription;
    }

    public function hasHtmlDescription(): bool
    {
        return $this->htmlDescription !== null;
    }

    public function setHtmlDescription(string $html, string $text): static
    {
        $this->htmlDescription = $html;
        $this->setDescription($text);

        return $this;
    }

    public function unsetHtmlDescription(bool $unsetText = true): static
    {
        $this->htmlDescription = null;

        if ($unsetText) {
            $this->unsetDescription();
        }

        return $this;
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

    public function setTransparency(Transparency $transparency): static
    {
        $this->transparency = $transparency;

        return $this;
    }

    public function getTransparency(): Transparency
    {
        return $this->transparency;
    }

    public function hasTransparency(): bool
    {
        return $this->transparency !== null;
    }

    public function unsetTransparency(): static
    {
        $this->transparency = null;

        return $this;
    }
}
