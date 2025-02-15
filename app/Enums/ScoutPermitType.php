<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ScoutPermitType: string implements HasLabel
{
    case Personal = 'personal';
    case Leadership = 'leadership';
    case Supervisory = 'supervisory';

    public function getLabel(): ?string
    {
        return __('app.scout-permit.permit-type.'.$this->value);
    }
}
