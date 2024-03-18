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
        return match ((string) $this->vattendee['PARTSTAT']) {
            'ACCEPTED' => AttendeeStatus::Accepted,
            'DECLINED' => AttendeeStatus::Declined,
            'NEEDS-ACTION' => AttendeeStatus::NeedsAction,
            'TENTATIVE' => AttendeeStatus::Tentative,
            default => null, // Unsupported: 'DELEGATED'
        };
    }

    public function getComment(): string|null
    {
        if (isset($this->vattendee['X-RESPONSE-COMMENT'])) {
            return html_entity_decode(
                str_replace('\;', ';', (string) $this->vattendee['X-RESPONSE-COMMENT'])
            );
        }
        return null;
    }
}
