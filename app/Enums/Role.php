<?php

namespace App\Enums;

enum Role: string
{
    case TeamLeader = 'team-leader';
    case TeamMember = 'team-member';
    case Guest = 'guest';

    protected function rank(): int
    {
        return match ($this) {
            static::Guest => 0,
            static::TeamMember => 1,
            static::TeamLeader => 2,
        };
    }

    public function compare(Role $other): int
    {
        return $this->rank() <=> $other->rank();
    }
}
