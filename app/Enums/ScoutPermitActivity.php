<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ScoutPermitActivity: string implements HasLabel
{
    case ClimbingAndAbseiling = 'Climbing and Abseiling';

    public function getLabel(): ?string
    {
        return __('app.scout-permit.activity.'.$this->value);
    }
}
