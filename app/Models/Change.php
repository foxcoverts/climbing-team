<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Change extends Model
{
    use HasUlids, HasTimestamps;

    protected $with = [
        'author',
        'attendees',
        'attendees.attendee',
        'comments',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Change\Attendee::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Change\Comment::class);
    }
}
