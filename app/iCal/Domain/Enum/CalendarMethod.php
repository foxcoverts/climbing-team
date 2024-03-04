<?php

namespace App\iCal\Domain\Enum;

enum CalendarMethod
{
    case Add;
    case Cancel;
    case Counter;
    case DeclineCounter;
    case Publish;
    case Refresh;
    case Reply;
    case Request;
}
