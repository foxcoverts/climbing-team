<?php

namespace App\Models;

use App\Casts\AsSequence;
use App\Casts\AsTimezone;
use App\Enums\AttendeeStatus;
use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use Concerns\HasSequence, Concerns\HasUid, HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_at',
        'end_at',
        'timezone',
        'status',
        'location',
        'activity',
        'group_name',
        'notes',
        'lead_instructor_id',
        'lead_instructor_notes',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => BookingStatus::Tentative->value,
        'location' => 'Fox Coverts Campsite',
        'activity' => 'Climbing',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'status' => BookingStatus::class,
        'sequence' => AsSequence::class,
        'timezone' => AsTimezone::class,
    ];

    /**
     * The attributes that cause the `sequence` to increase.
     */
    protected function sequenced(): array
    {
        return [
            'start_at',
            'end_at',
            'status',
        ];
    }

    public function changes(): HasMany
    {
        return $this->hasMany(Change::class)
            ->orderByDesc('created_at');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('comment', 'status', 'token')->as('attendance')
            ->using(Attendance::class);
    }

    public function lead_instructor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPast(): bool
    {
        if (is_null($this->end_at)) {
            return false;
        }

        return $this->end_at->isPast();
    }

    /**
     * Check if the booking **day** is in the past.
     */
    public function isBeforeToday(): bool
    {
        if (is_null($this->end_at)) {
            return false;
        }

        return $this->end_at->endOfDay()->isPast();
    }

    public function isToday(): bool
    {
        if (is_null($this->start_at) || is_null($this->end_at)) {
            return false;
        }

        return $this->start_at->startOfDay()->isPast()
            && $this->end_at->endOfDay()->isFuture();
    }

    public function isFuture(): bool
    {
        if (is_null($this->end_at)) {
            return false;
        }

        return $this->end_at->isFuture();
    }

    public function isCancelled(): bool
    {
        return $this->status == BookingStatus::Cancelled;
    }

    public function isConfirmed(): bool
    {
        return $this->status == BookingStatus::Confirmed;
    }

    public function isTentative(): bool
    {
        return $this->status == BookingStatus::Tentative;
    }

    /**
     * Sort bookings by start & end.
     *
     * @return void
     */
    public function scopeOrdered(Builder $bookings)
    {
        $bookings->orderBy('start_at')->orderBy('end_at');
    }

    /**
     * Bookings that are not cancelled.
     *
     * @return void
     */
    public function scopeNotCancelled(Builder $bookings)
    {
        $bookings->whereNot('bookings.status', BookingStatus::Cancelled);
    }

    /**
     * Find all bookings within the given start & end dates.
     */
    public function scopeBetween(Builder $bookings, string $start, string $end): void
    {
        $bookings
            ->whereDate('start_at', '>=', $start)
            ->whereDate('end_at', '<=', $end);
    }

    /**
     * Bookings that have not finished yet.
     */
    public function scopeFuture(Builder $bookings): void
    {
        $bookings->whereDate('end_at', '>=', Carbon::now());
    }

    /**
     * Bookings having the given attendee.
     *
     * @param  AttendeeStatus[]  $status  (default excludes `Declined`)
     */
    public function scopeAttendeeStatus(Builder $bookings, User $attendee, array $status = []): void
    {
        $bookings->whereHas('attendees', function (Builder $query) use ($attendee, $status) {
            $query->where('user_id', $attendee->id);
            if (empty($status)) {
                $query->whereNot('status', AttendeeStatus::Declined);
            } else {
                $query->whereIn('status', $status);
            }
        });
    }

    /**
     * Bookings that the given User should be able to view.
     */
    public function scopeForUser(Builder $bookings, User $user): void
    {
        if ($user->isGuest()) {
            $bookings->attendeeStatus($user);
        } elseif ($user->cannot('manage', Booking::class)) {
            $bookings->where(function (Builder $query) use ($user) {
                $query->attendeeStatus($user)
                    ->orWhereIn('status', [BookingStatus::Confirmed]);
            });
        }
    }
}
