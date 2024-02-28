<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'start_time',
        'end_time',
        'status',
        'location',
        'activity',
        'group_name',
        'notes',
        'lead_instructor_id',
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
    ];

    protected function getStartDateAttribute()
    {
        return $this->start_at->toDateString();
    }

    protected function setStartDateAttribute($value)
    {
        $new_date = Carbon::parse($value);

        if (is_null($this->start_at)) {
            $this->start_at = $new_date;
        } else {
            $this->start_at = $this->start_at->setDateFrom($new_date);
        }

        if (is_null($this->end_at)) {
            $this->end_at = $new_date;
        } else {
            $this->end_at = $this->end_at->setDateFrom($new_date);
        }
    }

    protected function getStartTimeAttribute()
    {
        return $this->start_at->format('H:i');
    }

    protected function setStartTimeAttribute($value)
    {
        $new_time = Carbon::parse($value);
        if (is_null($this->start_at)) {
            $this->start_at = $new_time;
        } else {
            $this->start_at = $this->start_at->setTimeFrom($new_time);
        }
    }

    protected function getEndTimeAttribute()
    {
        return $this->end_at->format('H:i');
    }

    protected function setEndTimeAttribute($value)
    {
        $new_time = Carbon::parse($value);
        if (is_null($this->end_at)) {
            if (!is_null($this->start_at)) {
                $this->end_at = $this->start_at->setTimeFrom($new_time);
            } else {
                $this->end_at = $new_time;
            }
        } else {
            $this->end_at = $this->end_at->setTimeFrom($new_time);
        }
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('status')->as('attendance')
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

    public function isFuture(): bool
    {
        if (is_null($this->end_at)) {
            return false;
        }
        return $this->end_at->isFuture();
    }

    public function isCancelled(): bool
    {
        return ($this->status == BookingStatus::Cancelled);
    }

    public function isConfirmed(): bool
    {
        return ($this->status == BookingStatus::Confirmed);
    }

    public function isTentative(): bool
    {
        return ($this->status == BookingStatus::Tentative);
    }
}
