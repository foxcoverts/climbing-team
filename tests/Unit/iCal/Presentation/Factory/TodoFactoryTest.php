<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Todo;
use App\iCal\Presentation\Factory\TodoFactory;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Tests\TestCase;

class TodoFactoryTest extends TestCase
{
    public function test_todo_is_rendered(): void
    {
        $uid = fake()->word();
        $todo = new Todo(new UniqueIdentifier($uid));

        $factory = new TodoFactory();
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("BEGIN:VTODO\r\n", $output);
        $this->assertStringContainsString("UID:$uid\r\n", $output);
        $this->assertStringContainsString("END:VTODO\r\n", $output);
    }

    public function test_organiser_is_rendered(): void
    {
        $email = fake()->email();
        $name = fake()->name();

        $todo = new Todo();
        $todo->setOrganizer(new Organizer(new EmailAddress($email), $name));

        $factory = new TodoFactory();
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("ORGANIZER;CN=$name:", $output);
    }
}
