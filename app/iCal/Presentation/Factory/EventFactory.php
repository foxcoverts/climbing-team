<?php

/*
 * This file is part of the eluceo/iCal package.
 *
 * (c) 2024 Markus Poerschke <markus@poerschke.nrw>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\Classification;
use Eluceo\iCal\Domain\Entity\Event as EluceoEvent;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\IntegerValue;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\EventFactory as EluceoEventFactory;
use UnexpectedValueException;

/**
 * @SuppressWarnings("CouplingBetweenObjects")
 */
class EventFactory extends EluceoEventFactory
{
    public function createComponent(EluceoEvent $event): Component
    {
        $component = parent::createComponent($event);

        if ($event instanceof Event) {
            if ($event->hasSequence()) {
                $component = $component->withProperty(new Property('SEQUENCE', new IntegerValue($event->getSequence()->getSequence())));
            }

            if ($event->hasClassification()) {
                $component = $component->withProperty(new Property('CLASS', $this->getEventClassificationTextValue($event->getClassification())));
            }
        }

        return $component;
    }

    private function getEventClassificationTextValue(Classification $classification): TextValue
    {
        return new TextValue(match ($classification) {
            Classification::Confidential => 'CONFIDENTIAL',
            Classification::Private => 'PRIVATE',
            Classification::Public => 'PUBLIC',
            default => throw new UnexpectedValueException(sprintf('The enum %s resulted in an unknown classification value that is not yet implemented.', Classification::class))
        });
    }
}
