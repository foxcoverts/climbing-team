<?php

namespace App\Models;

use App\Casts\AsSequence;
use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
