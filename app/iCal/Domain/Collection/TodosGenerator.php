<?php

namespace App\iCal\Domain\Collection;

use App\iCal\Domain\Entity\Todo;
use BadMethodCallException;
use Iterator;

final class TodosGenerator extends Todos
{
    /**
     * @var Iterator<Todo>
     */
    private Iterator $generator;

    /**
     * @param  Iterator<Todo>  $generator
     */
    public function __construct(Iterator $generator)
    {
        $this->generator = $generator;
    }

    public function getIterator(): Iterator
    {
        return $this->generator;
    }

    public function addTodo(Todo $todo): void
    {
        throw new BadMethodCallException('Todos cannot be added to an TodosGenerator.');
    }
}
