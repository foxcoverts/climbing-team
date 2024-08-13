<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\TodoStatus;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use InvalidArgumentException;
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
        $todo = new Todo;

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

        $todo = new Todo;
        $todo->getOrganizer();
    }

    public function test_has_organizer_with_no_organizer(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasOrganizer());
    }

    public function test_set_get_summary(): void
    {
        $summary = fake()->words(15, asText: true);

        $todo = new Todo;
        // setSummary is chainable
        $this->assertEquals($todo, $todo->setSummary($summary));
        // hasSummary is true when any summary is set
        $this->assertTrue($todo->hasSummary());
        // getSummary returns the set summary
        $this->assertEquals($summary, $todo->getSummary());
    }

    public function test_has_summary_with_no_summary(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasSummary());
    }

    public function test_get_summary_fails_with_no_summary(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo;
        $todo->getSummary();
    }

    public function test_unset_summary(): void
    {
        $summary = fake()->words(15, asText: true);

        $todo = new Todo;
        $todo->setSummary($summary);
        // unsetSummary is chainable
        $this->assertEquals($todo, $todo->unsetSummary());
        // hasSummary is false when summary is unset
        $this->assertFalse($todo->hasSummary());
    }

    public function test_set_get_description(): void
    {
        $description = fake()->words(15, asText: true);

        $todo = new Todo;
        // setDescription is chainable
        $this->assertEquals($todo, $todo->setDescription($description));
        // hasDescription is true when any description is set
        $this->assertTrue($todo->hasDescription());
        // getDescription returns the set description
        $this->assertEquals($description, $todo->getDescription());
    }

    public function test_has_description_with_no_description(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasDescription());
    }

    public function test_get_description_fails_with_no_description(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo;
        $todo->getDescription();
    }

    public function test_unset_description(): void
    {
        $description = fake()->words(15, asText: true);

        $todo = new Todo;
        $todo->setDescription($description);
        // unsetDescription is chainable
        $this->assertEquals($todo, $todo->unsetDescription());
        // hasDescription is false when description is unset
        $this->assertFalse($todo->hasDescription());
    }

    public function test_set_get_location(): void
    {
        $location = new Location(fake()->sentence());

        $todo = new Todo;
        // setLocation is chainable
        $this->assertEquals($todo, $todo->setLocation($location));
        // hasLocation is true when any location is set
        $this->assertTrue($todo->hasLocation());
        // getLocation returns the set location
        $this->assertEquals($location, $todo->getLocation());
    }

    public function test_has_location_with_no_location(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasLocation());
    }

    public function test_get_location_fails_with_no_location(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo;
        $todo->getLocation();
    }

    public function test_unset_location(): void
    {
        $location = new Location(fake()->sentence());

        $todo = new Todo;
        $todo->setLocation($location);
        // unsetLocation is chainable
        $this->assertEquals($todo, $todo->unsetLocation());
        // hasLocation is false when location is unset
        $this->assertFalse($todo->hasLocation());
    }

    public function test_set_get_priority(): void
    {
        $priority = fake()->randomDigitNotZero();

        $todo = new Todo;
        // setPriority is chainable
        $this->assertEquals($todo, $todo->setPriority($priority));
        // hasPriority is true when any priority is set
        $this->assertTrue($todo->hasPriority());
        // getPriority returns the set priority
        $this->assertEquals($priority, $todo->getPriority());
    }

    public function test_has_priority_with_no_priority(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasPriority());
    }

    public function test_get_priority_fails_with_no_priority(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo;
        $todo->getPriority();
    }

    public function test_unset_priority(): void
    {
        $priority = fake()->randomDigitNotZero();

        $todo = new Todo;
        $todo->setPriority($priority);
        // unsetPriority is chainable
        $this->assertEquals($todo, $todo->unsetPriority());
        // hasPriority is false when priority is unset
        $this->assertFalse($todo->hasPriority());
    }

    public function test_set_priority_fails_with_large_number(): void
    {
        $todo = new Todo;

        $this->expectException(InvalidArgumentException::class);
        $big_priority = fake()->numberBetween(10, 2147483647);
        $todo->setPriority($big_priority);
    }

    public function test_set_priority_fails_with_negative_number(): void
    {
        $todo = new Todo;

        $this->expectException(InvalidArgumentException::class);
        $negative_number = 0 - fake()->numberBetween();
        $todo->setPriority($negative_number);
    }

    public function test_set_get_status(): void
    {
        $status = fake()->randomElement(TodoStatus::class);

        $todo = new Todo;

        // default status is NeedsAction
        $this->assertEquals(TodoStatus::NeedsAction, $todo->getStatus());

        // setStatus is chainable
        $this->assertEquals($todo, $todo->setStatus($status));
        // getStatus returns the set status
        $this->assertEquals($status, $todo->getStatus());
    }

    public function test_set_get_due(): void
    {
        $due = fake()->dateTime();

        $todo = new Todo;

        // setDue is chainable
        $this->assertEquals($todo, $todo->setDue(new DateTime($due, true)));
        // hasDue is true when due is set
        $this->assertTrue($todo->hasDue());
        // getDue returns the set due
        $this->assertEquals($due, $todo->getDue()->getDateTime());
    }

    public function test_unset_due(): void
    {
        $due = fake()->dateTime();

        $todo = new Todo;
        $todo->setDue(new DateTime($due, true));

        // unsetDue is chainable
        $this->assertEquals($todo, $todo->unsetDue());
        // hasDue is false when due is unset
        $this->assertFalse($todo->hasDue());
    }

    public function test_get_fails_with_no_due(): void
    {
        $this->expectException(TypeError::class);

        $todo = new Todo;
        $todo->getDue();
    }

    public function test_has_organizer_with_no_due(): void
    {
        $todo = new Todo;
        $this->assertFalse($todo->hasDue());
    }
}
