<?php

namespace App\Models;

use App\Enums\AttendeeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendance extends Pivot
{
    protected $table = 'booking_user';

    /**
     * Create a new instance from a User and a Booking.
     *
     * @param User $user
     * @param Booking $booking
     * @return Attendance
     */
    public static function build(Booking $booking, User $user)
    {
        $attendance = new static;
        $attendance->booking = $booking;
        $attendance->user = $user;
        return $attendance;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
    ];


    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => AttendeeStatus::NeedsAction,
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => AttendeeStatus::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAccepted(): bool
    {
        return $this->status == AttendeeStatus::Accepted;
    }
}
