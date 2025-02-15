<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BookingAttendeeResponse: string implements HasColor, HasIcon, HasLabel
{
    case Yes = 'yes';
    case No = 'no';
    case Maybe = 'maybe';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Yes => 'success',
            self::No => 'danger',
            self::Maybe => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Yes => 'heroicon-o-check-circle',
            self::No => 'heroicon-o-x-circle',
            self::Maybe => 'heroicon-o-question-mark-circle',
        };
    }

    public function getLabel(): ?string
    {
        return __('app.booking.attendee.response.'.$this->value);
    }

    public function toStatus(): BookingAttendeeStatus
    {
        return match ($this) {
            self::Yes => BookingAttendeeStatus::Accepted,
            self::No => BookingAttendeeStatus::Declined,
            self::Maybe => BookingAttendeeStatus::Tentative,
        };
    }

    public static function tryFromStatus(BookingAttendeeStatus|string|null $status): ?static
    {
        if (is_string($status)) {
            $status = BookingAttendeeStatus::tryFrom($status);
        }

        return match ($status) {
            BookingAttendeeStatus::Accepted => self::Yes,
            BookingAttendeeStatus::Declined => self::No,
            BookingAttendeeStatus::Tentative => self::Maybe,
            default => null
        };
    }
}
