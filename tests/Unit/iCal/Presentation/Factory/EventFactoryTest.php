<?php

namespace Tests\Unit\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Event;
use App\iCal\Domain\Enum\Classification;
use App\iCal\Domain\Enum\Transparency;
use App\iCal\Presentation\Factory\EventFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EventFactoryTest extends TestCase
{
    #[DataProvider('classification_output_provider')]
    public function test_classification_is_rendered(Classification $classification, string $expected): void
    {
        $event = new Event();
        $event->setClassification($classification);

        $factory = new EventFactory();
        $output = $factory->createComponent($event);

        $this->assertStringContainsString("CLASS:$expected\r\n", (string) $output);
    }

    public static function classification_output_provider(): array
    {
        return [
            [Classification::Confidential, 'CONFIDENTIAL'],
            [Classification::Private, 'PRIVATE'],
            [Classification::Public, 'PUBLIC'],
        ];
    }

    public function test_comment_is_rendered(): void
    {
        $comment = fake()->words(5, asText: true);

        $event = new Event();
        $event->setComment($comment);

        $factory = new EventFactory();
        $output = $factory->createComponent($event);

        $this->assertStringContainsString("COMMENT:$comment\r\n", (string) $output);
    }

    public function test_html_description_is_rendered(): void
    {
        $html = fake()->randomHtml();
        $text = fake()->paragraph();

        $event = new Event();
        $event->setHtmlDescription($html, $text);

        $factory = new EventFactory();
        $output = $factory->createComponent($event);

        $this->assertStringContainsString('X-ALT-DESC;FMTTYPE=text/html:', (string) $output);
        $this->assertStringContainsString('DESCRIPTION:', (string) $output);
    }

    #[DataProvider('transparency_output_provider')]
    public function test_transparency_is_rendered(Transparency $transparency, string $expected): void
    {
        $event = new Event();
        $event->setTransparency($transparency);

        $factory = new EventFactory();
        $output = $factory->createComponent($event);

        $this->assertStringContainsString("TRANSP:$expected\r\n", (string) $output);
    }

    public static function transparency_output_provider(): array
    {
        return [
            [Transparency::Opaque, 'OPAQUE'],
            [Transparency::Transparent, 'TRANSPARENT'],
        ];
    }
}
