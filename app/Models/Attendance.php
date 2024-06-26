<?php

namespace App\Models;

use App\Enums\AttendeeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

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
        'comment',
        'token',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => AttendeeStatus::NeedsAction,
        'comment' => null,
    ];

    /**
     * Make a new token for an Attendance.
     *
     * @return string
     */
    public static function generateToken(): string
    {
        return hash('sha256', sprintf(
            '%s%s%s',
            config('app.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        ));
    }

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

    public function isDeclined(): bool
    {
        return $this->status == AttendeeStatus::Declined;
    }

    public function needsAction(): bool
    {
        return $this->status == AttendeeStatus::NeedsAction;
    }

    public function isLeadInstructor(): bool
    {
        return $this->user_id === $this->booking->lead_instructor_id;
    }

    public function isTeamLeader(): bool
    {
        return $this->user->isTeamLeader();
    }
}
