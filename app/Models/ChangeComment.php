<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ChangeComment extends Model
{
    use HasUlids;

    // Timestamps are stored on the change
    public $timestamps = false;

    public $fillable = [
        'body',
    ];

    public function change(): BelongsTo
    {
        return $this->belongsTo(Change::class);
    }

    public function author(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Change::class,
            'id', // changes.id
            'id', // users.id
            'change_id', // change_comments.change_id
            'author_id' // changes.author_id
        );
    }

    public function getCreatedAtAttribute()
    {
        return $this->change->created_at;
    }

    public function getUpdatedAtAttribute()
    {
        return $this->change->updated_at;
    }
}
