<?php

namespace App\Enums;

enum Role: string
{
    case TeamLeader = 'team-leader';
    case TeamMember = 'team-member';
    case Guest = 'guest';

    protected function rank(): int
    {
        switch ($this) {
            case static::Guest:
                return 0;
            case static::TeamMember:
                return 1;
            case static::TeamLeader:
                return 2;
        }
    }

    public function compare(Role $other): int
    {
        return $this->rank() <=> $other->rank();
    }
}
