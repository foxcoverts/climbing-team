<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class MailLog extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'body'
    ];

    protected $casts = [
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


    protected mixed $jsonCache = null;

    /**
     * @throws InvalidArgumentException
     */
    public function setBodyAttribute(string $value): void
    {
        $json = json_decode($value);
        if (empty($json)) {
            throw new InvalidArgumentException('Body must be valid JSON.');
        }
        if (!property_exists($json, 'subject')) {
            throw new InvalidArgumentException('Body does not look like an encoded email object.');
        }
        $this->jsonCache = $json;
        $this->body = $value;
    }

    protected function parseBody(): mixed
    {
        if ($this->jsonCache === null) {
            try {
                $this->body = $this->body;
            } catch (InvalidArgumentException) {
            }
        }
        return $this->jsonCache;
    }

    public function isValid(): bool
    {
        return $this->parseBody() !== null;
    }

    public function getSentAtAttribute(): string|null
    {
        return $this->parseBody()?->date;
    }

    public function getToAttribute(): string|null
    {
        return $this->parseBody()?->to?->text;
    }

    public function getFromAttribute(): string|null
    {
        return $this->parseBody()?->from?->text;
    }

    public function getSubjectAttribute(): string|null
    {
        return $this->parseBody()?->subject;
    }

    public function getCalendarAttribute(): Calendar\Calendar|null
    {
        $calendar = collect($this->parseBody()?->attachments)
            ->where('contentType', 'text/calendar')
            ->first();
        if ($calendar?->content->type == 'Buffer') {
            $data = '';
            for ($i = 0; $i < count($calendar->content->data); $i++) {
                $data .= chr($calendar->content->data[$i]);
            }
            return Calendar\Calendar::loadData($data);
        }
        return null;
    }

    public function getAttachmentsAttribute(): array|null
    {
        return $this->parseBody()?->attachments;
    }

    public function getBodyHtmlAttribute(): string|null
    {
        return $this->parseBody()?->html ?? $this->parseBody()?->textAsHtml;
    }

    public function getFromUserAttribute(): User|null
    {
        $email = $this->parseBody()?->from->value[0]->address;

        if (empty($email)) {
            return null;
        }

        return User::where('email', $email)->first();
    }

    public function getToBookingAttribute(): Booking|null
    {
        $email = $this->parseBody()?->to->value[0]->address;
        if (empty($email)) {
            return null;
        }

        return Booking::findByUid($email);
    }
}
