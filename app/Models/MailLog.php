<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @property-read string $rawBody
 * @property-read ?string $sentAt
 * @property-read ?string $to
 * @property-read ?string $from
 * @property-read ?string $subject
 * @property-read ?array $attachments
 * @property-read ?string $bodyHtml
 * @property-read ?string $fromEmail
 * @property-read ?User $fromUser
 * @property-read ?Calendar\Calendar $calendar
 * @property-read ?string $toEmail
 * @property-read ?Booking $toBooking
 * @property-read ?Booking $booking
 * @property-read ?User $user
 */
class MailLog extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'body',
    ];

    protected $casts = [
        'body' => 'object',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function markRead(bool $force = false): static
    {
        if (is_null($this->read_at) || $force) {
            $this->read_at = $this->freshTimestamp();
        }

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setBodyAttribute(string $value): void
    {
        $json = json_decode($value);
        if (empty($json)) {
            throw new InvalidArgumentException('Body must be valid JSON.');
        }
        if (! property_exists($json, 'subject')) {
            throw new InvalidArgumentException('Body does not look like an encoded email object.');
        }
        $this->attributes['body'] = $value;
    }

    public function isValid(): bool
    {
        return ! is_null($this->body);
    }

    protected function rawBody(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['body'],
        );
    }

    protected function sentAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->date,
        );
    }

    protected function to(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->to?->text,
        );
    }

    protected function from(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->from?->text,
        );
    }

    protected function subject(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->subject,
        );
    }

    protected function calendar(): Attribute
    {
        return Attribute::make(
            get: function () {
                $calendar = collect($this->attachments)
                    ->where('contentType', 'text/calendar')
                    ->first();
                if (is_object($calendar) && $calendar->content->type == 'Buffer') {
                    $data = '';
                    for ($i = 0; $i < count($calendar->content->data); $i++) {
                        $data .= chr($calendar->content->data[$i]);
                    }

                    return Calendar\Calendar::loadData($data);
                }

                return null;
            }
        );
    }

    protected function attachments(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->attachments,
        );
    }

    protected function bodyHtml(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->html
                ?? $this->body->textAsHtml,
        );
    }

    protected function fromEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->from->value[0]->address,
        );
    }

    protected function fromUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fromEmail
                ? User::findByEmail($this->fromEmail)
                : null,
        );
    }

    protected function toEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->body->to->value[0]->address,
        );
    }

    protected function toBooking(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->toEmail
                ? Booking::findByUid($this->toEmail)
                : null,
        );
    }

    protected function booking(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calendar?->getEvents()->first()?->getBooking()
                ?? $this->toBooking,
        );
    }

    protected function user(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calendar?->getEvents()->first()?->getAttendees()->first()?->getUser()
                ?? $this->fromUser,
        );
    }
}
