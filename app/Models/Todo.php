<?php

namespace App\Models;

use App\Casts\AsSequence;
use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Todo extends Model
{
    use Concerns\HasSequence, Concerns\HasUid, HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'summary',
        'description',
        'location',
        'priority',
        'status',
        'due_at',
        'started_at',
        'completed_at',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => TodoStatus::NeedsAction->value,
        'location' => 'Fox Coverts Campsite',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TodoStatus::class,
        'due_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'sequence' => AsSequence::class,
    ];

    /**
     * The attributes that cause the `sequence` to increase.
     */
    protected function sequenced(): array
    {
        return [
            'status',
            'due_at',
            'started_at',
        ];
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('comment', 'status', 'token')->as('attendance')
            ->using(TodoAttendance::class);
    }

    public function changes(): MorphMany
    {
        return $this->morphMany(Change::class, 'changeable')
            ->orderByDesc('created_at');
    }

    /**
     * Is this Todo overdue?
     */
    public function isOverdue(): bool
    {
        if (empty($this->due_at)) {
            return false;
        }

        return $this->due_at->isPast();
    }

    /**
     * Filter by status.
     *
     * @param  mixed  $status
     */
    public function scopeWithStatus(Builder $todos, $status = []): void
    {
        if (empty($status)) {
            $status = [TodoStatus::NeedsAction, TodoStatus::InProcess];
        }
        $todos->whereIn('status', $status);
    }

    /**
     * Sort by status, priority, and due_at.
     */
    public function scopeOrdered(Builder $todos): void
    {
        $todos
            ->orderBy('status')
            ->orderBy('priority')
            ->orderByRaw('-due_at DESC');
    }
}
