<?php

namespace App\iCal\Domain\Collection;

use App\iCal\Domain\Entity\Todo;
use ArrayIterator;
use Iterator;

final class TodosArray extends Todos
{
    /**
     * @var array<int, Todo>
     */
    private array $todos = [];

    /**
     * @param  array<array-key, Todo>  $todos
     */
    public function __construct(array $todos)
    {
        array_walk($todos, [$this, 'addTodo']);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->todos);
    }

    public function addTodo(Todo $todo): void
    {
        $this->todos[] = $todo;
    }
}
