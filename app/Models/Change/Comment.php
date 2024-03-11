<?php

namespace App\Models\Change;

use App\Models\Change;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Comment extends Model
{
    use HasUlids;

    protected $table = 'change_comments';

    // Timestamps are stored on the change
    public $timestamps = false;

    public $fillable = [
        'body',
    ];

    public function change(): BelongsTo
    {
        return $this->belongsTo(Change::class);
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
