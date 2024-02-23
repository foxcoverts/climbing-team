<?php

namespace App\Enums;

enum Role: string
{
    case TeamLeader = 'team-leader';
    case TeamMember = 'team-member';
    case Guest = 'guest';
}
