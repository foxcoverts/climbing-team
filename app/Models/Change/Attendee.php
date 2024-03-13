<?php

namespace App\Models\Change;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\Change;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Attendee extends Model
{
    use HasUlids;

    protected $table = 'change_attendees';

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
        'attendee_status' => AttendeeStatus::class,
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
            'change_id', // change_comments.change_id
            'author_id' // changes.author_id
        );
    }

    public function booking(): HasOneThrough
    {
        return $this->hasOneThrough(
            Booking::class,
            Change::class,
            'id', // changes.id
            'id', // bookings.id
            'change_id', // change_comments.change_id
            'booking_id' // changes.booking_id
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
