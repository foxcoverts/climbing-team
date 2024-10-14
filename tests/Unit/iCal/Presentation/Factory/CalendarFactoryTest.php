<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Presentation\Factory\CalendarFactory;
use DateInterval;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;
use Eluceo\iCal\Presentation\Component\Property\Value\DurationValue;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CalendarFactoryTest extends TestCase
{
    public function test_description_is_rendered(): void
    {
        $description = fake()->sentence(nbWords: 2);

        $calendar = new Calendar();
        $calendar->setDescription($description);

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("X-WR-CALDESC:$description\r\n", $output);
    }

    #[DataProvider('method_output_provider')]
    public function test_method_is_rendered(CalendarMethod $method, string $expected): void
    {
        $calendar = new Calendar();
        $calendar->setMethod($method);

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("METHOD:$expected\r\n", $output);
    }

    public static function method_output_provider(): array
    {
        return [
            [CalendarMethod::Add, 'ADD'],
            [CalendarMethod::Cancel, 'CANCEL'],
            [CalendarMethod::Counter, 'COUNTER'],
            [CalendarMethod::DeclineCounter, 'DECLINECOUNTER'],
            [CalendarMethod::Publish, 'PUBLISH'],
            [CalendarMethod::Refresh, 'REFRESH'],
            [CalendarMethod::Reply, 'REPLY'],
            [CalendarMethod::Request, 'REQUEST'],
        ];
    }

    public function test_name_is_rendered(): void
    {
        $name = fake()->sentence(nbWords: 2);

        $calendar = new Calendar();
        $calendar->setName($name);

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("X-WR-CALNAME:$name\r\n", $output);
    }

    public function test_time_zone_is_rendered(): void
    {
        $timezone = fake()->timezone();

        $calendar = new Calendar();
        $calendar->setTimeZone(new TimeZone($timezone));

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("X-WR-TIMEZONE:$timezone\r\n", $output);
    }

    public function test_refresh_interval_is_rendered(): void
    {
        $interval = DateInterval::createFromDateString('6 days');
        $duration = new DurationValue($interval);

        $calendar = new Calendar();
        $calendar->setRefreshInterval($interval);

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("X-PUBLISHED-TTL:$duration\r\n", $output);
    }

    public function test_todo_is_rendered(): void
    {
        $todo_uid = fake()->word();

        $calendar = new Calendar();
        $calendar->addTodo(new Todo(new UniqueIdentifier($todo_uid)));

        $factory = new CalendarFactory();
        $output = (string) $factory->createCalendar($calendar);

        $this->assertStringContainsString("BEGIN:VTODO\r\n", $output);
        $this->assertStringContainsString("UID:$todo_uid\r\n", $output);
    }
}
