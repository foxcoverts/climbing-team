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

    public function test_get_classification_fails_with_no_classification(): void
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

    public function test_set_get_comment(): void
    {
        $comment = fake()->words(15, asText: true);

        $event = new Event;
        // setComment is chainable
        $this->assertEquals($event, $event->setComment($comment));
        // hasComment is true when any comment is set
        $this->assertTrue($event->hasComment());
        // getComment returns the set comment
        $this->assertEquals($comment, $event->getComment());
    }

    public function test_has_comment_with_no_comment(): void
    {
        $event = new Event;
        $this->assertFalse($event->hasComment());
    }

    public function test_get_comment_fails_with_no_comment(): void
    {
        $this->expectException(TypeError::class);

        $event = new Event;
        $event->getComment();
    }

    public function test_unset_comment(): void
    {
        $comment = fake()->words(15, asText: true);

        $event = new Event;
        $event->setComment($comment);
        // unsetComment is chainable
        $this->assertEquals($event, $event->unsetComment());
        // hasComment is false when comment is unset
        $this->assertFalse($event->hasComment());
    }
}
