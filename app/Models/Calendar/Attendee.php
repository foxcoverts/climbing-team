<?php

namespace App\Models\Calendar;

use App\Enums\BookingAttendeeStatus;
use App\Models\Concerns\HasNoDatabase;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Sabre\VObject\Property;

class Attendee extends Model
{
    use HasNoDatabase;

    protected Property $vattendee;

    public static function fromVAttendee(Property $vattendee): static
    {
        $attendee = new static;
        $attendee->vattendee = $vattendee;

        return $attendee;
    }

    protected function user(): Attribute
    {
        return Attribute::make(
            get: fn () => User::findByEmail($this->email)
        );
    }

    /**
     * @deprecated use `user` attribute instead
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn () => str_replace('mailto:', '', $this->vattendee->getValue())
        );
    }

    /**
     * @deprecated use `email` attribute instead
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn () => match ((string) $this->vattendee['PARTSTAT']) {
                'ACCEPTED' => BookingAttendeeStatus::Accepted,
                'DECLINED' => BookingAttendeeStatus::Declined,
                'NEEDS-ACTION' => BookingAttendeeStatus::NeedsAction,
                'TENTATIVE' => BookingAttendeeStatus::Tentative,
                default => null, // Unsupported: 'DELEGATED'
            }
        );
    }

    /**
     * @deprecated use `status` attribute instaed
     */
    public function getStatus(): ?BookingAttendeeStatus
    {
        return $this->status;
    }

    public function comment(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (isset($this->vattendee['X-RESPONSE-COMMENT'])) {
                    return html_entity_decode(
                        str_replace('\;', ';', (string) $this->vattendee['X-RESPONSE-COMMENT'])
                    );
                }

                return null;
            }
        );
    }

    /**
     * @deprecated use `comment` attribute instead
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }
}
