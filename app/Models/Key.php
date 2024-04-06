<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Key extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'holder_id',
    ];

    protected $attributes = [
        'name' => 'Key',
    ];

    public function holder(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bookings that the given User should be able to view.
     */
    public function scopeForUser(Builder $keys, User $user): void
    {
        if ($user->cannot('manage', Key::class)) {
            $keys->where('holder_id', $user->id);
        }
    }
}
