<?php

namespace App\Models;

use App\Enums\TodoAttendeeStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class TodoAttendance extends Pivot
{
    protected $table = 'todo_user';

    /**
     * Create a new instance from a User and a Todo.
     */
    public static function build(Todo $todo, User $user): static
    {
        $attendance = new static;
        $attendance->todo = $todo;
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
        'status' => TodoAttendeeStatus::NeedsAction,
        'comment' => null,
    ];

    /**
     * Make a new token for a BookingAttendance.
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
        'status' => TodoAttendeeStatus::class,
    ];

    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
