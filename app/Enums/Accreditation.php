<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Accreditation: string implements HasColor, HasLabel
{
    case KitChecker = 'kit-checker';
    case ManageBookings = 'manage-bookings';
    case ManageQualifications = 'manage-qualifications';
    case ManageUsers = 'manage-users';

    public function getColor(): string|array|null
    {
        return Color::Yellow;
    }

    public function getLabel(): ?string
    {
        return __('app.user.accreditation.'.$this->value);
    }
}
