<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_at',
        'end_at',
        'status',
        'location',
        'activity',
        'group_name',
        'notes',
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

    public function scopePast(Builder $builder): Builder
    {
        return $builder->whereDate('end_at', '<=', Carbon::now());
    }

    public function scopeFuture(Builder $builder): Builder
    {
        return $builder->whereDate('end_at', '>', Carbon::now());
    }

    public function scopeOfStatus(Builder $builder, BookingStatus ...$status): Builder
    {
        return $builder->whereIn('status', $status);
    }
}
