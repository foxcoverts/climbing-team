<?php

namespace App\Enums;

enum ScoutPermitType: string
{
    case Personal = 'personal';
    case Leadership = 'leadership';
    case Supervisory = 'supervisory';
}
