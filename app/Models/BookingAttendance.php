<?php

namespace App\Models;

use App\Enums\BookingAttendeeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class BookingAttendance extends Pivot
{
    protected $table = 'booking_user';

    /**
     * Create a new instance from a User and a Booking.
     */
    public static function build(Booking $booking, User $user): static
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
        'status' => BookingAttendeeStatus::NeedsAction,
        'comment' => null,
    ];

    /**
     * Make a new token for an BookingAttendance.
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
        'status' => BookingAttendeeStatus::class,
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
        return $this->status == BookingAttendeeStatus::Accepted;
    }

    public function isDeclined(): bool
    {
        return $this->status == BookingAttendeeStatus::Declined;
    }

    public function needsAction(): bool
    {
        return $this->status == BookingAttendeeStatus::NeedsAction;
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
