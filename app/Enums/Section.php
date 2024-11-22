<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Section: string implements HasColor, HasLabel
{
    case Squirrel = 'squirrel';
    case Beaver = 'beaver';
    case Cub = 'cub';
    case Scout = 'scout';
    case Explorer = 'explorer';
    case Network = 'network';
    case Adult = 'adult';
    case Parent = 'parent';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Explorer => Color::Sky,
            self::Network => Color::Lime,
            self::Adult => Color::Lime,
            default => 'gray',
        };
    }

    public function getLabel(): ?string
    {
        return __('app.user.section.'.$this->value);
    }
}
