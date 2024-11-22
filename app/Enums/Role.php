<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasColor, HasLabel
{
    case TeamLeader = 'team-leader';
    case TeamMember = 'team-member';
    case Guest = 'guest';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Guest => Color::Gray,
            self::TeamMember => Color::Lime,
            self::TeamLeader => Color::Yellow,
        };
    }

    public function getLabel(): ?string
    {
        return __('app.user.role.'.$this->value);
    }

    protected function getRank(): int
    {
        return match ($this) {
            self::Guest => 0,
            self::TeamMember => 1,
            self::TeamLeader => 2,
        };
    }

    public function compare(Role $other): int
    {
        return $this->getRank() <=> $other->getRank();
    }
}
