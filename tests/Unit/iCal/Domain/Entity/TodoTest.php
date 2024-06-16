<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Todo;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Tests\TestCase;

class TodoTest extends TestCase
{
    public function test_new_todo_without_unique_identifier(): void
    {
        $todo = new Todo;
        // getUniqueIdentifier
        $this->assertNotNull($todo->getUniqueIdentifier());
        $this->assertNotEmpty((string) $todo->getUniqueIdentifier());
    }

    public function test_new_todo_with_unique_identifier(): void
    {
        $uid = fake()->word();
        $todo = new Todo(new UniqueIdentifier($uid));

        $this->assertEquals($uid, (string) $todo->getUniqueIdentifier());
    }
}
