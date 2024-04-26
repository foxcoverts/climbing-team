<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Enum\CalendarMethod;
use DateInterval;
use Eluceo\iCal\Domain\Entity\TimeZone;
use Tests\TestCase;
use TypeError;

class CalendarTest extends TestCase
{
    public function test_set_get_description(): void
    {
        $description = fake()->paragraph();

        $calendar = new Calendar;
        // setDescription is chainable
        $this->assertEquals($calendar, $calendar->setDescription($description));
        // hasDescription is true when any description is set
        $this->assertTrue($calendar->hasDescription());
        // getDescription returns the set description
        $this->assertEquals($description, $calendar->getDescription());
    }

    public function test_has_description_with_no_description(): void
    {
        $calendar = new Calendar;
        $this->assertFalse($calendar->hasDescription());
    }

    public function test_get_description_fails_with_no_description(): void
    {
        $this->expectException(TypeError::class);

        $calendar = new Calendar;
        $calendar->getDescription();
    }

    public function test_unset_description(): void
    {
        $description = fake()->paragraph();

        $calendar = new Calendar;
        $calendar->setDescription($description);
        // unsetDescription is chainable
        $this->assertEquals($calendar, $calendar->unsetDescription());
        // hasDescription is false when description is unset
        $this->assertFalse($calendar->hasDescription());
    }

    public function test_set_get_method(): void
    {
        $method = fake()->randomElement(CalendarMethod::class);

        $calendar = new Calendar;
        // setMethod is chainable
        $this->assertEquals($calendar, $calendar->setMethod($method));
        // hasMethod is true when any method is set
        $this->assertTrue($calendar->hasMethod());
        // getMethod returns the set method
        $this->assertEquals($method, $calendar->getMethod());
    }

    public function test_has_method_with_no_method(): void
    {
        $calendar = new Calendar;
        $this->assertFalse($calendar->hasMethod());
    }

    public function test_get_method_fails_with_no_method(): void
    {
        $this->expectException(TypeError::class);

        $calendar = new Calendar;
        $calendar->getMethod();
    }

    public function test_unset_method(): void
    {
        $method = fake()->randomElement(CalendarMethod::class);

        $calendar = new Calendar;
        $calendar->setMethod($method);
        // unsetMethod is chainable
        $this->assertEquals($calendar, $calendar->unsetMethod());
        // hasMethod is false when method is unset
        $this->assertFalse($calendar->hasMethod());
    }

    public function test_set_get_name(): void
    {
        $name = fake()->sentence();

        $calendar = new Calendar;
        // setName is chainable
        $this->assertEquals($calendar, $calendar->setName($name));
        // hasName is true when any name is set
        $this->assertTrue($calendar->hasName());
        // getName returns the set name
        $this->assertEquals($name, $calendar->getName());
    }

    public function test_has_name_with_no_name(): void
    {
        $calendar = new Calendar;
        $this->assertFalse($calendar->hasName());
    }

    public function test_get_name_fails_with_no_name(): void
    {
        $this->expectException(TypeError::class);

        $calendar = new Calendar;
        $calendar->getName();
    }

    public function test_unset_name(): void
    {
        $name = fake()->sentence();

        $calendar = new Calendar;
        $calendar->setName($name);
        // unsetName is chainable
        $this->assertEquals($calendar, $calendar->unsetName());
        // hasName is false when name is unset
        $this->assertFalse($calendar->hasName());
    }

    public function test_set_get_refresh_interval(): void
    {
        $duration = DateInterval::createFromDateString('1 day');

        $calendar = new Calendar;
        // setRefreshInterval is chainable
        $this->assertEquals($calendar, $calendar->setRefreshInterval($duration));
        // hasRefreshInterval is true when any refresh interval is set
        $this->assertTrue($calendar->hasRefreshInterval());
        // getRefreshInterval returns the set refresh interval
        $this->assertEquals($duration, $calendar->getRefreshInterval());
    }

    public function test_has_refresh_interval_with_no_refresh_interval(): void
    {
        $calendar = new Calendar;
        $this->assertFalse($calendar->hasRefreshInterval());
    }

    public function test_get_refresh_interval_fails_with_no_refresh_interval(): void
    {
        $this->expectException(TypeError::class);

        $calendar = new Calendar;
        $calendar->getRefreshInterval();
    }

    public function test_unset_refresh_interval(): void
    {
        $duration = DateInterval::createFromDateString('1 week');

        $calendar = new Calendar;
        $calendar->setRefreshInterval($duration);
        // unsetRefreshInterval is chainable
        $this->assertEquals($calendar, $calendar->unsetRefreshInterval());
        // hasRefreshInterval is false when refresh interval is unset
        $this->assertFalse($calendar->hasRefreshInterval());
    }

    public function test_set_get_time_zone(): void
    {
        $timeZoneId = fake()->timezone();
        $timeZone = new TimeZone($timeZoneId);

        $calendar = new Calendar;
        // setTimeZone is chainable
        $this->assertEquals($calendar, $calendar->setTimeZone($timeZone));
        // hasTimeZone is true when any time zone is set
        $this->assertTrue($calendar->hasTimeZone());
        // getTimeZone returns the set time zone
        $this->assertEquals($timeZone, $calendar->getTimeZone());
        $this->assertEquals($timeZoneId, $calendar->getTimeZoneId());
    }

    public function test_has_time_zone_with_no_time_zone(): void
    {
        $calendar = new Calendar;
        $this->assertFalse($calendar->hasTimeZone());
    }

    public function test_get_time_zone_fails_with_no_time_zone(): void
    {
        $this->expectException(TypeError::class);

        $calendar = new Calendar;
        $calendar->getTimeZone();
    }

    public function test_unset_time_zone(): void
    {
        $timeZone = new TimeZone(fake()->timezone());

        $calendar = new Calendar;
        $calendar->setTimeZone($timeZone);
        // unsetTimeZone is chainable
        $this->assertEquals($calendar, $calendar->unsetTimeZone());
        // hasTimeZone is false when time zone is unset
        $this->assertFalse($calendar->hasTimeZone());
    }
}
