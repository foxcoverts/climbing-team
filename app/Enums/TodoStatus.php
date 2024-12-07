<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TodoStatus: string implements HasColor, HasIcon, HasLabel
{
    case InProcess = 'in-process';
    case NeedsAction = 'needs-action';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::InProcess => 'heroicon-o-play-circle',
            self::Completed => 'heroicon-o-check-circle',
            self::Cancelled => 'heroicon-o-x-circle',
            default => 'heroicon-o-pause-circle',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::InProcess => Color::Sky,
            self::Completed => Color::Lime,
            default => Color::Gray,
        };
    }

    public function getLabel(): ?string
    {
        return __('app.todo.status.'.$this->value);
    }
}
