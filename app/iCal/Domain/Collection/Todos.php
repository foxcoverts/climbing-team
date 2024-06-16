<?php

namespace App\iCal\Domain\Collection;

use App\iCal\Domain\Entity\Todo;
use Iterator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<Todo>
 */
abstract class Todos implements IteratorAggregate
{
    /**
     * @return Iterator<Todo>
     */
    abstract public function getIterator(): Iterator;

    abstract public function addTodo(Todo $todo): void;
}
