<?php

namespace App\iCal\Presentation\Factory;

use App\iCal\Domain\Collection\Todos;
use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\TodoStatus;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Parameter;
use Eluceo\iCal\Presentation\Component\Property\Value\AppleLocationGeoValue;
use Eluceo\iCal\Presentation\Component\Property\Value\DateTimeValue;
use Eluceo\iCal\Presentation\Component\Property\Value\GeoValue;
use Eluceo\iCal\Presentation\Component\Property\Value\IntegerValue;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Component\Property\Value\UriValue;
use Generator;

class TodoFactory
{
    /**
     * @return Generator<Component>
     */
    final public function createComponents(Todos $todos): Generator
    {
        foreach ($todos as $todo) {
            yield $this->createComponent($todo);
        }
    }

    public function createComponent(Todo $todo): Component
    {
        return new Component(
            'VTODO',
            iterator_to_array($this->getProperties($todo), false)
        );
    }

    /**
     * @return Generator<Property>
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function getProperties(Todo $todo): Generator
    {
        yield new Property('UID', new TextValue((string) $todo->getUniqueIdentifier()));

        if ($todo->hasOrganizer()) {
            yield $this->getOrganizerProperty($todo->getOrganizer());
        }

        if ($todo->hasSummary()) {
            yield new Property('SUMMARY', new TextValue($todo->getSummary()));
        }

        if ($todo->hasDescription()) {
            yield new Property('DESCRIPTION', new TextValue($todo->getDescription()));
        }

        if ($todo->hasLocation()) {
            yield from $this->getLocationProperties($todo->getLocation());
        }

        if ($todo->hasPriority()) {
            yield new Property('PRIORITY', new IntegerValue($todo->getPriority()));
        }

        yield $this->getStatusProperty($todo->getStatus());

        if ($todo->hasDue()) {
            yield new Property('DUE', new DateTimeValue($todo->getDue()));
        }

        if ($todo->hasStart()) {
            yield new Property('DTSTART', new DateTimeValue($todo->getStart()));
        }

        if ($todo->hasCompleted()) {
            yield new Property('COMPLETED', new DateTimeValue($todo->getCompleted()));
        }
    }

    private function getOrganizerProperty(Organizer $organizer): Property
    {
        $parameters = [];

        if ($organizer->hasDisplayName()) {
            $parameters[] = new Parameter('CN', new TextValue($organizer->getDisplayName()));
        }

        if ($organizer->hasDirectoryEntry()) {
            $parameters[] = new Parameter('DIR', new UriValue($organizer->getDirectoryEntry()));
        }

        if ($organizer->isSentInBehalfOf()) {
            $parameters[] = new Parameter('SENT-BY', new UriValue($organizer->getSentBy()->toUri()));
        }

        return new Property('ORGANIZER', new UriValue($organizer->getEmailAddress()->toUri()), $parameters);
    }

    /**
     * @return Generator<Property>
     */
    private function getLocationProperties(Location $location): Generator
    {
        yield new Property('LOCATION', new TextValue((string) $location));

        if ($location->hasGeographicalPosition()) {
            yield new Property('GEO', new GeoValue($location->getGeographicPosition()));
            yield new Property(
                'X-APPLE-STRUCTURED-LOCATION',
                new AppleLocationGeoValue($location->getGeographicPosition()),
                [
                    new Parameter('VALUE', new TextValue('URI')),
                    new Parameter('X-ADDRESS', new TextValue((string) $location)),
                    new Parameter('X-APPLE-RADIUS', new IntegerValue(49)),
                    new Parameter('X-TITLE', new TextValue($location->getTitle())),
                ]
            );
        }
    }

    private function getStatusProperty(TodoStatus $status): Property
    {
        return new Property('STATUS', new TextValue(match ($status) {
            TodoStatus::NeedsAction => 'NEEDS-ACTION',
            TodoStatus::Completed => 'COMPLETED',
            TodoStatus::InProcess => 'IN-PROCESS',
            TodoStatus::Cancelled => 'CANCELLED',
        }));
    }
}
