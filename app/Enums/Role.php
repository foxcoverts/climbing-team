<?php

namespace App\Enums;

enum Role: string
{
    case TeamLeader = 'team-leader';
    case TeamMember = 'team-member';
    case Guest = 'guest';
    case Suspended = 'suspended';

    protected function rank(): int
    {
        return match ($this) {
            self::TeamLeader => 3,
            self::TeamMember => 2,
            self::Guest => 1,
            self::Suspended => 0,
        };
    }

    public function compare(Role $other): int
    {
        return $this->rank() <=> $other->rank();
    }
}
