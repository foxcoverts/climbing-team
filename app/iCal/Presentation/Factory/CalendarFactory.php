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
        }

        return $component;
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
