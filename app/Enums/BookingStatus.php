<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BookingStatus: string implements HasColor, HasIcon, HasLabel
{
    case Confirmed = 'confirmed';
    case Tentative = 'tentative';
    case Cancelled = 'cancelled';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Confirmed => Color::Green,
            self::Tentative => Color::Yellow,
            self::Cancelled => Color::Red,
        };
    }

    public function getIcon(): ?string
    {
        return 'heroicon-c-'.match ($this) {
            self::Confirmed => 'check-circle',
            self::Tentative => 'question-mark-circle',
            self::Cancelled => 'x-circle',
        };
    }

    public function getLabel(): ?string
    {
        return __('app.booking.status.'.$this->value);
    }
}
