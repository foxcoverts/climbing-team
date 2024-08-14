<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\TodoStatus;
use App\iCal\Domain\ValueObject\Sequence;
use App\iCal\Presentation\Factory\TodoFactory;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TodoFactoryTest extends TestCase
{
    public function test_todo_is_rendered(): void
    {
        $uid = fake()->word();
        $todo = new Todo(new UniqueIdentifier($uid));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("BEGIN:VTODO\r\n", $output);
        $this->assertStringContainsString("UID:$uid\r\n", $output);
        $this->assertStringContainsString("END:VTODO\r\n", $output);
    }

    public function test_organiser_is_rendered(): void
    {
        $email = fake()->email();
        $name = fake()->name();

        $todo = new Todo;
        $todo->setOrganizer(new Organizer(new EmailAddress($email), $name));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("ORGANIZER;CN=$name:", $output);
    }

    public function test_summary_is_rendered(): void
    {
        $summary = fake()->sentence();

        $todo = new Todo;
        $todo->setSummary($summary);

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("SUMMARY:$summary\r\n", $output);
    }

    public function test_description_is_rendered(): void
    {
        $description = fake()->sentence();

        $todo = new Todo;
        $todo->setDescription($description);

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("DESCRIPTION:$description\r\n", $output);
    }

    public function test_location_is_rendered(): void
    {
        $location = fake()->sentence(3);

        $todo = new Todo;
        $todo->setLocation(new Location($location));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("LOCATION:$location\r\n", $output);
    }

    public function test_priority_is_rendered(): void
    {
        $priority = fake()->randomDigitNotZero();

        $todo = new Todo;
        $todo->setPriority($priority);

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("PRIORITY:$priority\r\n", $output);
    }

    #[DataProvider('status_output_provider')]
    public function test_status_is_rendered(TodoStatus $status, string $expected): void
    {
        $todo = new Todo;
        $todo->setStatus($status);

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("STATUS:$expected\r\n", $output);
    }

    public static function status_output_provider(): array
    {
        return [
            [TodoStatus::NeedsAction, 'NEEDS-ACTION'],
            [TodoStatus::Completed, 'COMPLETED'],
            [TodoStatus::InProcess, 'IN-PROCESS'],
            [TodoStatus::Cancelled, 'CANCELLED'],
        ];
    }

    public function test_due_is_rendered(): void
    {
        $due_at = fake()->dateTime();
        $due_at_ical = $due_at->format('Ymd\THis\Z');

        $todo = new Todo;
        $todo->setDue(new DateTime($due_at, true));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("DUE:$due_at_ical\r\n", $output);
    }

    public function test_start_is_rendered(): void
    {
        $start_at = fake()->dateTime();
        $start_at_ical = $start_at->format('Ymd\THis\Z');

        $todo = new Todo;
        $todo->setStart(new DateTime($start_at, true));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("DTSTART:$start_at_ical\r\n", $output);
    }

    public function test_completed_is_rendered(): void
    {
        $completed_at = fake()->dateTime();
        $completed_at_ical = $completed_at->format('Ymd\This\Z');

        $todo = new Todo;
        $todo->setCompleted(new DateTime($completed_at, true));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("COMPLETED:$completed_at_ical\r\n", $output);
    }

    public function test_sequence_is_rendered(): void
    {
        $sequence = fake()->numberBetween();

        $todo = new Todo;
        $todo->setSequence(new Sequence($sequence));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("SEQUENCE:$sequence\r\n", $output);
    }

    public function test_last_modified_is_rendered(): void
    {
        $last_modified = fake()->dateTime(timezone: 'UTC');
        $last_modified_ical = $last_modified->format('Ymd\This\Z');

        $todo = new Todo;
        $todo->setLastModified(new Timestamp($last_modified));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("LAST-MODIFIED:$last_modified_ical\r\n", $output);
    }

    public function test_touched_at_is_rendered(): void
    {
        $touched_at = fake()->dateTime(timezone: 'UTC');
        $touched_at_ical = $touched_at->format('Ymd\This\Z');

        $todo = new Todo;
        $todo->touch(new Timestamp($touched_at));

        $factory = new TodoFactory;
        $output = (string) $factory->createComponent($todo);

        $this->assertStringContainsString("DTSTAMP:$touched_at_ical\r\n", $output);
    }
}
