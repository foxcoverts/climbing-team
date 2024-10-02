<?php

namespace App\Enums;

enum TodoStatus: string
{
    case InProcess = 'in-process';
    case NeedsAction = 'needs-action';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
