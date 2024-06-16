<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Todo;
use App\iCal\Presentation\Factory\TodoFactory;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
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

    public function test_summary_is_rendered(): void
    {
        $summary = fake()->sentence();

        $todo = new Todo();
        $todo->setSummary($summary);

        $factory = new TodoFactory();
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("SUMMARY:$summary\r\n", $output);
    }

    public function test_description_is_rendered(): void
    {
        $description = fake()->sentence();

        $todo = new Todo();
        $todo->setDescription($description);

        $factory = new TodoFactory();
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("DESCRIPTION:$description\r\n", $output);
    }

    public function test_location_is_rendered(): void
    {
        $location = fake()->sentence(3);

        $todo = new Todo();
        $todo->setLocation(new Location($location));

        $factory = new TodoFactory();
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("LOCATION:$location\r\n", $output);
    }
}
