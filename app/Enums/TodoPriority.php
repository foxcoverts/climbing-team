<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TodoPriority: int implements HasColor, HasLabel
{
    case High = 1;
    case Medium = 5;
    case Low = 9;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::High => Color::Lime,
            self::Medium => Color::Sky,
            self::Low => Color::Yellow,
        };
    }

    public function getLabel(): ?string
    {
        return __('app.todo.priority.'.$this->value);
    }
}
