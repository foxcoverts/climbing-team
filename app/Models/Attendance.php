<?php

namespace App\Models;

use App\Enums\AttendeeRole;
use App\Enums\AttendeeStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Attendance extends Pivot
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'role',
    ];


    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => AttendeeStatus::NeedsAction,
        'role' => null,
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => AttendeeStatus::class,
        'role' => AttendeeRole::class,
    ];
}
