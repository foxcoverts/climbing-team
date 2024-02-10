<?php

namespace App\Enums;

enum AttendeeRole: string
{
    /**
     * An instructor responsible for leading a booking.
     */
    case LeadInstructor = 'lead-instructor';
}
