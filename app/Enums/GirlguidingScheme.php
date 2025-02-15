<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GirlguidingScheme: string implements HasLabel
{
    case Climbing = 'climbing';

    public function getLabel(): ?string
    {
        return __("app.girlguiding.scheme.{$this->value}");
    }
}
