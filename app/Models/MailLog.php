<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected function parseBody(): mixed
    {
        if (is_null($this->jsonCache)) {
            $this->jsonCache = json_decode($this->body);
        }

        return $this->jsonCache;
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

    public function getBodyHtmlAttribute(): string|null
    {
        return $this->parseBody()?->html ?? $this->parseBody()?->textAsHtml;
    }

    public function getSentAtAttribute(): string|null
    {
        return $this->parseBody()?->date;
    }
}
