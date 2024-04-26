<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Enum\CalendarMethod;
use Tests\TestCase;
use TypeError;

class CalendarTest extends TestCase
{
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
}
