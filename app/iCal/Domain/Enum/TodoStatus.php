<?php

namespace App\iCal\Domain\Enum;

enum TodoStatus
{
    case NeedsAction;
    case InProcess;
    case Completed;
    case Cancelled;
}
