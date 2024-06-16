<?php

namespace App\iCal\Presentation\Factory;

use App\iCal\Domain\Collection\Todos;
use App\iCal\Domain\Entity\Todo;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
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
    }
}
