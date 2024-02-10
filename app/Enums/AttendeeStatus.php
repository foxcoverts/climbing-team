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
}
