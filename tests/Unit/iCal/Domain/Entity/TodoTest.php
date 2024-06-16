<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Todo;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Tests\TestCase;
use TypeError;

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

    public function test_set_get_organizer(): void
    {
        $name = fake()->name();
        $email = fake()->email();
        $todo = new Todo();

        // setOrganizer is chainable
        $this->assertEquals($todo, $todo->setOrganizer(new Organizer(new EmailAddress($email), $name)));
        // hasOrganizer is true when any Organizer is set
        $this->assertTrue($todo->hasOrganizer());

        // getOrganizer returns the set organizer
        $organizer = $todo->getOrganizer();
        $this->assertEquals($email, $organizer->getEmailAddress()->getEmailAddress());
        $this->assertEquals($name, $organizer->getDisplayName());
    }

    public function test_get_fails_with_no_organizer(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo();
        $todo->getOrganizer();
    }

    public function test_has_organizer_with_no_organizer(): void
    {
        $todo = new Todo();
        $this->assertFalse($todo->hasOrganizer());
    }
}
