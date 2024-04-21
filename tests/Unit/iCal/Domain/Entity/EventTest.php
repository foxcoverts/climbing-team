<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\Classification;
use Tests\TestCase;
use TypeError;

class EventTest extends TestCase
{
    public function test_set_get_classification(): void
    {
        $classification = fake()->randomElement(Classification::class);

        $event = new Event;
        // setClassification is chainable
        $this->assertEquals($event, $event->setClassification($classification));
        // hasClassification is true when any Classification is set
        $this->assertTrue($event->hasClassification());
        // getClassification returns the set classification
        $this->assertEquals($classification, $event->getClassification());
    }

    public function test_get_classifcation_fails_with_no_classification(): void
    {
        $this->expectException(TypeError::class);

        $event = new Event;
        $event->getClassification();
    }

    public function test_has_classification_with_no_classification(): void
    {
        $event = new Event;
        $this->assertFalse($event->hasClassification());
    }

    public function test_unset_classification(): void
    {
        $classification = fake()->randomElement(Classification::class);

        $event = new Event;
        $event->setClassification($classification);
        $this->assertEquals($event, $event->unsetClassification());
        $this->assertFalse($event->hasClassification());
    }
}
