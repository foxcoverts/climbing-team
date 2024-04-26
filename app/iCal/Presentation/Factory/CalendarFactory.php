<?php

namespace App\iCal\Presentation\Factory;

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Enum\CalendarMethod;
use Eluceo\iCal\Domain\Entity\Calendar as EluceoCalendar;
use Eluceo\iCal\Presentation\Component;
use Eluceo\iCal\Presentation\Component\Property;
use Eluceo\iCal\Presentation\Component\Property\Value\TextValue;
use Eluceo\iCal\Presentation\Factory\CalendarFactory as EluceoCalendarFactory;

class CalendarFactory extends EluceoCalendarFactory
{
    public function createCalendar(EluceoCalendar $calendar): Component
    {
        $component = parent::createCalendar($calendar);

        if ($calendar instanceof Calendar) {
            if ($calendar->hasMethod()) {
                /* @see https://www.ietf.org/rfc/rfc5546.html#section-1.4 */
                $component = $component->withProperty(
                    new Property('METHOD', $this->getCalendarMethodValue($calendar->getMethod()))
                );
            }

            if ($calendar->hasName()) {
                $component = $component->withProperty(
                    new Property('X-WR-CALNAME', new TextValue($calendar->getName()))
                );
            }
        }

        return $component;
    }

    protected function getCalendarMethodValue(CalendarMethod $calendarMethod): TextValue
    {
        return new TextValue(match ($calendarMethod) {
            CalendarMethod::Add => 'ADD',
            CalendarMethod::Cancel => 'CANCEL',
            CalendarMethod::Counter => 'COUNTER',
            CalendarMethod::DeclineCounter => 'DECLINECOUNTER',
            CalendarMethod::Publish => 'PUBLISH',
            CalendarMethod::Refresh => 'REFRESH',
            CalendarMethod::Reply => 'REPLY',
            CalendarMethod::Request => 'REQUEST',
        });
    }
}
