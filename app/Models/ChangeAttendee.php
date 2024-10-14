<?php

namespace App\Models;

use App\Enums\BookingAttendeeStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ChangeAttendee extends Model
{
    use HasUlids;

    // Timestamps are stored on the change
    public $timestamps = false;

    public $fillable = [
        'attendee_id',
        'attendee_status',
        'attendee_comment',
    ];

    public $with = [
        'attendee',
    ];

    public $casts = [
        'attendee_status' => BookingAttendeeStatus::class,
    ];

    public function change(): BelongsTo
    {
        return $this->belongsTo(Change::class);
    }

    public function attendee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    public function author(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Change::class,
            'id', // changes.id
            'id', // users.id
            'change_id', // change_attendees.change_id
            'author_id' // changes.author_id
        );
    }

    public function getCreatedAtAttribute()
    {
        return $this->change->created_at;
    }

    public function getUpdatedAtAttribute()
    {
        return $this->change->updated_at;
    }
}
