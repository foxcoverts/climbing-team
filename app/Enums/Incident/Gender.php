<?php

namespace App\Enums\Incident;

enum Gender: string
{
    case Female = 'Female';
    case Indeterminate = 'Indeterminate';
    case Male = 'Male';
    case Unknown = 'Unknown';
}
