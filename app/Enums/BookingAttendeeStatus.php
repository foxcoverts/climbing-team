<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BookingAttendeeStatus: string implements HasIcon, HasLabel
{
    /**
     * Person has confirmed they are available to help with a booking.
     */
    case Accepted = 'accepted';

    /**
     * Person has responded 'maybe available' to a booking. It should not be assumed they will attend.
     */
    case Tentative = 'tentative';

    /**
     * Person has confirmed they are not available for a booking.
     */
    case Declined = 'declined';

    /**
     * Person has been invited to the booking but has not responded.
     */
    case NeedsAction = 'needs-action';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Accepted,
            self::Tentative => 'heroicon-o-check-circle',
            self::Declined => 'heroicon-o-x-circle',
            self::NeedsAction => 'heroicon-o-question-mark-circle',
        };
    }

    public function getLabel(): ?string
    {
        return __('app.attendee.status.'.$this->value);
    }

    /**
     * Compare with another BookingAttendeeStatus.
     */
    public function compare(BookingAttendeeStatus $other): int
    {
        return $this->rank() <=> $other->rank();
    }

    protected function rank(): int
    {
        return match ($this) {
            self::Accepted => 0,
            self::Tentative => 1,
            self::Declined => 2,
            self::NeedsAction => 3,
        };
    }
}
