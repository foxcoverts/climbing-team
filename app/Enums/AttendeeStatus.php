<?php

namespace App\Enums;

enum AttendeeStatus: string
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

    /**
     * Compare with another AttendeeStatus.
     */
    public function compare(AttendeeStatus $other): int
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
