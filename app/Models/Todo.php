<?php

namespace App\Models;

use App\Casts\AsSequence;
use App\Enums\TodoStatus;
use App\Events\TodoChanged;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Todo extends Model
{
    use Concerns\HasSequence, Concerns\HasUid, HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'completed_at',
        'description',
        'due_at',
        'location',
        'priority',
        'started_at',
        'status',
        'summary',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'location' => 'Fox Coverts Campsite',
        'status' => TodoStatus::NeedsAction->value,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'due_at' => 'datetime',
        'priority' => 'integer',
        'sequence' => AsSequence::class,
        'started_at' => 'datetime',
        'status' => TodoStatus::class,
    ];

    /**
     * The attributes that cause the `sequence` to increase.
     */
    protected function sequenced(): array
    {
        return [
            'due_at',
            'started_at',
            'status',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Todo $model): void {
            if ($model->isDirty('status')) {
                switch ($model->status) {
                    case TodoStatus::NeedsAction:
                        $model->started_at = null;
                        $model->completed_at = null;
                        break;

                    case TodoStatus::InProcess:
                        $model->started_at ??= $model->freshTimestamp();
                        $model->completed_at = null;
                        break;

                    case TodoStatus::Completed:
                        $model->completed_at = $model->freshTimestamp();
                        break;

                    case TodoStatus::Cancelled:
                        $model->completed_at = $model->freshTimestamp();
                        break;
                }
            }
        });

        static::saved(function (Todo $model): void {
            if ($model->wasChanged('sequence') && Auth::check()) {
                event(new TodoChanged($model, Auth::user(), $model->getChanges()));
            }
        });
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('comment', 'status', 'token')->as('attendance')
            ->using(TodoAttendance::class);
    }

    /**
     * Is this Todo overdue?
     */
    public function isOverdue(): bool
    {
        if ($this->isComplete()) {
            return false;
        }

        if (empty($this->due_at)) {
            return false;
        }

        return $this->due_at->isPast();
    }

    /**
     * Has this Todo been completed?
     */
    public function isComplete(): bool
    {
        return ! empty($this->completed_at) || in_array($this->status, [
            TodoStatus::Completed,
            TodoStatus::Cancelled,
        ]);
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
            ->orderByRaw('-completed_at ASC')
            ->orderBy('priority')
            ->orderByRaw('-due_at DESC');
    }
}
