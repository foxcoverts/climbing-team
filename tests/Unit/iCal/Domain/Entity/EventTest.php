<?php

namespace Tests\Unit\iCal\Domain\Entity;

use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\Classification;
use App\iCal\Domain\Enum\Transparency;
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

    public function test_set_get_html_description(): void
    {
        $html = fake()->randomHtml();
        $text = fake()->paragraph();

        $event = new Event;
        // setHtmlDescription is chainable
        $this->assertEquals($event, $event->setHtmlDescription($html, $text));
        // hasHtmlDescription is true when a HTML Description is set
        $this->assertTrue($event->hasHtmlDescription());
        // getHtmlDescription returns the set HTML Description
        $this->assertEquals($html, $event->getHtmlDescription());
        // plain description is set to text version
        $this->assertTrue($event->hasDescription());
        $this->assertEquals($text, $event->getDescription());
    }

    public function test_has_html_description_with_none(): void
    {
        $event = new Event;
        $this->assertFalse($event->hasHtmlDescription());
    }

    public function test_get_html_description_fails_with_none(): void
    {
        $this->expectException(TypeError::class);

        $event = new Event;
        $event->getHtmlDescription();
    }

    public function test_unset_html_description(): void
    {
        $html = fake()->randomHtml();
        $text = fake()->paragraph();

        $event = new Event;
        $event->setHtmlDescription($html, $text);
        // unsetHtmlDescription is chainable
        $this->assertEquals($event, $event->unsetHtmlDescription());
        // hasHtmlDescription is false when unset
        $this->assertFalse($event->hasHtmlDescription());
        // plain description is also unset
        $this->assertFalse($event->hasDescription());
    }

    public function test_unset_html_description_can_ignore_text(): void
    {
        $html = fake()->randomHtml();
        $text = fake()->paragraph();

        $event = new Event;
        $event->setHtmlDescription($html, $text);
        $event->unsetHtmlDescription(false);
        $this->assertFalse($event->hasHtmlDescription());
        $this->assertEquals($text, $event->getDescription());
        $this->assertTrue($event->hasDescription());
    }

    public function test_set_get_transparency(): void
    {
        $transparency = fake()->randomElement(Transparency::class);

        $event = new Event;
        // setClassification is chainable
        $this->assertEquals($event, $event->setTransparency($transparency));
        // hasClassification is true when any Classification is set
        $this->assertTrue($event->hasTransparency());
        // getClassification returns the set classification
        $this->assertEquals($transparency, $event->getTransparency());
    }

    public function test_get_transparency_fails_with_no_transparency(): void
    {
        $this->expectException(TypeError::class);

        $event = new Event;
        $event->getTransparency();
    }

    public function test_has_transparency_with_no_transparency(): void
    {
        $event = new Event;
        $this->assertFalse($event->hasTransparency());
    }

    public function test_unset_transparency(): void
    {
        $transparency = fake()->randomElement(Transparency::class);

        $event = new Event;
        $event->setTransparency($transparency);
        $this->assertEquals($event, $event->unsetTransparency());
        $this->assertFalse($event->hasTransparency());
    }
}
