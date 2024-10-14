<?php

namespace App\Models\Calendar;

use App\Enums\BookingAttendeeStatus;
use App\Models\User;
use Sabre\VObject\Property;

class Attendee
{
    public function __construct(
        protected Property $vattendee
    ) {
    }

    public function getUser(): ?User
    {
        return User::findByEmail($this->getEmail());
    }

    public function getEmail(): string
    {
        return str_replace('mailto:', '', $this->vattendee->getValue());
    }

    public function getStatus(): ?BookingAttendeeStatus
    {
        return match ((string) $this->vattendee['PARTSTAT']) {
            'ACCEPTED' => BookingAttendeeStatus::Accepted,
            'DECLINED' => BookingAttendeeStatus::Declined,
            'NEEDS-ACTION' => BookingAttendeeStatus::NeedsAction,
            'TENTATIVE' => BookingAttendeeStatus::Tentative,
            default => null, // Unsupported: 'DELEGATED'
        };
    }

    public function getComment(): ?string
    {
        if (isset($this->vattendee['X-RESPONSE-COMMENT'])) {
            return html_entity_decode(
                str_replace('\;', ';', (string) $this->vattendee['X-RESPONSE-COMMENT'])
            );
        }

        return null;
    }
}
