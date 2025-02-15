<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ScoutPermitCategory: string implements HasLabel
{
    case ArtificialTopRope = 'Artificial Top Rope';
    case NaturalTopRope = 'Natural Top Rope';
    case ArtificialLeadClimbing = 'Artificial Lead Climbing';
    case NaturalLeadClimbing = 'Natural Lead Climbing';

    public function getLabel(): ?string
    {
        return __('app.scout-permit.category.'.$this->value);
    }
}
