<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BookingLeadInstructor implements HasColor, HasIcon, HasLabel
{
    case LeadInstructor;

    public function getColor(): string|array|null
    {
        return BookingAttendeeStatus::Accepted->getColor();
    }

    public function getIcon(): ?string
    {
        return BookingAttendeeStatus::Accepted->getIcon();
    }

    public function getLabel(): ?string
    {
        return 'Lead Instructor';
    }
}
