<?php

namespace App\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Enum\CalendarMethod;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\DurationValue;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\EventFactory;
use Eluceo\iCal\Presentation\Factory\TimeZoneFactory;
use Generator;

class CalendarFactory
{
    private EventFactory $eventFactory;
    private TimeZoneFactory $timeZoneFactory;

    public function __construct(EventFactory $eventFactory = null, TimeZoneFactory $timeZoneFactory = null)
    {
        $this->eventFactory = $eventFactory ?? new EventFactory();
        $this->timeZoneFactory = $timeZoneFactory ?? new TimeZoneFactory();
    }

    public function createCalendar(Calendar $calendar): Component
    {
        $components = $this->createCalendarComponents($calendar);
        $properties = iterator_to_array($this->getProperties($calendar), false);

        return new Component('VCALENDAR', $properties, $components);
    }

    /**
     * @return iterable<Component>
     */
    protected function createCalendarComponents(Calendar $calendar): iterable
    {
        yield from $this->eventFactory->createComponents($calendar->getEvents());
        yield from $this->timeZoneFactory->createComponents($calendar->getTimeZones());
    }

    /**
     * @return Generator<Property>
     */
    protected function getProperties(Calendar $calendar): Generator
    {
        /* @see https://www.ietf.org/rfc/rfc5545.html#section-3.7.3 */
        yield new Property('PRODID', new TextValue($calendar->getProductIdentifier()));
        /* @see https://www.ietf.org/rfc/rfc5545.html#section-3.7.4 */
        yield new Property('VERSION', new TextValue('2.0'));
        /* @see https://www.ietf.org/rfc/rfc5545.html#section-3.7.1 */
        yield new Property('CALSCALE', new TextValue('GREGORIAN'));
        if ($calendar->hasMethod()) {
            /* @see https://www.ietf.org/rfc/rfc5546.html#section-1.4 */
            yield new Property('METHOD', $this->getCalendarMethodValue($calendar->getMethod()));
        }
        $publishedTTL = $calendar->getPublishedTTL();
        if ($publishedTTL) {
            /* @see http://msdn.microsoft.com/en-us/library/ee178699(v=exchg.80).aspx */
            yield new Property('X-PUBLISHED-TTL', new DurationValue($publishedTTL));
        }
    }

    protected function getCalendarMethodValue(CalendarMethod $calendarMethod): TextValue
    {
        if ($calendarMethod === CalendarMethod::Add) {
            return new TextValue('ADD');
        }
        if ($calendarMethod === CalendarMethod::Cancel) {
            return new TextValue('CANCEL');
        }
        if ($calendarMethod === CalendarMethod::Counter) {
            return new TextValue('COUNTER');
        }
        if ($calendarMethod === CalendarMethod::DeclineCounter) {
            return new TextValue('DECLINECOUNTER');
        }
        if ($calendarMethod === CalendarMethod::Publish) {
            return new TextValue('PUBLISH');
        }
        if ($calendarMethod === CalendarMethod::Refresh) {
            return new TextValue('REFRESH');
        }
        if ($calendarMethod === CalendarMethod::Reply) {
            return new TextValue('REPLY');
        }
        if ($calendarMethod === CalendarMethod::Request) {
            return new TextValue('REQUEST');
        }
    }
}
