<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Todo;
use App\iCal\Presentation\Factory\TodoFactory;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Tests\TestCase;

class TodoFactoryTest extends TestCase
{
    public function test_todo_is_rendered(): void
    {
        $uid = fake()->word();
        $todo = new Todo(new UniqueIdentifier($uid));

        $factory = new TodoFactory();
        $output = $factory->createComponent($todo);

        $this->assertStringContainsString("BEGIN:VTODO\r\n", (string) $output);
        $this->assertStringContainsString("UID:$uid\r\n", (string) $output);
        $this->assertStringContainsString("END:VTODO\r\n", (string) $output);
    }
}
