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
}
