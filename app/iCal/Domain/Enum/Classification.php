<?php

namespace App\iCal\Domain\Enum;

enum Classification
{
    case Public;
    case Private;
    case Confidential;
}
