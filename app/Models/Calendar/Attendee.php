<?php

namespace App\Models\Calendar;

use App\Enums\AttendeeStatus;
use App\Models\User;
use Sabre\VObject\Property;

class Attendee
{
    public function __construct(
        protected Property $vattendee
    ) {
    }

    public function getUser(): User|null
    {
        return User::firstWhere('email', $this->getEmail());
    }

    public function getEmail(): string
    {
        return
            str_replace('mailto:', '', $this->vattendee->getValue());
    }

    public function getStatus(): AttendeeStatus|null
    {
        switch ($this->vattendee['PARTSTAT']) {
            case 'ACCEPTED':
                return AttendeeStatus::Accepted;

            case 'DECLINED':
                return AttendeeStatus::Declined;

            case 'NEEDS-ACTION':
                return AttendeeStatus::NeedsAction;

            case 'TENTATIVE':
                return AttendeeStatus::Tentative;

            default:
            case "DELEGATED":
                return null; // Unsupported
        }
    }
}
